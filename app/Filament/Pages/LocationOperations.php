<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Actions\Attendance\CheckInMemberAction;
use App\Actions\Orders\CheckoutOrderAction;
use App\DTO\CheckoutData;
use App\Models\Member;
use App\Models\MembershipPackage;
use App\Models\MembershipPurchase;
use App\Models\Product;
use App\Models\User;
use App\Support\AdminShift;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use UnitEnum;

class LocationOperations extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::BuildingStorefront;

    protected static ?string $navigationLabel = 'Operasional Lokasi';

    protected static string|UnitEnum|null $navigationGroup = 'Admin Lokasi';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.location-operations';

    public string $memberName = '';

    public string $memberEmail = '';

    public string $memberPhone = '';

    public string $memberPassword = 'password123';

    public ?int $packageMemberId = null;

    public ?int $packageId = null;

    public string $packagePaymentMethod = 'cash';

    public ?int $orderMemberId = null;

    public ?int $productId = null;

    public int $productQuantity = 1;

    public string $orderPaymentMethod = 'cash';

    public ?int $checkInMemberId = null;

    public string $checkInLocation = 'Akhwat Gym Studio';

    public function getTitle(): string|Htmlable
    {
        return 'Operasional Lokasi';
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['Owner', 'Super admin', 'Admin di lokasi']) ?? false;
    }

    /**
     * @return array<int, string>
     */
    public function memberOptions(): array
    {
        return Member::query()
            ->with('user')
            ->latest()
            ->get()
            ->mapWithKeys(fn (Member $member): array => [
                $member->id => ($member->user?->name ?? "Member #{$member->id}").' - '.$member->member_code,
            ])
            ->all();
    }

    /**
     * @return array<int, string>
     */
    public function packageOptions(): array
    {
        return MembershipPackage::query()
            ->where('is_active', true)
            ->orderBy('price')
            ->pluck('name', 'id')
            ->all();
    }

    /**
     * @return array<int, string>
     */
    public function productOptions(): array
    {
        return Product::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn (Product $product): array => [
                $product->id => "{$product->name} - Stok {$product->stock} - Rp ".number_format((float) $product->price, 0, ',', '.'),
            ])
            ->all();
    }

    public function registerMember(): void
    {
        $data = $this->validate([
            'memberName' => ['required', 'string', 'max:255'],
            'memberEmail' => ['required', 'email', 'max:255', 'unique:users,email'],
            'memberPhone' => ['nullable', 'string', 'max:32'],
            'memberPassword' => ['required', 'string', 'min:8'],
        ]);

        $member = DB::transaction(function () use ($data): Member {
            $user = User::query()->create([
                'name' => $data['memberName'],
                'email' => $data['memberEmail'],
                'phone' => $data['memberPhone'] ?: null,
                'password' => Hash::make($data['memberPassword']),
            ]);

            if (method_exists($user, 'assignRole')) {
                $user->assignRole('Member');
            }

            return Member::query()->create([
                'user_id' => $user->id,
                'member_code' => $this->nextMemberCode(),
                'joined_at' => today(),
            ]);
        });

        $this->reset(['memberName', 'memberEmail', 'memberPhone']);
        $this->memberPassword = 'password123';
        $this->packageMemberId = $member->id;
        $this->orderMemberId = $member->id;
        $this->checkInMemberId = $member->id;

        Notification::make()
            ->title('Member berhasil didaftarkan')
            ->body($member->user?->name.' siap dipilih untuk paket, belanja, atau check-in.')
            ->success()
            ->send();
    }

    public function sellMembership(): void
    {
        $data = $this->validate([
            'packageMemberId' => ['required', 'integer', 'exists:members,id'],
            'packageId' => ['required', 'integer', 'exists:membership_packages,id'],
            'packagePaymentMethod' => ['required', 'string', 'in:cash,qris,bank_transfer,manual_transfer'],
        ]);

        $package = MembershipPackage::query()->where('is_active', true)->findOrFail((int) $data['packageId']);
        $startsAt = now();

        MembershipPurchase::query()->create([
            'member_id' => (int) $data['packageMemberId'],
            ...AdminShift::stamp(auth()->user()),
            'membership_package_id' => $package->id,
            'starts_at' => $startsAt,
            'expires_at' => $startsAt->copy()->addDays($package->duration_days),
            'status' => 'active',
            'includes_personal_trainer' => $package->includes_personal_trainer,
            'visits_allowed' => $package->has_visit_limit ? $package->visit_limit : null,
            'visits_used' => 0,
            'payment_method' => $data['packagePaymentMethod'],
            'amount' => $package->price,
            'payment_reference' => 'ADMIN-'.Str::upper(Str::random(10)),
        ]);

        Notification::make()
            ->title('Paket member berhasil didaftarkan')
            ->body('Transaksi tercatat untuk '.AdminShift::label(AdminShift::forUser(auth()->user())).'.')
            ->success()
            ->send();
    }

    public function sellProduct(): void
    {
        $data = $this->validate([
            'orderMemberId' => ['required', 'integer', 'exists:members,id'],
            'productId' => ['required', 'integer', 'exists:products,id'],
            'productQuantity' => ['required', 'integer', 'min:1'],
            'orderPaymentMethod' => ['required', 'string', 'in:cash,qris,bank_transfer,manual_transfer'],
        ]);

        try {
            $member = Member::query()->findOrFail((int) $data['orderMemberId']);
            $order = app(CheckoutOrderAction::class)->execute($member, new CheckoutData(
                items: [[
                    'product_id' => (int) $data['productId'],
                    'quantity' => (int) $data['productQuantity'],
                ]],
                paymentMethod: $data['orderPaymentMethod'],
            ), auth()->user(), $data['orderPaymentMethod'] === 'cash');
        } catch (ValidationException $exception) {
            Notification::make()
                ->title('Pesanan produk ditolak')
                ->body(collect($exception->errors())->flatten()->first() ?? 'Produk tidak bisa dipesan.')
                ->danger()
                ->send();

            return;
        }

        $this->productQuantity = 1;

        Notification::make()
            ->title('Pesanan produk berhasil dibuat')
            ->body('Stok sudah dikurangi dan transaksi masuk laporan shift.')
            ->success()
            ->send();
    }

    public function manualCheckIn(): void
    {
        $data = $this->validate([
            'checkInMemberId' => ['required', 'integer', 'exists:members,id'],
            'checkInLocation' => ['required', 'string', 'max:160'],
        ]);

        try {
            $attendance = app(CheckInMemberAction::class)->execute(
                member: Member::query()->with('user')->findOrFail((int) $data['checkInMemberId']),
                attendanceType: 'gym_visit',
                location: $data['checkInLocation'],
                admin: auth()->user(),
            );

            Notification::make()
                ->title('Check-in manual berhasil')
                ->body(($attendance->member->user?->name ?? 'Member').' tercatat hadir.')
                ->success()
                ->send();
        } catch (ValidationException $exception) {
            Notification::make()
                ->title('Check-in ditolak')
                ->body(collect($exception->errors())->flatten()->first() ?? 'Data check-in tidak valid.')
                ->danger()
                ->send();
        }
    }

    private function nextMemberCode(): string
    {
        $lastId = (int) Member::query()->max('id');

        return 'MBR'.str_pad((string) ($lastId + 1), 6, '0', STR_PAD_LEFT);
    }
}

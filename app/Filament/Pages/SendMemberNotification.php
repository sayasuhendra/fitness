<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Member;
use App\Services\Notifications\MemberNotificationService;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;

class SendMemberNotification extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Bell;

    protected static ?string $navigationLabel = 'Kirim Notifikasi';

    protected static string|UnitEnum|null $navigationGroup = 'Notifications';

    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.pages.send-member-notification';

    public string $target = 'all';

    /**
     * @var array<int, int>
     */
    public array $memberIds = [];

    public string $notificationTitle = '';

    public string $body = '';

    public string $type = 'admin_broadcast';

    public ?string $actionUrl = '/notifications';

    public int $sentCount = 0;

    public function getTitle(): string|Htmlable
    {
        return 'Kirim Notifikasi';
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->can('viewAny', Member::class) ?? false;
    }

    /**
     * @return array<int, string>
     */
    public function getMemberOptionsProperty(): array
    {
        return Member::query()
            ->with('user')
            ->whereHas('user')
            ->orderBy('member_code')
            ->get()
            ->mapWithKeys(fn (Member $member): array => [
                $member->id => trim(sprintf(
                    '%s - %s (%s)',
                    $member->member_code,
                    $member->user?->name ?? 'Member',
                    $member->user?->email ?? '-',
                )),
            ])
            ->all();
    }

    public function send(MemberNotificationService $notifications): void
    {
        $validated = $this->validate([
            'target' => ['required', 'in:all,selected'],
            'memberIds' => ['array'],
            'memberIds.*' => ['integer', 'exists:members,id'],
            'notificationTitle' => ['required', 'string', 'max:80'],
            'body' => ['required', 'string', 'max:240'],
            'type' => ['required', 'string', 'max:80'],
            'actionUrl' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validated['target'] === 'selected' && count($validated['memberIds']) === 0) {
            $this->addError('memberIds', 'Pilih minimal satu member.');

            return;
        }

        $members = Member::query()
            ->with('user')
            ->whereHas('user')
            ->when(
                $validated['target'] === 'selected',
                fn ($query) => $query->whereIn('id', $validated['memberIds'])
            )
            ->get();

        foreach ($members as $member) {
            if ($member->user === null) {
                continue;
            }

            $notifications->send(
                user: $member->user,
                title: $validated['notificationTitle'],
                body: $validated['body'],
                type: $validated['type'],
                actionUrl: $validated['actionUrl'] ?: null,
            );
        }

        $this->sentCount = $members->count();
        $this->reset(['memberIds', 'notificationTitle', 'body']);
        $this->target = 'all';
        $this->type = 'admin_broadcast';
        $this->actionUrl = '/notifications';

        Notification::make()
            ->title('Notifikasi dikirim')
            ->body("Notifikasi dikirim ke {$this->sentCount} member.")
            ->success()
            ->send();
    }
}

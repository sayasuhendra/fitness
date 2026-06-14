<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Membership\PurchaseMembershipAction;
use App\DTO\PurchaseMembershipData;
use App\Http\Requests\Membership\PurchaseMembershipRequest;
use App\Http\Resources\MembershipHistoryResource;
use App\Http\Resources\MembershipPackageResource;
use App\Models\MembershipPackage;
use App\Services\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index(): JsonResponse
    {
        $packages = MembershipPackage::query()->where('is_active', true)->oldest('price')->get();

        return ApiResponder::success(MembershipPackageResource::collection($packages), 'Membership packages retrieved');
    }

    public function purchase(PurchaseMembershipRequest $request, PurchaseMembershipAction $action): JsonResponse
    {
        $purchase = $action->execute($request->user()->member, new PurchaseMembershipData(
            packageId: (int) $request->validated('package_id'),
            paymentMethod: $request->validated('payment_method'),
        ));

        return ApiResponder::success(new MembershipHistoryResource($purchase->load('package')), 'Membership activated', 201);
    }

    public function history(Request $request): JsonResponse
    {
        $history = $request->user()->member->membershipPurchases()
            ->with('package')
            ->latest()
            ->get();

        return ApiResponder::success(MembershipHistoryResource::collection($history), 'Membership history retrieved');
    }
}

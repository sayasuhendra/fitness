<?php

declare(strict_types=1);

namespace App\Actions\Payments;

use App\Models\PaymentConfirmation;
use App\Models\User;
use App\Support\AdminShift;

class RejectPaymentConfirmationAction
{
    public function execute(PaymentConfirmation $confirmation, User $admin, ?string $adminNote = null): PaymentConfirmation
    {
        $confirmation->update([
            'status' => 'rejected',
            'admin_note' => $adminNote,
            'verified_by' => $admin->id,
            'handled_shift' => AdminShift::forUser($admin),
            'handled_date' => AdminShift::date()->toDateString(),
            'verified_at' => now(),
        ]);

        return $confirmation->refresh();
    }
}

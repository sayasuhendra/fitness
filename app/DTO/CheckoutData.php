<?php

declare(strict_types=1);

namespace App\DTO;

final readonly class CheckoutData
{
    /**
     * @param  array<int, array{product_id:int, quantity:int}>  $items
     */
    public function __construct(
        public array $items,
        public string $paymentMethod,
    ) {}
}

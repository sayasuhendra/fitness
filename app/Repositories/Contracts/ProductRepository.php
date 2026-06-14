<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface ProductRepository
{
    public function search(?string $category = null, ?string $search = null): Collection;
}

<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price,
            'image_url' => $this->imageUrl(),
            'category' => $this->category->name,
            'stock' => $this->stock,
        ];
    }

    private function imageUrl(): string
    {
        $imageUrl = (string) $this->image_url;

        if ($imageUrl === '') {
            return '';
        }

        if (Str::startsWith($imageUrl, ['http://', 'https://'])) {
            return $imageUrl;
        }

        return asset('storage/'.ltrim($imageUrl, '/'));
    }
}

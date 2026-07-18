<?php

declare(strict_types=1);

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', Rule::exists('products', 'id')],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'string', Rule::in(['qris', 'bank_transfer', 'cash'])],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Keranjang masih kosong.',
            'items.min' => 'Keranjang masih kosong.',
            'items.*.product_id.exists' => 'Produk tidak ditemukan atau sudah tidak tersedia. Silakan hapus produk dari keranjang dan pilih ulang dari toko.',
            'items.*.quantity.min' => 'Jumlah produk minimal 1.',
            'payment_method.in' => 'Metode pembayaran tidak tersedia.',
        ];
    }
}

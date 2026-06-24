<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIuranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_warga' => 'sometimes|exists:warga,id',
            'bulan' => 'sometimes|date_format:Y-m',
            'jumlah_iuran' => 'sometimes|integer|min:0',
            'status' => 'sometimes|in:pending,selesai',
        ];
    }

    public function messages(): array
    {
        return [
            'id_warga.exists' => 'Warga tidak ditemukan',
            'bulan.date_format' => 'Format bulan harus YYYY-MM',
            'jumlah_iuran.integer' => 'Jumlah iuran harus berupa angka',
            'status.in' => 'Status harus pending atau selesai',
        ];
    }
}

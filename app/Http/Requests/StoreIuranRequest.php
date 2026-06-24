<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIuranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_warga' => 'required|exists:warga,id',
            'bulan' => 'required|date_format:Y-m',
            'jumlah_iuran' => 'required|integer|min:0',
            'status' => 'required|in:pending,selesai',
        ];
    }

    public function messages(): array
    {
        return [
            'id_warga.required' => 'Warga harus dipilih',
            'id_warga.exists' => 'Warga tidak ditemukan',
            'bulan.required' => 'Bulan harus diisi',
            'bulan.date_format' => 'Format bulan harus YYYY-MM',
            'jumlah_iuran.required' => 'Jumlah iuran harus diisi',
            'jumlah_iuran.integer' => 'Jumlah iuran harus berupa angka',
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status harus pending atau selesai',
        ];
    }
}

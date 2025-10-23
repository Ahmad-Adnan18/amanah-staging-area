<?php

namespace App\Http\Requests\Pengajaran;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSantriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nis' => 'nullable|string|max:20|unique:santris,nis',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => ['required', Rule::in(['Putra', 'Putri'])],
            'rayon' => 'nullable|string|max:50',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kelas_id' => 'required|exists:kelas,id', // <-- TAMBAHKAN INI
        ];
    }
}

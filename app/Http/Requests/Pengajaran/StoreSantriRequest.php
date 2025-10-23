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
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string|max:50',
            'alamat' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'rayon' => 'nullable|string|max:50',
            'asal_sekolah' => 'nullable|string|max:255',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kelas_id' => 'required|exists:kelas,id', // <-- TAMBAHKAN INI
        ];
    }
}

<?php

namespace App\Imports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\ToModel;

class TeachersImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Teacher([
            //
        ]);
    }
    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls']);
        Excel::import(new TeachersImport, $request->file('file'));
        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil diimpor!');
    }
}

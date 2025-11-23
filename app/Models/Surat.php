<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;
    
    // Set agar ID tidak auto-increment jika Anda ingin ID = No. Surat
    public $incrementing = false; 
    protected $fillable = ['id', 'nama_surat', 'jumlah_ayat'];
}
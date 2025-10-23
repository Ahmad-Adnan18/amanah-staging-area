<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = ['room_id', 'name', 'quantity', 'description'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
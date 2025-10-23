<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type'];

    /**
     * Get the schedules for the room.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Get the inventory items for the room.
     */
    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }
}
<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryItemController extends Controller
{
    // Hapus atau komentari baris ini
    // public function __construct()
    // {
    //     $this->middleware('role:admin,pengajaran');
    // }

    public function index(Room $room)
    {
        if (Auth::check() && in_array(Auth::user()->role, ['admin', 'pengajaran'])) {
            $inventoryItems = $room->inventoryItems()->orderBy('name')->get();
            return view('admin.master-data.inventory-items.index', compact('room', 'inventoryItems'));
        }
        abort(403, 'Unauthorized action.');
    }

    public function create(Room $room)
    {
        if (Auth::check() && in_array(Auth::user()->role, ['admin', 'pengajaran'])) {
            return view('admin.master-data.inventory-items.create', compact('room'));
        }
        abort(403, 'Unauthorized action.');
    }

    public function store(Request $request, Room $room)
    {
        if (Auth::check() && in_array(Auth::user()->role, ['admin', 'pengajaran'])) {
            $request->validate([
                'name' => 'required|string|max:255',
                'quantity' => 'required|integer|min:0',
                'description' => 'nullable|string|max:500',
            ]);

            $room->inventoryItems()->create($request->all());

            return redirect()->route('admin.rooms.inventory.index', $room)->with('success', 'Inventaris berhasil ditambahkan.');
        }
        abort(403, 'Unauthorized action.');
    }

    public function edit(Room $room, InventoryItem $inventoryItem)
    {
        if (Auth::check() && in_array(Auth::user()->role, ['admin', 'pengajaran'])) {
            return view('admin.master-data.inventory-items.edit', compact('room', 'inventoryItem'));
        }
        abort(403, 'Unauthorized action.');
    }

    public function update(Request $request, Room $room, InventoryItem $inventoryItem)
    {
        if (Auth::check() && in_array(Auth::user()->role, ['admin', 'pengajaran'])) {
            $request->validate([
                'name' => 'required|string|max:255',
                'quantity' => 'required|integer|min:0',
                'description' => 'nullable|string|max:500',
            ]);

            $inventoryItem->update($request->all());

            return redirect()->route('admin.rooms.inventory.index', $room)->with('success', 'Inventaris berhasil diperbarui.');
        }
        abort(403, 'Unauthorized action.');
    }

    public function destroy(Room $room, InventoryItem $inventoryItem)
    {
        if (Auth::check() && in_array(Auth::user()->role, ['admin', 'pengajaran'])) {
            $inventoryItem->delete();
            return redirect()->route('admin.rooms.inventory.index', $room)->with('success', 'Inventaris berhasil dihapus.');
        }
        abort(403, 'Unauthorized action.');
    }
}
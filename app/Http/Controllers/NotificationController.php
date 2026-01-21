<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class NotificationController extends Controller
{
    /**
     * Roles yang diizinkan untuk mengelola notifikasi
     */
    private array $allowedManageRoles = ['admin', 'pengajaran', 'pengasuhan', 'kesehatan', 'ubudiyah'];

    /**
     * Roles yang tidak bisa melihat notifikasi
     */
    private array $excludedViewRoles = ['wali_santri'];

    /**
     * Middleware check untuk management access
     */
    private function canManage(): bool
    {
        return in_array(Auth::user()->role, $this->allowedManageRoles);
    }

    /**
     * Middleware check untuk view access
     */
    private function canView(): bool
    {
        return !in_array(Auth::user()->role, $this->excludedViewRoles);
    }

    /**
     * Halaman daftar notifikasi untuk user
     */
    public function index()
    {
        if (!$this->canView()) {
            abort(403, 'Anda tidak memiliki akses ke fitur notifikasi.');
        }

        $notifications = Notification::visibleToCurrentUser()
            ->with('creator:id,name')
            ->orderByDesc('published_at')
            ->paginate(15);

        // Get unread notification IDs for current user
        $readNotificationIds = NotificationRead::where('user_id', Auth::id())
            ->pluck('notification_id')
            ->toArray();

        return view('notifications.index', compact('notifications', 'readNotificationIds'));
    }

    /**
     * Halaman detail notifikasi
     */
    public function show(Notification $notification)
    {
        if (!$this->canView()) {
            abort(403, 'Anda tidak memiliki akses ke fitur notifikasi.');
        }

        // Cek apakah notifikasi visible untuk user ini
        $userRole = Auth::user()->role;
        if ($notification->target_roles && !in_array($userRole, $notification->target_roles)) {
            abort(404);
        }

        // Cek apakah sudah published
        if (!$notification->published_at || $notification->published_at > now()) {
            abort(404);
        }

        // Otomatis tandai sebagai sudah dibaca
        NotificationRead::firstOrCreate([
            'user_id' => Auth::id(),
            'notification_id' => $notification->id,
        ], [
            'read_at' => now(),
        ]);

        return view('notifications.show', compact('notification'));
    }

    /**
     * Tandai notifikasi sebagai sudah dibaca
     */
    public function markAsRead(Notification $notification)
    {
        if (!$this->canView()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        NotificationRead::firstOrCreate([
            'user_id' => Auth::id(),
            'notification_id' => $notification->id,
        ], [
            'read_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Tandai semua notifikasi sebagai sudah dibaca
     */
    public function markAllAsRead()
    {
        if (!$this->canView()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $visibleNotifications = Notification::visibleToCurrentUser()->pluck('id');
        
        foreach ($visibleNotifications as $notificationId) {
            NotificationRead::firstOrCreate([
                'user_id' => Auth::id(),
                'notification_id' => $notificationId,
            ], [
                'read_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai sudah dibaca.');
    }

    /**
     * API: Get unread count for current user
     */
    public function getUnreadCount()
    {
        if (!$this->canView()) {
            return response()->json(['count' => 0]);
        }

        $totalVisible = Notification::visibleToCurrentUser()->count();
        $readCount = NotificationRead::where('user_id', Auth::id())
            ->whereIn('notification_id', Notification::visibleToCurrentUser()->pluck('id'))
            ->count();

        return response()->json(['count' => $totalVisible - $readCount]);
    }

    // ========================================
    // MANAGEMENT METHODS (CRUD)
    // ========================================

    /**
     * Halaman manajemen notifikasi untuk admin
     */
    public function manage()
    {
        if (!$this->canManage()) {
            abort(403, 'Anda tidak memiliki akses untuk mengelola notifikasi.');
        }

        $notifications = Notification::with('creator:id,name')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('notifications.manage.index', compact('notifications'));
    }

    /**
     * Form tambah notifikasi baru
     */
    public function create()
    {
        if (!$this->canManage()) {
            abort(403);
        }

        $types = Notification::getTypes();
        $targetRoles = Notification::getTargetableRoles();

        return view('notifications.manage.create', compact('types', 'targetRoles'));
    }

    /**
     * Simpan notifikasi baru
     */
    public function store(Request $request)
    {
        if (!$this->canManage()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => ['required', Rule::in(array_keys(Notification::getTypes()))],
            'target_roles' => 'nullable|array',
            'target_roles.*' => Rule::in(array_keys(Notification::getTargetableRoles())),
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
            'publish_now' => 'nullable|boolean',
        ]);

        // Jika publish_now, set published_at ke sekarang
        if ($request->boolean('publish_now')) {
            $validated['published_at'] = now();
        }

        // Jika target_roles kosong, set null (untuk semua role)
        if (empty($validated['target_roles'])) {
            $validated['target_roles'] = null;
        }

        $validated['created_by'] = Auth::id();

        $notification = Notification::create($validated);

        // --- KIRIM PUSH NOTIFICATION ---
        if ($request->boolean('publish_now')) {
            try {
                // Determine target users
                $query = \App\Models\User::whereNotNull('fcm_token');
                
                if ($notification->target_roles) {
                    $query->whereIn('role', $notification->target_roles);
                } else {
                    // Jika broadcast semua, tetap exclude wali_santri sesuai aturan awal
                    $query->where('role', '!=', 'wali_santri');
                }

                $tokens = $query->pluck('fcm_token')->toArray();
                
                if (!empty($tokens)) {
                    $fcm = new \App\Services\FcmService();
                    $fcm->broadcast(
                        $tokens,
                        $notification->title,
                        // Strip tags agar plain text di notif HP
                        strip_tags($notification->message), 
                        ['notification_id' => (string) $notification->id]
                    );
                }
            } catch (\Exception $e) {
                // Silent fail agar tidak mengganggu flow simpan notif
                \Illuminate\Support\Facades\Log::error('FCM Broadcast Error: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notifikasi berhasil dibuat.');
    }

    /**
     * Form edit notifikasi
     */
    public function edit(Notification $notification)
    {
        if (!$this->canManage()) {
            abort(403);
        }

        $types = Notification::getTypes();
        $targetRoles = Notification::getTargetableRoles();

        return view('notifications.manage.edit', compact('notification', 'types', 'targetRoles'));
    }

    /**
     * Update notifikasi
     */
    public function update(Request $request, Notification $notification)
    {
        if (!$this->canManage()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => ['required', Rule::in(array_keys(Notification::getTypes()))],
            'target_roles' => 'nullable|array',
            'target_roles.*' => Rule::in(array_keys(Notification::getTargetableRoles())),
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
            'publish_now' => 'nullable|boolean',
        ]);

        // Jika publish_now, set published_at ke sekarang
        if ($request->boolean('publish_now') && !$notification->published_at) {
            $validated['published_at'] = now();
        }

        // Jika target_roles kosong, set null (untuk semua role)
        if (empty($validated['target_roles'])) {
            $validated['target_roles'] = null;
        }

        $notification->update($validated);

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notifikasi berhasil diperbarui.');
    }

    /**
     * Hapus notifikasi
     */
    public function destroy(Notification $notification)
    {
        if (!$this->canManage()) {
            abort(403);
        }

        $notification->delete();

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notifikasi berhasil dihapus.');
    }
}

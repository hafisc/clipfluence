<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('user')
            ->latest()
            ->take(200)
            ->get();

        $history = $notifications
            ->groupBy(fn (Notification $notification) => implode('|', [
                $notification->title,
                $notification->message,
                $notification->type,
                $notification->created_at?->format('Y-m-d H:i'),
            ]))
            ->map(function ($group) {
                $first = $group->first();
                $roles = $group->pluck('user.role')->filter()->unique()->values();

                return [
                    'title' => $first->title,
                    'message' => $first->message,
                    'type' => $first->type,
                    'icon' => $first->icon ?: 'bell',
                    'action_url' => $first->action_url,
                    'target' => $this->formatTarget($roles, $group->count()),
                    'sent' => $first->created_at?->format('d M Y, H:i'),
                    'reach' => $group->count(),
                ];
            })
            ->values();

        return view('admin.notifications.index', compact('history'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'target' => ['required', 'in:all,kreator,brand'],
            'type' => ['required', 'in:info,success,warning,error'],
            'title' => ['required', 'string', 'max:120'],
            'message' => ['required', 'string', 'max:1000'],
            'action_url' => ['nullable', 'string', 'max:255'],
        ]);

        $users = User::query()
            ->when($validated['target'] !== 'all', fn ($query) => $query->where('role', $validated['target']))
            ->whereIn('role', ['kreator', 'brand'])
            ->get(['id']);

        if ($users->isEmpty()) {
            return back()->withInput()->with('error', 'Tidak ada pengguna untuk target penerima tersebut.');
        }

        $now = now();
        $icon = match ($validated['type']) {
            'success' => 'check-circle',
            'warning' => 'alert-triangle',
            'error' => 'x-circle',
            default => 'bell',
        };

        Notification::insert($users->map(fn (User $user) => [
            'user_id' => $user->id,
            'type' => $validated['type'],
            'icon' => $icon,
            'title' => $validated['title'],
            'message' => $validated['message'],
            'action_url' => $validated['action_url'] ?: null,
            'created_at' => $now,
            'updated_at' => $now,
        ])->all());

        return redirect()
            ->route('admin.notifications')
            ->with('success', 'Notifikasi berhasil dikirim ke ' . $users->count() . ' pengguna.');
    }

    private function formatTarget($roles, int $count): string
    {
        if ($roles->contains('kreator') && $roles->contains('brand')) {
            return 'Semua Pengguna';
        }

        if ($roles->contains('kreator')) {
            return 'Hanya Kreator';
        }

        if ($roles->contains('brand')) {
            return 'Hanya Merek';
        }

        return $count . ' pengguna';
    }
}

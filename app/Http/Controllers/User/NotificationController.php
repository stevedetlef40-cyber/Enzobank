<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $page_title = 'Notifications';
        $notifications = UserNotification::auth()
            ->latest('id')
            ->paginate(20);

        $unreadCount = UserNotification::auth()
            ->where('is_read', false)
            ->count();

        return view('user.sections.notifications.index', compact(
            'page_title',
            'notifications',
            'unreadCount'
        ));
    }

    public function read(UserNotification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function readAll()
    {
        UserNotification::auth()
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function show(UserNotification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['is_read' => true]);

        // Derive a route from the notification type
        $route = '/user/transactions';
        $type = $notification->type;
        if (in_array($type, ['OWN-BANK-TRANSFER', 'OTHER-BANK-TRANSFER', 'MOBILE-WALLET-TRANSFER', 'FUND-RECEIVED', 'SECURITY'])) {
            $route = '/user/transactions';
        } elseif (in_array($type, ['ADD-MONEY'])) {
            $route = '/user/add-money/index';
        } elseif (in_array($type, ['MONEY-OUT', 'WITHDRAW'])) {
            $route = '/user/money-out/index';
        } elseif ($type === 'VIRTUAL-CARD') {
            $route = '/user/strowallet/virtual/card';
        }

        return redirect($route);
    }
}

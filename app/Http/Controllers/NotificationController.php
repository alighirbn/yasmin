<?php

namespace App\Http\Controllers;



class NotificationController extends Controller
{
  public function index()
  {
    return view('notification.index');
  }

  public function markasread($id)
  {
    if ($id) {
      $notification = auth()->user()->notifications->where('id', $id)->first();
      if ($notification) {
        $notification->markAsRead();
        return redirect()->route($notification->data['route'], $notification->data['url_address']);
      }
    }
  }

  public function markallasread()
  {
    auth()->user()->unreadnotifications->markAsRead();
    return back();
  }

  public function fetch()
  {
    // Fetch all unread notifications
    $unreadNotifications = auth()->user()->unreadNotifications()->get();

    return response()->json($unreadNotifications);
  }
}

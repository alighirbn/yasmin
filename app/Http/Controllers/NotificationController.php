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

  /**
   * Fetch all unread notifications for the authenticated user.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function fetch()
  {
    $user = auth()->user();

    if (!$user) {
      return response()->json(['error' => 'Unauthenticated'], 401);
    }

    // Fetch all unread notifications
    $unreadNotifications = $user->notifications()->whereNull('read_at')->get();

    return response()->json($unreadNotifications);
  }
}

<?php

namespace App\Http\Controllers;

use App\Models\Learner;
use App\Models\Library;
use App\Models\User;
use App\Notifications\CustomNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class NotificationController extends Controller
{
    public function create(){
        return view('notification.custom_notification');
    }

    public function send(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'link' => 'nullable|url',
            'image' => 'nullable|url',
            'guard' => 'required|string|in:web,library,learner',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'link' => $request->link,
            'image' => $request->image,
            'guard' => $request->guard,
        ];
        // Determine the guard and notify users
        $users = match ($request->guard) {
            'web' => User::all(),
            'library' => Library::all(),
            'learner' => Learner::all(),
        };
       // Manually insert each notification for users
    foreach ($users as $user) {
        DB::table('notifications')->insert([
            'id' => Str::uuid()->toString(),
            'type' => CustomNotification::class,
            'notifiable_type' => get_class($user),
            'notifiable_id' => $user->id,
            'data' => json_encode($data),
            'guard' => $request->guard,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

        return redirect()->back()->with('success', 'Notification sent successfully!');
    }

}

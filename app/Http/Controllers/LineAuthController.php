<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LineAuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'line_user_id' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'picture_url' => ['nullable', 'string', 'max:2048'],
        ]);

        $user = User::firstOrCreate(
            ['line_user_id' => $data['line_user_id']],
            [
                'name' => $data['name'],
                'line_picture_url' => $data['picture_url'] ?? null,
            ]
        );

        // Update profile if changed
        $user->update([
            'name' => $data['name'],
            'line_picture_url' => $data['picture_url'] ?? $user->line_picture_url,
        ]);

        Auth::login($user, remember: true);

        return response()->json(['status' => 'ok']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['status' => 'ok']);
    }
}

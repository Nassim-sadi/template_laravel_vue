<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Notifications\ResetPasswordNotification;
use App\Http\Requests\auth\ForgotPasswordRequest;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $input = $request->only('email');
        $user = User::where('email', $input)->first();
        $user->notify(new ResetPasswordNotification());
        $success['succees'] = true;
        return response()->json($success, 200);
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\TryCatch;

class UserController extends Controller

{
    public function edit(Request $request)
    {
        $user = $request->user();

        return $user;
    }

    public function updateProfilePicture(Request $request)
    {

        $user = $request->user();

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($user->image) {
                $oldImage = basename(parse_url($user->image, PHP_URL_PATH));
            }
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName() . '-' . time() . '.' . $image->extension();
            $destinationPath = public_path('/storage/images/profile');
            $image->move($destinationPath, $imageName);
            $user->image = $imageName;
            if (file_exists("storage/images/profile/" . $oldImage)) {
                unlink("storage/images/profile/" . $oldImage);
            }
            $user->save();
            $user->refresh();
            return response()->json(['success' => 'Image uploaded successfully', 'user' => $user], 200);
        } else {
            return response()->json(['message' => 'No image found + ' . $request->image], 404);
        }
    }
}
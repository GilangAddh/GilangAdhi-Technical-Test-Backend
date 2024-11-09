<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserData;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function update(Request $request, $id)
    {
        try {
            // Validasi
            $data = $request->validate([
                'name' => 'string',
                'email' => ['string', 'email', Rule::unique('users')->ignore($id)],
                'punish_date' => 'nullable|date',
                'is_residential' => 'nullable|boolean',
                'is_commercial' => 'nullable|boolean',
            ]);

            // Cari user berdasarkan ID
            $user = UserData::findOrFail($id);

            // Jika ada perubahan password
            if ($request->has('password')) {
                $request->validate([
                    'password' => 'required|string|min:8|confirmed',
                ]);
                $user->password = bcrypt($request->password);
            }

            // Update data user
            $user->update([
                'name' => $data['name'] ?? $user->name,
                'email' => $data['email'] ?? $user->email,
                'punish_date' => $data['punish_date'] ?? $user->punish_date,
                'is_residential' => $data['is_residential'] ?? $user->is_residential,
                'is_commercial' => $data['is_commercial'] ?? $user->is_commercial,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Http\Controllers\Controller;
use App\Helpers\ResponseFormatter;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function registrasi(Request $request) {
        try {

            if(!$request->filled(['name', 'email', 'password'])) {
                return response()->json(['message' => 'semua field harus diisi'], 422);
            }

            // validasi request
            $request->validate([
                'name' => ['required', 'string', 'max:255', 'htmlspecialchars'],
                'username' => ['required', 'string', 'max:255', 'unique:users', 'htmlspecialchars'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'phone' => ['nullable', 'string', 'max:255'],
                'password' => 'required|string|min:8|regex:/^.*(?=.{3,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@$!%*?&])[a-zA-Z0-9@$!%*?&].*$/',
            ]);

            // Implementasi logika verifikasi email di sini (tidak ditampilkan)

            // Buat pengguna baru
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => bcrypt($request->password),
            ]);

            $user = User::where('email', $request->email)->first();

            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ],'User Registered');
        } catch (ValidationException $e) {
            return ResponseFormatter::error([
              'message' => 'Validasi gagal',
              'errors' => $e->errors(),
            ], 'Registrasi Gagal', 422);
          } catch (Exception $e) {
            // Catat error untuk debugging
            Log::error($e);

            return ResponseFormatter::error([
              'message' => 'Terjadi kesalahan',
            ], 'Registrasi Gagal', 500);
          }
    }
}

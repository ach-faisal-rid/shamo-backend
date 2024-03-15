<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Http\Controllers\Controller;
use App\Helpers\ResponseFormatter;
use Illuminate\Http\Request;
use App\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    public function registrasi(Request $request) {
        try {

            if(!$request->filled(['name', 'email', 'password'])) {
                return response()->json(['message' => 'semua field harus diisi'], 422);
            }

            // validasi request
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255', 'unique:users'],
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
        }
    }

    public function login (Request $request) {
        try {

            if(!$request->filled(['email', 'password'])) {
                return response()->json(['message' => 'semua field harus diisi'], 422);
            }

            // validasi request
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            // Autentikasi Pengguna
            $credentials = request(['email', 'password']);
            // Melakukan autentikasi pengguna
            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error([
                    'message' => 'unauthorized'
                ], 'Authentication Failed', 500);
            }

            // Mengambil data pengguna dari database
            $user = User::where('email', $request->email)
            ->firstOrFail(); // only retrieve necessary user data

            if (!Hash::check($request->password, $user->password, [])) {
                throw new Exception("Invalid Credentials");
            }

            // Membuat token akses untuk pengguna
            $tokenResult = $user->createToken('authToken', ['*'], now()->addMinutes(60))
            ->plainTextToken; // set token expiration
            // Mengembalikan response success dengan data
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    // include other relevant user information
                ]
            ], 'Authenticated');
        }
        // Penanganan Kesalahan
        // Mengembalikan response error dengan pesan error
        catch (ValidationException $e) {
            // Menangani error validasi
            return ResponseFormatter::error([
              'message' => 'Validasi Gagal',
              'errors' => $e->errors(),
            ], 'Login Gagal', 422);
        } catch (ModelNotFoundException $e) {
            // Handle user not found scenario with specific error message
            return ResponseFormatter::error([
                'message' => 'Email tidak ditemukan.'
            ], 'Login Gagal', 401);
        }
    }

    public function fetch (Request $request) {
        return ResponseFormatter::success($request->user(),
        'Data profile user berhasil diambil');
    }

    public function updateProfile(Request $request) {
        $data = $request->all();

        $user = Auth::user();
        $user->update($data);

        return ResponseFormatter::success($user, 'Profile Updated !');
    }
}

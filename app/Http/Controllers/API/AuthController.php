<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'password_confirmation' => 'required|same:password'
        ]);

        // Encrypt Password (bcrypt)
        $validate['password'] = bcrypt($request->password);

        /// Simpan data user ke tabel user
        $user = User::create($validate);
        if ($user) {
            $data['success'] = true;
            $data['message'] = 'User berhasil disimpan';
            $data['data'] = $user->name; // Nama user
            $data['token'] = $user->createToken('MDPApp')->plainTextToken;
            return response()->json($data, Response::HTTP_CREATED); // 201
        } else {
            $data['success'] = false;
            $data['message'] = 'User gagal disimpan';
            return response()->json($data, Response::HTTP_BAD_REQUEST); // 400
        }
     }

    public function login(Request $request) {
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            $user = Auth::user();
            $data['success'] = true;
            $data['message'] = 'Login berhasil';
            $data['token'] = $user->createToken('MDPApp')->plainTextToken;
            $data['data'] = $user;
            return response()->json($data, Response::HTTP_OK); // 200
        } else {
            $data['success'] = false;
            $data['message'] = 'Login gagal';
            return response()->json($data, Response::HTTP_UNAUTHORIZED); // 401
        }
    }
}

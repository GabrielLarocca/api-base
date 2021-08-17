<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\loginRequest;
use App\Http\Requests\registerRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
    use ApiResponser;

    public function register(registerRequest $request) {
        $data = $request->all();

        $user = User::create([
            'name' => $data['name'],
            'password' => bcrypt($data['password']),
            'email' => $data['email']
        ]);

        return $this->success(['token' => $user->createToken('API Token')->plainTextToken], "Registro feito com sucesso!");
    }

    public function login(loginRequest $request) {
        $data = $request->all();

        if (!Auth::attempt($data)) return $this->error('Informações invalidas', 401);

        return $this->success(['token' => auth()->user()->createToken('API Token')->plainTextToken], "Login feito com sucesso!");
    }

    public function logout() {
        auth()->user()->tokens()->delete();

        return ['message' => 'Tokens Revoked'];
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vertical;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect('/home')
                ->withErrors($validator)
                ->withInput();
        }

        $rawPassword = $request->input('password');
        $secretKey = config('app.key');
        $plainPassword = null;

        try {
            $cipherText = base64_decode($rawPassword);
            if (substr($cipherText, 0, 8) === "Salted__") {
                $salt = substr($cipherText, 8, 8);
                $ciphertext = substr($cipherText, 16);
                $keyAndIV = $this->evpkdf($secretKey, $salt, 32, 16);
                $decrypted = openssl_decrypt($ciphertext, 'aes-256-cbc', $keyAndIV['key'], OPENSSL_RAW_DATA, $keyAndIV['iv']);
                
                if ($decrypted !== false) {
                    $plainPassword = $decrypted;
                }
            }
        } catch (\Exception $e) {
            $plainPassword = null;
        }

        if (!$plainPassword) {
            $plainPassword = $rawPassword;
        }

        $credentials = [
            'username' => $request->username,
            'password' => $plainPassword
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if (Auth::user()->must_change_password) {
                return redirect()->route('profile.change-password');
            }
            return redirect()->intended('dashboard');
        }

        return redirect('/home')->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    /**
     * OpenSSL Key derivation function
     */
    private function evpkdf($password, $salt, $keyLen, $ivLen)
    {
        $derivedBytes = '';
        $block = '';
        while (strlen($derivedBytes) < ($keyLen + $ivLen)) {
            $block = md5($block . $password . $salt, true);
            $derivedBytes .= $block;
        }
        return [
            'key' => substr($derivedBytes, 0, $keyLen),
            'iv'  => substr($derivedBytes, $keyLen, $ivLen)
        ];
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/home');
    }
}

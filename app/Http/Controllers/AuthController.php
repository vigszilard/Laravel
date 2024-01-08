<?php

namespace App\Http\Controllers;

use App\Models\Journalist;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function showLoginForm(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::user();

            if ($request->filled('article_id')) {
                return redirect()->route('article', ['id' => $request->input('article_id')]);
            } elseif ($user->role_id == 1) {
                return redirect()->route('index');
            } else {
                return redirect()->route('dashboard');
            }
        }

        return redirect()->route('login')->with('error', 'Login failed. Please check your credentials and try again.');
    }

    public function showRegisterForm(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:journalists,email',
            'password' => 'required|min:4',
            'firstname' => 'required',
            'lastname' => 'required',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('register')->with('error', 'Register failed. User already exists.');
        }

        $journalist = Journalist::create([
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'role_id' => $request->input('role_id'),
        ]);

        $result = Auth::guard('web')->attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);

        if($result) {
            $user = Auth::user();

            if ($request->filled('article_id')) {
                return redirect()->route('article', ['id' => $request->input('article_id')]);
            } elseif ($user->role_id == 1) {
                return redirect()->route('index');
            } else {
                return redirect()->route('dashboard');
            }
        }
        return redirect()->route('register')->with('error', 'Login failed. Please try again.');
    }

    public function logout(): RedirectResponse
    {
        Auth::guard('web')->logout();

        return redirect()->route('index');
    }
}


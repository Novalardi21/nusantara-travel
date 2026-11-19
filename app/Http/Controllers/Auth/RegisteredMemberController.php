<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterMemberRequest;
use App\Models\Member;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegisteredMemberController extends Controller
{
    public function store(RegisterMemberRequest $request): RedirectResponse
    {
        $attributes = $request->safe()->only(['nama', 'alamat', 'telepon', 'jen_kelamin', 'username', 'password']);
        $attributes['password'] = Hash::make($attributes['password']);

        try {
            $member = Member::create($attributes);
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages(['username' => 'Username sudah digunakan.']);
        }

        Auth::guard('member')->login($member);
        $request->session()->regenerate();

        return redirect()->route('booking');
    }
}

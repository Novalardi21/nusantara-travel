<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class MemberProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('pages.member.profile', ['member' => $request->user('member')]);
    }

    public function update(Request $request): RedirectResponse
    {
        $member = $request->user('member');
        $member->update($request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'telepon' => ['required', 'string', 'regex:/^\+?[0-9]{8,19}$/D', 'max:20'],
            'alamat' => ['required', 'string', 'max:100'],
            'jen_kelamin' => ['required', Rule::in(['L', 'P'])],
        ]));

        return back()->with('status', 'Profil berhasil diperbarui.');
    }

    public function password(Request $request): RedirectResponse
    {
        $input = $request->validate([
            'current_password' => ['required', 'string', 'current_password:member'],
            'password' => ['required', 'string', 'confirmed', Password::min(8), 'regex:/\A[^\x00]{8,72}\z/'],
        ]);

        $request->user('member')->update(['password' => Hash::make($input['password'])]);
        $request->session()->regenerate();

        return back()->with('status', 'Kata sandi berhasil diperbarui.');
    }
}

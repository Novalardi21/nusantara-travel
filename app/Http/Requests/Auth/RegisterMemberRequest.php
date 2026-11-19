<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:100'],
            'alamat' => ['required', 'string', 'max:100'],
            'telepon' => ['required', 'string', 'regex:/^\+?[0-9]{8,19}$/', 'max:20'],
            'jen_kelamin' => ['required', Rule::in(['L', 'P'])],
            'username' => ['bail', 'required', 'string', 'min:4', 'max:20', 'regex:/^[A-Za-z0-9_.-]+$/D', Rule::unique('member', 'username'), Rule::unique('operator_loket', 'user')],
            'password' => ['required', 'string', 'confirmed', 'max:72', Password::min(8), function (string $attribute, string $value, \Closure $fail): void {
                if (strlen($value) > 72 || str_contains($value, "\0")) {
                    $fail('Kata sandi harus dapat disimpan dengan aman dan maksimal 72 byte.');
                }
            }],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'username.min' => 'Username minimal 4 karakter.',
            'username.max' => 'Username maksimal 20 karakter.',
            'username.regex' => 'Username hanya boleh berisi huruf, angka, titik, tanda hubung, dan garis bawah.',
            'password.confirmed' => 'Ulangi kata sandi dengan nilai yang sama.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'telepon.regex' => 'Masukkan nomor telepon yang valid, misalnya 081234567890.',
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return ['nama' => 'Nama lengkap', 'alamat' => 'Alamat', 'telepon' => 'Nomor telepon', 'jen_kelamin' => 'Jenis kelamin', 'username' => 'Username', 'password' => 'Kata sandi'];
    }
}

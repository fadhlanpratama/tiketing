<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;
use App\Models\Users;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = Users::where('email', $request->email)->first();

        if ($user && $user->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda belum disetujui oleh Admin. Fitur lupa password belum bisa digunakan.',
            ], 403);
        }

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => 'Link reset password telah dikirim ke email Anda.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => match ($status) {
                Password::RESET_THROTTLED => 'Anda baru saja meminta reset password. Silakan tunggu beberapa saat sebelum mencoba lagi.',
                Password::INVALID_USER    => 'Email tidak terdaftar dalam sistem kami.',
                default => 'Gagal mengirim link reset. Silakan coba lagi.',
            },
        ], 422);
    }

    public function showResetForm(Request $request, string $token)
    {
        $email = $request->query('email');

        if (!$this->isTokenValid($email, $token)) {
            return view('auth.reset-password-expired', [
                'email' => $email,
            ]);
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    protected function isTokenValid(?string $email, string $token): bool
    {
        if (!$email) {
            return false;
        }

        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$record) {
            return false;
        }

        $expireMinutes = config('auth.passwords.users.expire');
        $createdAt = Carbon::parse($record->created_at);

        if ($createdAt->addMinutes($expireMinutes)->isPast()) {
            return false;
        }

        return Hash::check($token, $record->token);
    }

    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::min(8)->letters()->numbers()->mixedCase()],
        ], [
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = Users::where('email', $request->email)->first();

        if ($user && $user->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda belum disetujui oleh Admin. Tidak dapat mereset password.',
            ], 403);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => $password,
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'success' => true,
                'message' => 'Password berhasil direset. Silakan login dengan password baru.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => match ($status) {
                Password::INVALID_TOKEN => 'Link reset password sudah kedaluwarsa.',
                Password::INVALID_USER  => 'Email tidak ditemukan dalam sistem kami.',
                default => 'Gagal mereset password. Silakan coba lagi.',
            },
        ], 422);
    }
}
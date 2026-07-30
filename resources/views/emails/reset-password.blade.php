<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password</title>
</head>
<body style="margin:0; padding:0; background-color:#f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f1f5f9; padding: 40px 16px;">
        <tr>
            <td align="center">

                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width: 480px; background-color:#ffffff; border-radius: 20px; overflow:hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">

                    <!-- Header -->
                    <tr>
                        <td style="background-color:#0a2540; padding: 28px 32px;" align="center">
                            <span style="color:#ffffff; font-size:16px; font-weight:700; letter-spacing:0.3px;">
                                Sistem Tiketing ESDM
                            </span>
                        </td>
                    </tr>

                    <!-- Icon -->
                    <tr>
                        <td style="padding: 36px 32px 0 32px;" align="center">
                            <table cellpadding="0" cellspacing="0" style="width:56px; height:56px; background-color:#eff6ff; border-radius:16px;">
                                <tr>
                                    <td align="center" valign="middle" style="font-size:24px;">
                                        🔐
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 20px 32px 0 32px;" align="center">
                            <h1 style="margin:0; font-size:20px; color:#0f172a; font-weight:800;">
                                Reset Password
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 12px 32px 0 32px;">
                            <p style="margin:0 0 8px 0; font-size:14px; color:#334155; line-height:1.6;">
                                Halo, <strong>{{ $nama }}</strong>!
                            </p>
                            <p style="margin:0; font-size:14px; color:#64748b; line-height:1.6;">
                                Kami menerima permintaan untuk mereset password akun Anda. Klik tombol di bawah ini untuk membuat password baru.
                            </p>
                        </td>
                    </tr>

                    <!-- Button -->
                    <tr>
                        <td style="padding: 28px 32px 0 32px;" align="center">
                            <table cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="border-radius: 12px; background-color:#0a2540;">
                                        <a href="{{ $url }}" target="_blank" style="display:inline-block; padding: 14px 32px; font-size:14px; font-weight:700; color:#ffffff; text-decoration:none; border-radius:12px;">
                                            Reset Password
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Expire note -->
                    <tr>
                        <td style="padding: 24px 32px 0 32px;" align="center">
                            <table cellpadding="0" cellspacing="0" style="background-color:#fef9c3; border-radius:10px; width:100%;">
                                <tr>
                                    <td style="padding: 10px 16px; font-size:12px; color:#854d0e; text-align:center;">
                                        ⏱️ Link ini akan kedaluwarsa dalam <strong>{{ $expire }} menit</strong>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Fallback link -->
                    <tr>
                        <td style="padding: 24px 32px 0 32px;">
                            <p style="margin:0; font-size:12px; color:#94a3b8; line-height:1.6;">
                                Jika tombol di atas tidak berfungsi, salin dan tempel link berikut ke browser Anda:
                            </p>
                            <p style="margin:6px 0 0 0; font-size:12px; word-break:break-all;">
                                <a href="{{ $url }}" style="color:#2563eb; text-decoration:none;">{{ $url }}</a>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 20px 32px 0 32px;">
                            <p style="margin:0; font-size:12px; color:#94a3b8; line-height:1.6;">
                                Jika Anda tidak merasa meminta ini, abaikan saja email ini. Password Anda tidak akan berubah.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 32px; background-color:#f8fafc; margin-top: 24px;" align="center">
                            <p style="margin:0; font-size:11px; color:#94a3b8;">
                                &copy; {{ date('Y') }} Sistem Tiketing ESDM.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
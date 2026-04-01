<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إشعار إزالة الحساب</title>
</head>
<body style="margin:0;padding:0;background-color:#f0f4f8;font-family:'Segoe UI',Arial,sans-serif;direction:rtl;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f0f4f8;padding:40px 16px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">
                    <tr>
                        <td align="center" style="background:#ffffff;padding:36px 40px 20px;">
                            <img src="{{ config('app.url') }}/assets/logo.png" alt="{{ config('app.name') }}" width="180" style="display:block;height:auto;max-width:180px;">
                        </td>
                    </tr>
                    <tr>
                        <td style="background:linear-gradient(90deg,#c9a84c,#f0c96a,#c9a84c);height:4px;font-size:0;line-height:0;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="padding:40px 48px 32px;color:#1e293b;font-size:15px;line-height:1.9;">
                            <p style="margin:0 0 18px;font-size:18px;font-weight:bold;color:#0f2557;">مرحباً، {{ $member->name }}</p>
                            <p style="margin:0 0 16px;">
                                نود إعلامك بأنه تم إزالة حسابك من مكتب
                                <strong style="color:#0f2557;">{{ $firmName }}</strong>
                                بواسطة المالك <strong style="color:#0f2557;">{{ $removedByName }}</strong>.
                            </p>
                            <p style="margin:0 0 16px;">
                                لن تتمكن بعد الآن من الوصول إلى بيانات المكتب أو تسجيل الدخول باستخدام هذا الحساب ضمن هذا المكتب.
                            </p>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;margin:24px 0;">
                                <tr>
                                    <td style="padding:16px 20px;font-size:13px;color:#475569;">
                                        <strong>البريد الإلكتروني:</strong> {{ $member->email }}<br>
                                        <strong>تاريخ الإزالة:</strong> {{ now()->timezone(config('app.display_timezone', config('app.timezone')))->format('Y-m-d H:i') }}
                                    </td>
                                </tr>
                            </table>
                            <p style="margin:0;font-size:13px;color:#64748b;">
                                إذا كنت تعتقد أن هذه الإزالة تمت بالخطأ، يرجى التواصل مباشرة مع مالك المكتب.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#f8faff;padding:20px 48px;border-top:1px solid #e2e8f0;text-align:center;font-size:12px;color:#94a3b8;line-height:1.7;">
                            &copy; {{ date('Y') }} <strong style="color:#0f2557;">{{ config('app.name') }}</strong> &mdash; جميع الحقوق محفوظة
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
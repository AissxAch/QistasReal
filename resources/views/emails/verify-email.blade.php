<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تأكيد البريد الإلكتروني</title>
</head>
<body style="margin:0;padding:0;background-color:#f0f4f8;font-family:'Segoe UI',Arial,sans-serif;direction:rtl;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f0f4f8;padding:40px 16px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">
                    <tr>
                        <td align="center" style="background:#ffffff;padding:36px 40px 28px;">
                            <img src="https://raw.githubusercontent.com/AissxAch/assets/refs/heads/main/fulllogo.png"
                                 alt="{{ config('app.name') }}"
                                 width="180"
                                 style="display:block;height:auto;max-width:180px;">
                        </td>
                    </tr>

                    <tr>
                        <td style="background:linear-gradient(90deg,#c9a84c,#f0c96a,#c9a84c);height:4px;font-size:0;line-height:0;">&nbsp;</td>
                    </tr>

                    <tr>
                        <td style="padding:40px 48px 32px;color:#1e293b;font-size:15px;line-height:1.9;">
                            <p style="margin:0 0 20px;font-size:18px;font-weight:bold;color:#0f2557;">
                                مرحباً، {{ $user->name }} 👋
                            </p>

                            <p style="margin:0 0 16px;">
                                شكرًا لانضمامك إلى منصة <strong style="color:#0f2557;">قسطاس</strong>.
                                لإكمال تفعيل حسابك، يرجى تأكيد عنوان بريدك الإلكتروني بالضغط على الزر أدناه.
                            </p>

                            <p style="margin:0 0 16px;color:#7c2d12;background:#fff7ed;border:1px solid #fdba74;border-radius:10px;padding:14px 16px;">
                                بعد تأكيد البريد الإلكتروني ستتمكن من متابعة إعداد مكتبك وطلب الاشتراك الأساسي إذا لم يكن لديك اشتراك بعد.
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f8faff;border-radius:8px;border:1px solid #dbeafe;margin:24px 0;">
                                <tr>
                                    <td style="padding:16px 20px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td style="color:#64748b;font-size:13px;padding:4px 0;">الاسم</td>
                                                <td align="left" style="color:#0f2557;font-weight:bold;font-size:13px;padding:4px 0;">{{ $user->name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color:#64748b;font-size:13px;padding:4px 0;">البريد الإلكتروني</td>
                                                <td align="left" style="color:#0f2557;font-weight:bold;font-size:13px;padding:4px 0;">{{ $user->email }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color:#64748b;font-size:13px;padding:4px 0;">نوع الحساب</td>
                                                <td align="left" style="color:#0f2557;font-weight:bold;font-size:13px;padding:4px 0;">تسجيل فردي / مالك مكتب</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 28px;">
                                اضغط على الزر التالي لتأكيد بريدك الإلكتروني:
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center" style="padding:0 0 28px;">
                                        <a href="{{ $verificationUrl }}"
                                           style="display:inline-block;background:linear-gradient(135deg,#0f2557,#1a3a8f);color:#ffffff;text-decoration:none;padding:15px 44px;border-radius:8px;font-size:15px;font-weight:bold;letter-spacing:0.3px;box-shadow:0 4px 12px rgba(15,37,87,0.35);">
                                            تأكيد البريد الإلكتروني &larr;
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#fffbeb;border-radius:8px;border:1px solid #fde68a;margin-bottom:24px;">
                                <tr>
                                    <td style="padding:14px 18px;font-size:13px;color:#92400e;line-height:1.7;">
                                        إذا لم تقم بإنشاء هذا الحساب، يمكنك تجاهل هذا البريد بأمان ولن يتم اتخاذ أي إجراء على حسابك.
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0;font-size:13px;color:#94a3b8;">
                                في حال لم يعمل الزر، يمكنك نسخ الرابط التالي ولصقه في المتصفح:<br>
                                <span style="word-break:break-all; color:#1a3a8f;">{{ $verificationUrl }}</span>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="background:#f8faff;padding:20px 48px;border-top:1px solid #e2e8f0;text-align:center;font-size:12px;color:#94a3b8;line-height:1.7;">
                            &copy; {{ date('Y') }} <strong style="color:#0f2557;">{{ config('app.name') }}</strong> &mdash; جميع الحقوق محفوظة<br>
                            منصة متكاملة لإدارة القضايا القانونية
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دعوة للانضمام إلى {{ $firmName }}</title>
</head>
<body style="margin:0;padding:0;background-color:#f0f4f8;font-family:'Segoe UI',Arial,sans-serif;direction:rtl;">

    {{-- Outer wrapper --}}
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f0f4f8;padding:40px 16px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">

                    {{-- Header: logo only, no color banner --}}
                    <tr>
                        <td align="center" style="background:#ffffff;padding:36px 40px 28px;">
                            <img src="https://i.ibb.co/7JmDCrZy/Gemini-Generated-Image-orslaforslaforsl.png"
                                 alt="{{ config('app.name') }}"
                                 width="180"
                                 style="display:block;height:auto;max-width:180px;">
                        </td>
                    </tr>

                    {{-- Accent bar --}}
                    <tr>
                        <td style="background:linear-gradient(90deg,#c9a84c,#f0c96a,#c9a84c);height:4px;font-size:0;line-height:0;">&nbsp;</td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:40px 48px 32px;color:#1e293b;font-size:15px;line-height:1.9;">

                            <p style="margin:0 0 20px;font-size:18px;font-weight:bold;color:#0f2557;">
                                مرحباً، {{ $member->name }} 👋
                            </p>

                            <p style="margin:0 0 16px;">
                                يسعدنا إخبارك بأنه تمت إضافتك إلى مكتب
                                <strong style="color:#0f2557;">{{ $firmName }}</strong>
                                على منصة <strong style="color:#0f2557;">قسطاس</strong> لإدارة القضايا القانونية.
                            </p>

                            <p style="margin:0 0 16px;color:#7c2d12;background:#fff7ed;border:1px solid #fdba74;border-radius:10px;padding:14px 16px;">
                                يجب تفعيل الحساب خلال <strong>24 ساعة</strong> من وقت استلام هذه الرسالة، وإلا سيتم حذف الدعوة والحساب تلقائياً.
                            </p>

                            {{-- Info card --}}
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f8faff;border-radius:8px;border:1px solid #dbeafe;margin:24px 0;">
                                <tr>
                                    <td style="padding:16px 20px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td style="color:#64748b;font-size:13px;padding:4px 0;">البريد الإلكتروني</td>
                                                <td align="left" style="color:#0f2557;font-weight:bold;font-size:13px;padding:4px 0;">{{ $member->email }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color:#64748b;font-size:13px;padding:4px 0;">الدور الوظيفي</td>
                                                <td align="left" style="color:#0f2557;font-weight:bold;font-size:13px;padding:4px 0;">{{ $member->role }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color:#64748b;font-size:13px;padding:4px 0;">المكتب</td>
                                                <td align="left" style="color:#0f2557;font-weight:bold;font-size:13px;padding:4px 0;">{{ $firmName }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 28px;">
                                لبدء استخدام حسابك، يرجى تعيين كلمة مرور جديدة بالضغط على الزر أدناه:
                            </p>

                            {{-- CTA button --}}
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center" style="padding:0 0 28px;">
                                        <a href="{{ $setPasswordUrl }}"
                                           style="display:inline-block;background:linear-gradient(135deg,#0f2557,#1a3a8f);color:#ffffff;text-decoration:none;padding:15px 44px;border-radius:8px;font-size:15px;font-weight:bold;letter-spacing:0.3px;box-shadow:0 4px 12px rgba(15,37,87,0.35);">
                                            تعيين كلمة المرور &larr;
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            {{-- Warning note --}}
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#fffbeb;border-radius:8px;border:1px solid #fde68a;margin-bottom:24px;">
                                <tr>
                                    <td style="padding:14px 18px;font-size:13px;color:#92400e;line-height:1.7;">
                                        ⏳ رابط تعيين كلمة المرور مرتبط بدعوة التفعيل الحالية.
                                        إذا انتهت صلاحيته قبل التفعيل، يمكنك طلب رابط جديد من صفحة
                                        <a href="{{ route('password.request') }}" style="color:#1a3a8f;font-weight:bold;">نسيت كلمة المرور</a>.
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0;font-size:13px;color:#94a3b8;">
                                إذا لم تكن تتوقع هذه الدعوة، يمكنك تجاهل هذا البريد بأمان.
                            </p>

                        </td>
                    </tr>

                    {{-- Footer --}}
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



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset your password</title>
<style>
  @media only screen and (max-width: 600px) {
    .card { width: 100% !important; }
    .cell { padding: 28px 22px !important; }
  }
  @media (prefers-reduced-motion: no-preference) {
    .card { animation: enzoFadeUp .6s cubic-bezier(.2,.8,.2,1) both; }
    .brand-band { background-size: 220% 220%; animation: enzoShimmer 7s ease infinite; }
  }
  @keyframes enzoFadeUp { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
  @keyframes enzoShimmer { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
</style>
</head>
<body style="margin:0; padding:0; background-color:#eef2fb;">
<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color:#eef2fb;">
  <tr>
    <td align="center" style="padding:32px 12px;">
      <table class="card" width="600" cellpadding="0" cellspacing="0" role="presentation" style="width:600px; max-width:600px; border-radius:16px; overflow:hidden; box-shadow:0 14px 38px rgba(11,31,77,0.14); background-color:#ffffff;">
        <tr>
          <td class="brand-band" style="background:linear-gradient(135deg,#0b1f4d 0%,#3b5bdb 55%,#3B82F6 120%); padding:30px 40px; text-align:center;">
            <table align="center" cellpadding="0" cellspacing="0" role="presentation" style="margin:0 auto;">
              <tr>
                <td style="width:46px;height:46px;border-radius:13px;background:rgba(255,255,255,0.18);color:#fff;font-weight:800;font-size:22px;text-align:center;vertical-align:middle;font-family:Arial;">E</td>
                <td style="color:#fff;font-weight:800;font-size:24px;letter-spacing:.5px;padding-left:12px;font-family:-apple-system,Segoe UI,Roboto,Arial;">EnzoBank</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="cell" style="padding:40px;">
            <h1 style="margin:0 0 14px; color:#0b1f4d; font-size:22px; font-weight:800;">Reset your password</h1>
            <p style="margin:0 0 18px; color:#475569; font-size:15px; line-height:1.6;">Hello,</p>
            <p style="margin:0 0 22px; color:#475569; font-size:15px; line-height:1.6;">We received a request to reset the password for your admin account. Click the button below to choose a new password. This link will expire soon for your security.</p>
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
              <tr>
                <td align="center">
                  <a href="{{ $reset_url ?? 'javascript:void(0)' }}" style="background:linear-gradient(135deg,#3b5bdb,#5f3dc4); color:#ffffff; text-decoration:none; font-weight:700; font-size:15px; padding:14px 32px; border-radius:10px; display:inline-block; box-shadow:0 10px 22px rgba(59,91,219,0.35);">Reset Password</a>
                </td>
              </tr>
            </table>
            <p style="margin:22px 0 0; color:#94a3b8; font-size:13px; line-height:1.6;">If you did not request this change, you can safely ignore this email. Your password will remain unchanged.</p>
            <p style="margin:18px 0 0; color:#94a3b8; font-size:12px; line-height:1.6;">Thanks,<br>The EnzoBank Security Team</p>
          </td>
        </tr>
        <tr>
          <td align="center" style="padding:22px 10px 8px;">
            <p style="margin:0 0 8px; font-size:13px; color:#64748b; font-weight:600;">EnzoBank &middot; Secure digital banking</p>
            <p style="margin:0; font-size:11px; color:#aab2c2; line-height:1.6;">&copy; {{ date('Y') }} EnzoBank. All rights reserved.</p>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
<x-mail::layout>
{{-- Header / brand band --}}
<x-slot:header>
<div class="brand-band" style="background:linear-gradient(135deg,#0b1f4d 0%,#3b5bdb 55%,#3B82F6 120%); padding:30px 40px; text-align:center;">
  <table align="center" cellpadding="0" cellspacing="0" role="presentation" style="margin:0 auto;">
    <tr>
      <td style="color:#ffffff;font-weight:800;font-size:24px;letter-spacing:.5px;font-family:-apple-system,Segoe UI,Roboto,Arial,sans-serif;">Enzo<span style="color:#3B82F6;">Bank</span></td>
    </tr>
  </table>
  <div style="margin-top:4px;font-size:13px;color:rgba(255,255,255,0.65);font-family:Arial,Helvetica,sans-serif;letter-spacing:1px;">SECURE DIGITAL BANKING</div>
</div>
</x-slot:header>

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
{{ $subcopy }}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<table align="center" width="600" cellpadding="0" cellspacing="0" role="presentation" style="width:600px; max-width:600px;">
  <tr>
    <td align="center" style="padding:22px 10px 8px;">
      <p style="margin:0 0 8px; font-size:13px; color:#64748b; font-weight:600;">EnzoBank &middot; Secure digital banking</p>
      <p style="margin:0 0 10px; font-size:12px; color:#94a3b8;">
        <strong style="color:#64748b;">Need help?</strong><br>
        Email: <a href="mailto:support@enzobank.org" style="color:#3b5bdb; text-decoration:underline;">support@enzobank.org</a> &nbsp;·&nbsp; WhatsApp: <a href="https://wa.me/447464483316" style="color:#3b5bdb; text-decoration:underline;">+44 7464 483316</a>
      </p>
      <p style="margin:0; font-size:11px; color:#aab2c2; line-height:1.6;">
        &copy; {{ date('Y') }} EnzoBank. All rights reserved.<br>
        You are receiving this email because you hold an EnzoBank account. If you believe this was sent in error, contact support@enzobank.org.
      </p>
    </td>
  </tr>
</table>
</x-slot:footer>
</x-mail::layout>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Biometric — EnzoBank</title>
<style>html,body{background:#0A0E1A;margin:0;height:100%;}*{margin:0;padding:0;box-sizing:border-box;}</style>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
html, body { height:100%; width:100%; overflow:hidden; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif; }

.bio-screen {
  position:fixed; inset:0;
  background: linear-gradient(160deg, #0A0E1A 0%, #141B2D 50%, #0A0E1A 100%);
  display:flex; flex-direction:column; align-items:center; justify-content:center;
}

.fingerprint-container {
  position:relative; margin-bottom:40px;
  animation: fadeUp 0.6s ease-out;
}
.fingerprint-ring {
  width:140px; height:140px; border-radius:50%;
  background:rgba(59,130,246,0.08);
  display:flex; align-items:center; justify-content:center;
  position:relative;
  cursor:pointer;
  -webkit-tap-highlight-color:transparent;
  transition: all 0.3s ease;
}
.fingerprint-ring::before {
  content:''; position:absolute; inset:-4px;
  border-radius:50%;
  border:2px solid rgba(59,130,246,0.15);
  animation: ringPulse 2s ease-in-out infinite;
}
@keyframes ringPulse { 0%,100% { transform:scale(1); opacity:0.5; } 50% { transform:scale(1.08); opacity:0; } }
.fingerprint-ring.scanning::before { border-color:#3B82F6; animation: ringScan 0.8s ease-in-out infinite; }
@keyframes ringScan { 0%,100% { transform:scale(1); opacity:0.6; } 50% { transform:scale(1.12); opacity:0; } }
.fingerprint-ring.success { background:rgba(59,130,246,0.12); }
.fingerprint-ring.success::before { border-color:#3B82F6; animation:none; transform:scale(1.05); opacity:0.3; }
.fingerprint-ring.fail { background:rgba(239,68,68,0.12); animation:shake 0.4s ease; }
.fingerprint-ring.fail::before { border-color:#EF4444; animation:none; transform:scale(1.05); opacity:0.3; }
@keyframes shake { 0%,100% { transform:translateX(0); } 25% { transform:translateX(-8px); } 50% { transform:translateX(8px); } 75% { transform:translateX(-4px); } }

.fingerprint-ring svg { width:56px; height:56px; stroke:#3B82F6; fill:none; stroke-width:1.5; position:relative; z-index:1; }
.fingerprint-ring.success svg { stroke:#3B82F6; }
.fingerprint-ring.fail svg { stroke:#EF4444; }
.fingerprint-ring.scanning svg { stroke:#3B82F6; animation: scanGlow 0.8s ease-in-out infinite; }
@keyframes scanGlow { 0%,100% { opacity:0.6; } 50% { opacity:1; } }

.bio-title {
  font-size:20px; font-weight:700; color:#F1F5F9; margin-bottom:6px;
  animation: fadeUp 0.6s ease-out 0.15s both;
}
.bio-sub {
  font-size:14px; color:#64748B; text-align:center; max-width:280px;
  animation: fadeUp 0.6s ease-out 0.25s both;
}
@keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }

.bio-status {
  margin-top:20px; font-size:13px; font-weight:600;
  transition: all 0.3s ease; min-height:20px;
  animation: fadeUp 0.6s ease-out 0.3s both;
}
.bio-status.idle { color:#64748B; }
.bio-status.scanning { color:#3B82F6; }
.bio-status.success { color:#3B82F6; }
.bio-status.fail { color:#EF4444; }

.bio-footer {
  position:absolute; bottom:48px; left:0; right:0; text-align:center;
  animation: fadeUp 0.6s ease-out 0.4s both;
}
.bio-footer a {
  color:#3B82F6; text-decoration:none; font-size:13px; font-weight:600;
  display:inline-flex; align-items:center; gap:6px;
  transition:color 0.2s;
}
.bio-footer a:hover { color:#60A5FA; }
.bio-footer a svg { width:16px; height:16px; stroke:currentColor; fill:none; stroke-width:2; }

.bio-retry-btn {
  display:none; margin-top:16px;
  padding:12px 28px; border-radius:10px;
  background:rgba(59,130,246,0.12); border:1.5px solid rgba(59,130,246,0.2);
  color:#3B82F6; font-size:14px; font-weight:700; cursor:pointer;
  transition: all 0.2s; -webkit-tap-highlight-color:transparent;
  outline:none; font-family:inherit;
  width:fit-content; margin-left:auto; margin-right:auto;
}
.bio-retry-btn:hover { background:rgba(59,130,246,0.2); border-color:rgba(59,130,246,0.3); }
.bio-retry-btn:active { transform:scale(0.95); }
.bio-retry-btn.visible { display:inline-flex; align-items:center; gap:8px; animation: fadeUp 0.3s ease-out both; }
.bio-retry-btn svg { width:16px; height:16px; stroke:currentColor; fill:none; stroke-width:2; }

.loading-dots { display:inline-flex; gap:4px; margin-left:4px; }
.loading-dots span {
  width:5px; height:5px; border-radius:50%; background:#3B82F6;
  animation: dotBounce 0.6s ease-in-out infinite;
}
.loading-dots span:nth-child(2) { animation-delay:0.15s; }
.loading-dots span:nth-child(3) { animation-delay:0.3s; }
@keyframes dotBounce { 0%,100% { transform:translateY(0); opacity:0.4; } 50% { transform:translateY(-6px); opacity:1; } }

.toast-msg {
  position:fixed; top:24px; left:50%; transform:translateX(-50%) translateY(-100px);
  padding:14px 24px; border-radius:12px; font-size:14px; font-weight:600;
  box-shadow:0 8px 32px rgba(0,0,0,0.25); transition:transform 0.4s cubic-bezier(0.34,1.56,0.64,1);
  z-index:100; max-width:90%; text-align:center;
}
.toast-msg.show { transform:translateX(-50%) translateY(0); }
.toast-msg.error { background:#EF4444; color:#fff; }
.toast-msg.success { background:#2563EB; color:#fff; }
</style>
</head>
<body>
<div class="page-loader" id="pageLoader">
  <div class="loader-ring">
    <svg class="loader-ring-svg" width="72" height="72" viewBox="0 0 72 72">
      <defs><linearGradient id="plGrad" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#3B82F6"/><stop offset="50%" stop-color="#6366F1"/><stop offset="100%" stop-color="#8B5CF6"/>
      </linearGradient></defs>
      <circle class="loader-track" cx="36" cy="36" r="30"/>
      <circle class="loader-arc" cx="36" cy="36" r="30"/>
    </svg>
    <div class="loader-dot-ring"></div>
  </div>
  <div class="loader-text">
    <span>Loading</span>
    <span class="loader-dots"><span></span><span></span><span></span></span>
  </div>
  <div class="loader-shimmer"></div>
</div>
<style>
.page-loader { position:fixed; inset:0; z-index:99999; background:rgba(10,14,26,0.96); display:flex; flex-direction:column; align-items:center; justify-content:center; transition:opacity 0.5s ease, visibility 0.5s ease; }
.page-loader.loaded { opacity:0; visibility:hidden; pointer-events:none; }
.page-loader .loader-ring { position:relative; width:72px; height:72px; margin-bottom:32px; }
.page-loader .loader-ring-svg { position:absolute; inset:0; transform:rotate(-90deg); }
.page-loader .loader-track { fill:none; stroke:rgba(59,130,246,0.12); stroke-width:3; }
.page-loader .loader-arc { fill:none; stroke:url(#plGrad); stroke-width:3; stroke-linecap:round; stroke-dasharray:170; stroke-dashoffset:340; animation: plSpin 1.6s cubic-bezier(0.4,0,0.2,1) infinite; }
@keyframes plSpin { 0% { stroke-dashoffset:340; transform:rotate(0deg); } 50% { stroke-dashoffset:0; transform:rotate(270deg); } 100% { stroke-dashoffset:340; transform:rotate(360deg); } }
.page-loader .loader-dot-ring { position:absolute; inset:8px; border-radius:50%; display:flex; align-items:center; justify-content:center; }
.page-loader .loader-dot-ring::before { content:''; width:8px; height:8px; border-radius:50%; background:#3B82F6; animation: plPulse 1.6s ease-in-out infinite; box-shadow:0 0 12px rgba(59,130,246,0.4); }
@keyframes plPulse { 0%,100% { transform:scale(0.8); opacity:0.6; } 50% { transform:scale(1.2); opacity:1; } }
.page-loader .loader-text { font-size:15px; font-weight:600; color:#94A3B8; letter-spacing:0.3px; display:flex; align-items:center; gap:3px; }
.page-loader .loader-dots { display:flex; gap:3px; }
.page-loader .loader-dots span { width:4px; height:4px; border-radius:50%; background:#3B82F6; animation: plDotBounce 1.2s ease-in-out infinite; }
.page-loader .loader-dots span:nth-child(2) { animation-delay:0.2s; }
.page-loader .loader-dots span:nth-child(3) { animation-delay:0.4s; }
@keyframes plDotBounce { 0%,80%,100% { transform:scale(0.4); opacity:0.3; } 40% { transform:scale(1); opacity:1; } }
.page-loader .loader-shimmer { width:80px; height:2px; border-radius:1px; margin-top:14px; background:linear-gradient(90deg, transparent 0%, rgba(59,130,246,0.3) 50%, transparent 100%); background-size:200% 100%; animation: plShimmer 1.5s ease-in-out infinite; }
@keyframes plShimmer { 0% { background-position:-200% 0; } 100% { background-position:200% 0; } }
</style>
<script>window.addEventListener('load',function(){setTimeout(function(){document.getElementById('pageLoader').classList.add('loaded')},300)});</script>
<div class="bio-screen">
  <div class="fingerprint-container">
    <div class="fingerprint-ring" id="fingerprintRing">
      <svg viewBox="0 0 56 56">
        <path d="M28 12C19.163 12 12 19.163 12 28v6"/>
        <path d="M44 28c0-8.837-7.163-16-16-16"/>
        <path d="M16 42c0-3.314 2.686-6 6-6h2"/>
        <path d="M34 44c0-3.314-2.686-6-6-6h-2"/>
        <path d="M12 38c0-2.21 1.79-4 4-4h2"/>
        <path d="M44 38c0-2.21-1.79-4-4-4h-2"/>
        <path d="M28 8a20 20 0 0 0-20 20v4"/>
        <path d="M48 28a20 20 0 0 0-20-20"/>
        <circle cx="28" cy="30" r="3"/>
      </svg>
    </div>
  </div>

  <div class="bio-title">Biometric Authentication</div>
  <div class="bio-sub">Touch the fingerprint sensor to<br>securely access your account</div>

  <div class="bio-status idle" id="bioStatus">
    <span>Touch to authenticate</span>
  </div>

  <div class="bio-footer">
    <a href="/app/pin">
      <svg viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 6V4a6 6 0 1 1 12 0v2"/></svg>
      Use PIN Instead
    </a>
    <button class="bio-retry-btn" id="retryBtn">
      <svg viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
      Retry Scan
    </button>
  </div>
</div>

<div class="toast-msg" id="toastMsg"></div>

<script>
(function() {
  const ring = document.getElementById('fingerprintRing');
  const status = document.getElementById('bioStatus');
  const toast = document.getElementById('toastMsg');
  const retryBtn = document.getElementById('retryBtn');
  let isProcessing = false;

  function showToast(msg, type) {
    toast.className = 'toast-msg ' + (type || 'error');
    toast.textContent = msg;
    toast.classList.add('show');
    clearTimeout(toast._timer);
    toast._timer = setTimeout(() => toast.classList.remove('show'), 3000);
  }

  function setState(state) {
    ring.classList.remove('scanning', 'success', 'fail');
    status.classList.remove('idle', 'scanning', 'success', 'fail');
    if (state === 'scanning') {
      ring.classList.add('scanning');
      status.classList.add('scanning');
      status.innerHTML = 'Scanning<span class="loading-dots"><span></span><span></span><span></span></span>';
      retryBtn.classList.remove('visible');
    } else if (state === 'success') {
      ring.classList.add('success');
      status.classList.add('success');
      status.textContent = '✓ Authenticated';
    } else if (state === 'fail') {
      ring.classList.add('fail');
      status.classList.add('fail');
      status.textContent = '✗ Not recognized';
      retryBtn.classList.add('visible');
    } else {
      status.classList.add('idle');
      status.innerHTML = '<span>Touch to authenticate</span>';
      retryBtn.classList.remove('visible');
    }
  }

  // Check if user is authenticated via session
  async function checkAuth() {
    try {
      const r = await fetch('/app/pin/status', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      if (!r.ok) { window.location.href = '/app/login'; return; }
      const data = await r.json();
      if (!data.authenticated) { window.location.href = '/app/login'; }
    } catch(e) {
      window.location.href = '/app/login';
    }
  }
  checkAuth();

  async function authenticate() {
    if (isProcessing) return;
    isProcessing = true;
    showLoader('Authenticating');
    setState('scanning');

    try {
      let authenticated = false;
      let webauthnAttempted = false;

      // Try WebAuthn platform biometrics first
      if (window.PublicKeyCredential && typeof PublicKeyCredential.isUserVerifyingPlatformAuthenticatorAvailable === 'function') {
        const uvAvailable = await PublicKeyCredential.isUserVerifyingPlatformAuthenticatorAvailable();
        if (uvAvailable) {
          webauthnAttempted = true;
          try {
            const challenge = new Uint8Array(32);
            window.crypto.getRandomValues(challenge);

            const credential = await navigator.credentials.get({
              publicKey: {
                challenge: challenge,
                timeout: 30000,
                userVerification: 'required',
              }
            });

            if (credential) {
              authenticated = true;
            }
          } catch(e) {
            // User cancelled or the scan was not recognized — genuine failure
            authenticated = false;
          }
        }
      }

      // Simulated fallback ONLY when no platform authenticator is available
      if (!webauthnAttempted && !authenticated) {
        await new Promise(r => setTimeout(r, 1800));
        authenticated = true;
      }

      hideLoader();

      if (authenticated) {
        setState('success');
        await new Promise(r => setTimeout(r, 500));
        window.location.href = '/user/rise/home';
        return;
      }

      // Genuine failure — keep the retry button visible until the user acts
      setState('fail');
      showToast('Biometric scan failed. Try again or use PIN.', 'error');
    } catch(err) {
      hideLoader();
      setState('fail');
      showToast('Authentication error. Try again.', 'error');
    }

    isProcessing = false;
  }

  ring.addEventListener('click', authenticate);
  retryBtn.addEventListener('click', authenticate);

  // Auto-scan on page load (with small delay for animation)
  setTimeout(() => authenticate(), 600);
})();
document.getElementById('pageLoader').classList.add('loaded');
</script>
@include('partials.app-loader')
</body>
</html>

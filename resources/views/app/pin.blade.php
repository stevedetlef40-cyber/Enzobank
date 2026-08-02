<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Security PIN — EnzoBank</title>
<style>html,body{background:#0A0E1A;margin:0;height:100%;}*{margin:0;padding:0;box-sizing:border-box;}</style>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
html, body { height:100%; width:100%; overflow:hidden; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif; }

.pin-screen {
  position:fixed; inset:0;
  background: linear-gradient(160deg, #0A0E1A 0%, #141B2D 50%, #0A0E1A 100%);
  display:flex; flex-direction:column; align-items:center;
}
.pin-container {
  flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center;
  padding:24px; width:100%; max-width:400px; position:relative; z-index:1;
}
.user-pill {
  display:flex; align-items:center; gap:10px;
  background:rgba(30,41,59,0.6); border:1px solid rgba(148,163,184,0.08);
  padding:8px 16px 8px 8px; border-radius:99px; margin-bottom:32px;
  animation: fadeUp 0.5s ease-out;
}
.user-avatar {
  width:36px; height:36px; border-radius:50%;
  background: linear-gradient(135deg, #3B82F6, #2563EB);
  display:flex; align-items:center; justify-content:center;
  color:#fff; font-size:14px; font-weight:700;
}
.user-name { font-size:14px; font-weight:600; color:#F1F5F9; }
@keyframes fadeUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }

.pin-title { font-size:18px; font-weight:700; color:#F1F5F9; margin-bottom:6px; animation: fadeUp 0.5s ease-out 0.1s both; }
.pin-sub { font-size:13px; color:#64748B; margin-bottom:28px; animation: fadeUp 0.5s ease-out 0.15s both; }

.pin-dots {
  display:flex; gap:16px; margin-bottom:40px;
  animation: fadeUp 0.5s ease-out 0.2s both;
}
.pin-dot {
  width:16px; height:16px; border-radius:50%;
  background:rgba(148,163,184,0.12); border:2px solid rgba(148,163,184,0.15);
  transition: all 0.2s cubic-bezier(0.34,1.56,0.64,1);
}
.pin-dot.filled { background:#3B82F6; border-color:#3B82F6; transform:scale(1.1); box-shadow:0 0 12px rgba(59,130,246,0.3); }
.pin-dot.error { background:#EF4444; border-color:#EF4444; animation: shake 0.4s ease; }
@keyframes shake { 0%,100% { transform:translateX(0); } 25% { transform:translateX(-6px); } 50% { transform:translateX(6px); } 75% { transform:translateX(-4px); } }

.pin-pad {
  display:grid; grid-template-columns:repeat(3, 1fr); gap:12px;
  width:100%; max-width:280px;
  animation: fadeUp 0.5s ease-out 0.3s both;
}
.pin-key {
  padding:18px 0; border-radius:14px;
  background:rgba(30,41,59,0.5); border:1.5px solid rgba(148,163,184,0.08);
  color:#F1F5F9; font-size:24px; font-weight:600;
  cursor:pointer; text-align:center; user-select:none;
  transition: all 0.15s ease;
  -webkit-tap-highlight-color:transparent;
}
.pin-key:active { transform:scale(0.92); background:rgba(59,130,246,0.2); border-color:rgba(59,130,246,0.3); }
.pin-key.empty { background:transparent; border:none; cursor:default; pointer-events:none; }
.pin-key.back { font-size:20px; color:#94A3B8; }

.pin-footer {
  margin-top:32px; animation: fadeUp 0.5s ease-out 0.35s both;
}
.pin-footer a {
  color:#3B82F6; text-decoration:none; font-size:13px; font-weight:600;
  display:flex; align-items:center; justify-content:center; gap:6px;
  transition:color 0.2s;
}
.pin-footer a svg { width:16px; height:16px; stroke:currentColor; fill:none; stroke-width:2; }

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
<div class="pin-screen">
  <div class="pin-container">
    <div class="user-pill" id="userPill">
      <div class="user-avatar" id="userAvatar">U</div>
      <span class="user-name" id="userName">User</span>
    </div>

    <div class="pin-title" id="pinTitle">Enter PIN</div>
    <div class="pin-sub" id="pinSub">Enter your 4-digit security PIN</div>

    <div class="pin-dots" id="pinDots">
      <div class="pin-dot" data-i="0"></div>
      <div class="pin-dot" data-i="1"></div>
      <div class="pin-dot" data-i="2"></div>
      <div class="pin-dot" data-i="3"></div>
    </div>

    <div class="pin-pad" id="pinPad">
      <div class="pin-key" data-value="1">1</div>
      <div class="pin-key" data-value="2">2</div>
      <div class="pin-key" data-value="3">3</div>
      <div class="pin-key" data-value="4">4</div>
      <div class="pin-key" data-value="5">5</div>
      <div class="pin-key" data-value="6">6</div>
      <div class="pin-key" data-value="7">7</div>
      <div class="pin-key" data-value="8">8</div>
      <div class="pin-key" data-value="9">9</div>
      <div class="pin-key empty"></div>
      <div class="pin-key" data-value="0">0</div>
      <div class="pin-key back" data-value="back">
        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5"/><path d="M12 5l-7 7 7 7"/></svg>
      </div>
    </div>

    <div class="pin-footer" id="pinFooter">
      <a href="/app/biometric" id="bioLink">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/><path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/></svg>
        Use Fingerprint Instead
      </a>
    </div>
  </div>
</div>
<div class="toast-msg" id="toastMsg"></div>

<script>
(function() {
  let pin = '';
  let step = 'enter';       // 'enter' | 'confirm' | 'set'
  let firstPin = '';
  const maxPin = 4;
  const dots = document.querySelectorAll('.pin-dot');
  const toast = document.getElementById('toastMsg');
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
  const pinTitle = document.getElementById('pinTitle');
  const pinSub = document.getElementById('pinSub');
  const bioLink = document.getElementById('bioLink');

  function showToast(msg, type) {
    toast.className = 'toast-msg ' + (type || 'error');
    toast.textContent = msg;
    toast.classList.add('show');
    clearTimeout(toast._timer);
    toast._timer = setTimeout(() => toast.classList.remove('show'), 3000);
  }

  function updateDots(hasError) {
    dots.forEach((dot, i) => {
      dot.classList.toggle('filled', i < pin.length);
      dot.classList.toggle('error', !!hasError);
    });
  }

  function resetPin() { pin = ''; updateDots(false); }

  function setMode(mode, title, sub) {
    step = mode;
    pin = '';
    firstPin = '';
    pinTitle.textContent = title;
    pinSub.textContent = sub;
    updateDots(false);
  }

  async function onPinComplete() {
    showLoader('Verifying PIN');
    if (step === 'enter') {
      try {
        const r = await fetch('/user/check/pin', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
          body: JSON.stringify({ pin: pin })
        });
        hideLoader();
        const data = parseInt(await r.text(), 10);
        if (data === 1) {
          window.location.href = '/app/biometric';
        } else if (data === 2) {
          setMode('set', 'Set PIN', 'Create a new 4-digit PIN');
          showToast('Please set a new PIN.', 'success');
          resetPin();
        } else {
          showToast('Incorrect PIN. Please try again.', 'error');
          dots.forEach(d => d.classList.add('error'));
          setTimeout(() => { pin = ''; updateDots(false); }, 400);
        }
      } catch(err) {
        hideLoader();
        showToast('Could not verify PIN. Try again.', 'error');
        setTimeout(() => { pin = ''; updateDots(false); }, 400);
      }
    } else if (step === 'set') {
      // First entry — store and move to confirm
      firstPin = pin;
      setMode('confirm', 'Confirm PIN', 'Re-enter your 4-digit PIN');
      resetPin();
    } else if (step === 'confirm') {
      if (pin !== firstPin) {
        hideLoader();
        showToast('PINs do not match. Try again.', 'error');
        setMode('set', 'Set PIN', 'Create a new 4-digit PIN');
        resetPin();
        return;
      }
      showLoader('Saving PIN');
      try {
        const r = await fetch('/user/setup-pin/store', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
          body: JSON.stringify({ pin_code: pin })
        });
        const data = await r.json();
        hideLoader();
        if (data.success) {
          showToast('PIN set successfully!', 'success');
          setTimeout(() => { window.location.href = '/app/biometric'; }, 600);
        } else {
          showToast(data.message || 'Failed to set PIN.', 'error');
          setMode('set', 'Set PIN', 'Create a new 4-digit PIN');
          resetPin();
        }
      } catch(err) {
        hideLoader();
        showToast('Connection error. Try again.', 'error');
        setMode('set', 'Set PIN', 'Create a new 4-digit PIN');
        resetPin();
      }
    }
  }

  // Keypad handler
  document.getElementById('pinPad').addEventListener('click', function(e) {
    const key = e.target.closest('.pin-key');
    if (!key) return;
    const val = key.dataset.value;

    if (val === 'back') {
      pin = pin.slice(0, -1);
      updateDots(false);
      return;
    }

    if (pin.length >= maxPin) return;
    pin += val;
    updateDots(false);

    if (pin.length === maxPin) {
      onPinComplete();
    }
  });

  // Determine mode on load
  (async function init() {
    try {
      // Try to get user info
      const statusR = await fetch('/app/pin/status', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      if (!statusR.ok) { window.location.href = '/app/login'; return; }
      const status = await statusR.json();

      if (status.user) {
        document.getElementById('userName').textContent = status.user.name;
        document.getElementById('userAvatar').textContent = status.user.initials;
      }

      if (!status.has_pin) {
        setMode('set', 'Set PIN', 'Create a new 4-digit PIN');
      } else {
        setMode('enter', 'Enter PIN', 'Enter your 4-digit security PIN');
      }
    } catch(e) {
      // Fallback to Blade data
      @auth
      document.getElementById('userName').textContent = '{{ auth()->user()->fullname ?? "User" }}';
      document.getElementById('userAvatar').textContent = '{{ auth()->user()->fullname ? strtoupper(substr(auth()->user()->fullname, 0, 1)) : "U" }}';
      @endauth
      setMode('enter', 'Enter PIN', 'Enter your 4-digit security PIN');
    }
  })();
})();
document.getElementById('pageLoader').classList.add('loaded');
</script>
@include('partials.app-loader')
</body>
</html>

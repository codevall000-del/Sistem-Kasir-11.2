<template>
  <div class="min-h-screen flex items-stretch bg-[var(--paper)] text-[var(--ink)] font-body">

    <!-- LEFT: printed receipt panel -->
    <div class="hidden lg:flex lg:w-[44%] bg-[var(--ink)] text-[var(--paper)] items-center justify-center p-12 relative overflow-hidden">
      <div class="absolute inset-0 opacity-[0.06]" style="background-image: radial-gradient(var(--paper) 1px, transparent 1px); background-size: 18px 18px;"></div>

      <div class="relative w-full max-w-sm bg-[var(--paper)] text-[var(--ink)] px-7 py-8 shadow-2xl receipt-edge-bottom">
        <div class="text-center mb-5">
          <p class="font-mono text-[10px] tracking-[0.35em] uppercase text-[var(--ink-soft)]">Sistem Kasir</p>
          <h1 class="font-mono font-extrabold text-2xl tracking-tight mt-1">JISS JOSS JUSS</h1>
          <p class="text-xs text-[var(--ink-soft)] mt-1">JL. Terentang Elok</p>
        </div>

        <div class="dashed-line my-4"></div>

        <div class="space-y-1.5 font-mono text-[11px] text-[var(--ink-soft)]">
          <div class="flex justify-between"><span>SESI</span><span>MASUK ADMIN</span></div>
          <div class="flex justify-between"><span>TERMINAL</span><span>#01</span></div>
          <div class="flex justify-between"><span>STATUS</span><span class="text-[var(--till)] font-bold">STANDBY</span></div>
        </div>

        <div class="dashed-line my-4"></div>

        <p class="text-center text-sm leading-relaxed text-[var(--ink)]">
          Masuk untuk membuka laci kasir,<br class="hidden sm:block" />
          kelola stok, dan mulai transaksi.
        </p>

        <div class="dashed-line my-4"></div>

        <div class="flex justify-center">
          <div class="stamp-badge">JISS JOSS</div>
        </div>
      </div>
    </div>

    <!-- RIGHT: login form as a keypad panel -->
    <div class="flex-1 flex items-center justify-center px-6 py-16">
      <div class="w-full max-w-sm">

        <div class="lg:hidden text-center mb-8">
          <p class="font-mono text-[10px] tracking-[0.35em] uppercase text-[var(--ink-soft)]">Sistem Kasir</p>
          <h1 class="font-mono font-extrabold text-2xl tracking-tight">JISS JOSSS JUSSS</h1>
        </div>

        <div class="mb-8">
          <p class="font-mono text-[10px] tracking-[0.3em] uppercase text-[var(--till)] font-bold mb-2">— Masuk —</p>
          <h2 class="text-3xl font-extrabold tracking-tight text-[var(--ink)]">Login Admin</h2>
          <p class="text-[var(--ink-soft)] text-sm mt-2">Masukkan kredensial untuk membuka kasir.</p>
        </div>

        <form @submit.prevent="handleLogin" class="space-y-5">
          <div>
            <label class="block font-mono text-[10px] tracking-[0.2em] uppercase text-[var(--ink-soft)] mb-2">Email</label>
            <input
              v-model="form.email"
              type="email"
              placeholder="admin@gmail.com"
              class="till-input"
              required
            />
          </div>

          <div>
            <label class="block font-mono text-[10px] tracking-[0.2em] uppercase text-[var(--ink-soft)] mb-2">Kata Sandi</label>
            <input
              v-model="form.password"
              type="password"
              placeholder="••••••••"
              class="till-input"
              required
            />
          </div>

          <button type="submit" class="till-button-primary w-full mt-2">
            <span>Buka Kasir</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="5" y1="12" x2="19" y2="12" />
              <polyline points="12 5 19 12 12 19" />
            </svg>
          </button>
        </form>

        
      </div>
    </div>
  </div>
</template>

<script setup>
const form = ref({ email: '', password: '' })

const handleLogin = async () => {
  try {
    const res = await $fetch('http://localhost/crud-laravel/public/api/login', {
      method: 'POST',
      body: form.value
    })

    useCookie('token').value = 'admin'
    navigateTo('/kasir')
  } catch (err) {
    alert('Email atau Password salah!')
  }
}
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

:root {
  --paper: #F0EDE3;
  --paper-dim: #E4E0D0;
  --ink: #1C2A22;
  --ink-soft: #66705F;
  --till: #0F6B4C;
  --till-dark: #0A4E37;
  --stamp: #C1852E;
  --brick: #AE402A;
  --line: #C9C2A9;
}

.font-body { font-family: 'Plus Jakarta Sans', sans-serif; }
.font-mono { font-family: 'JetBrains Mono', monospace; }

.dashed-line {
  border-top: 1.5px dashed var(--line);
}

.receipt-edge-bottom {
  position: relative;
}
.receipt-edge-bottom::after {
  content: '';
  position: absolute;
  left: 0; right: 0; bottom: -11px; height: 22px;
  background-image: radial-gradient(circle at 11px 0, transparent 11px, var(--paper) 11.5px);
  background-size: 22px 22px;
  background-repeat: repeat-x;
}

.stamp-badge {
  font-family: 'JetBrains Mono', monospace;
  font-weight: 800;
  font-size: 10px;
  letter-spacing: 0.15em;
  color: var(--till);
  border: 2px solid var(--till);
  padding: 6px 14px;
  border-radius: 999px;
  transform: rotate(-3deg);
}

.till-input {
  width: 100%;
  background: var(--paper-dim);
  border: 1.5px solid var(--line);
  border-radius: 8px;
  padding: 11px 14px;
  font-size: 14px;
  color: var(--ink);
  transition: border-color 0.15s ease, background 0.15s ease;
}
.till-input:focus {
  outline: none;
  border-color: var(--till);
  background: white;
}

.till-button-primary {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  background: var(--till);
  color: var(--paper);
  font-weight: 700;
  font-size: 14px;
  padding: 12px 20px;
  border-radius: 8px;
  transition: background 0.15s ease, transform 0.1s ease;
}
.till-button-primary:hover { background: var(--till-dark); }
.till-button-primary:active { transform: scale(0.98); }
</style>
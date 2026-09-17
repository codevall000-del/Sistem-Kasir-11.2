<template>
  <div class="min-h-screen bg-[var(--paper)] text-[var(--ink)] font-body">

    <!-- ============================ NAV ============================ -->
    <header class="relative bg-[var(--ink)] text-[var(--paper)]">
      <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-full bg-[var(--till)] flex items-center justify-center font-mono font-extrabold text-sm">JUSJUS</div>
          <div class="leading-tight">
            <p class="font-mono text-[9px] tracking-[0.3em] uppercase text-[var(--paper)]/60">Toko Favian</p>
            <h1 class="font-mono font-bold text-base tracking-tight">Dubis Store</h1>
          </div>
        </div>

        <nav class="hidden md:flex items-center gap-1 text-sm font-medium">
          <NuxtLink to="/kasir" class="nav-pill">Kasir</NuxtLink>
          <NuxtLink to="/barang" class="nav-pill">Kelola Barang</NuxtLink>
          <NuxtLink to="/riwayattransaksi" class="nav-pill nav-pill-active">Riwayat</NuxtLink>
        </nav>

        <button @click="logout" class="logout-btn">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          <span class="hidden sm:inline">Keluar</span>
        </button>
      </div>
      <div class="receipt-edge"></div>
    </header>

    <!-- ============================ MAIN ============================ -->
    <main class="max-w-7xl mx-auto px-6 pt-10 pb-16">
      <div class="mb-8">
        <p class="font-mono text-[10px] tracking-[0.3em] uppercase text-[var(--till)] font-bold mb-1">— Arsip —</p>
        <h2 class="text-3xl font-extrabold tracking-tight">Riwayat Transaksi</h2>
      </div>

      <!-- State Loading -->
      <div v-if="loading" class="table-panel py-16 text-center">
        <div class="inline-flex items-center gap-3 font-mono text-sm text-[var(--ink-soft)]">
          <span class="loader-dot"></span>
          Memuat riwayat transaksi…
        </div>
      </div>

      <!-- Tabel Data Riwayat -->
      <div v-else class="table-panel">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="font-mono text-[10px] tracking-[0.15em] uppercase text-[var(--ink-soft)]">
              <th class="p-4">No. Transaksi</th>
              <th class="p-4">Tanggal</th>
              <th class="p-4">Total Belanja</th>
              <th class="p-4">Uang Bayar</th>
              <th class="p-4">Kembalian</th>
              <th class="p-4 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="riwayatList.length === 0">
              <td colspan="6" class="p-10 text-center text-[var(--ink-soft)] text-sm">
                Belum ada riwayat transaksi.
              </td>
            </tr>

            <tr v-for="trx in riwayatList" :key="trx.id" class="row-line">
              <td class="p-4 font-mono text-xs font-bold text-[var(--till)]">{{ trx.no_nota || 'TRX-' + trx.id }}</td>
              <td class="p-4 text-sm text-[var(--ink-soft)]">{{ formatTanggal(trx.created_at) }}</td>
              <td class="p-4 font-mono font-bold">Rp {{ trx.total_harga ? trx.total_harga.toLocaleString() : 0 }}</td>
              <td class="p-4 font-mono text-sm">Rp {{ trx.bayar ? trx.bayar.toLocaleString() : 0 }}</td>
              <td class="p-4 font-mono text-sm text-[var(--ink-soft)]">Rp {{ trx.kembali ? trx.kembali.toLocaleString() : 0 }}</td>
              <td class="p-4">
                <button @click="bukaDetail(trx)" class="detail-btn mx-auto">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                  Detail
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>

    <!-- ============================ MODAL DETAIL ============================ -->
    <div v-if="showModal" class="fixed inset-0 bg-[var(--ink)]/60 backdrop-blur-sm flex items-center justify-center z-50 p-4" @click.self="tutupModal">
      <div class="bg-[var(--paper)] w-full max-w-md p-6 receipt-edge-bottom shadow-2xl">
        <div class="flex justify-between items-start mb-4">
          <div>
            <p class="font-mono text-[10px] tracking-[0.3em] uppercase text-[var(--till)] font-bold mb-1">— Detail Struk —</p>
            <h3 class="text-lg font-extrabold tracking-tight">{{ detailData.no_nota || 'TRX-' + detailData.id }}</h3>
          </div>
          <button @click="tutupModal" class="text-[var(--ink-soft)] hover:text-[var(--brick)] font-bold text-xl leading-none">&times;</button>
        </div>

        <div class="font-mono text-xs text-[var(--ink-soft)] mb-4">
          <div class="flex justify-between"><span>TANGGAL</span><span>{{ formatTanggal(detailData.created_at) }}</span></div>
          <div class="flex justify-between"><span>KASIR</span><span>Admin</span></div>
        </div>

        <div class="dashed-line mb-3"></div>

        <div class="max-h-56 overflow-y-auto pr-1 -mr-1 mb-3">
          <table class="w-full text-sm text-left border-collapse">
            <thead>
              <tr class="font-mono text-[10px] tracking-[0.1em] uppercase text-[var(--ink-soft)]">
                <th class="py-2">Nama Barang</th>
                <th class="py-2 text-center">Qty</th>
                <th class="py-2 text-right">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in (detailData.items || [])" :key="index" class="border-t border-[var(--line)]">
                <td class="py-2 font-medium">{{ item.nama_barang }}</td>
                <td class="py-2 text-center font-mono">{{ item.qty }}</td>
                <td class="py-2 text-right font-mono">Rp {{ (item.harga * item.qty).toLocaleString() }}</td>
              </tr>
              <tr v-if="!(detailData.items && detailData.items.length)">
                <td colspan="3" class="py-6 text-center text-xs text-[var(--ink-soft)] italic">
                  Data detail barang belum tersedia dari database.
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="dashed-line mb-4"></div>

        <div class="font-mono text-sm space-y-1.5">
          <div class="flex justify-between font-bold">
            <span>TOTAL BELANJA</span>
            <span class="text-[var(--till)]">Rp {{ detailData.total_harga ? detailData.total_harga.toLocaleString() : 0 }}</span>
          </div>
          <div class="flex justify-between text-[var(--ink-soft)]">
            <span>BAYAR (TUNAI)</span>
            <span>Rp {{ detailData.bayar ? detailData.bayar.toLocaleString() : 0 }}</span>
          </div>
          <div class="flex justify-between text-[var(--ink-soft)]">
            <span>KEMBALIAN</span>
            <span>Rp {{ detailData.kembali ? detailData.kembali.toLocaleString() : 0 }}</span>
          </div>
        </div>

        <div class="mt-6 flex justify-end">
          <button @click="tutupModal" class="till-button-ghost">Tutup</button>
        </div>
      </div>
    </div>

    <!-- ============================ MODAL KONFIRMASI LOGOUT ============================ -->
    <div v-if="showLogoutModal" class="fixed inset-0 bg-[var(--ink)]/60 backdrop-blur-sm flex items-center justify-center z-50 p-4" @click.self="showLogoutModal = false">
      <div class="bg-[var(--paper)] w-full max-w-xs p-6 receipt-edge-bottom shadow-2xl text-center">
        <p class="font-mono text-[10px] tracking-[0.3em] uppercase text-[var(--brick)] font-bold mb-1">— Konfirmasi —</p>
        <h3 class="text-lg font-extrabold tracking-tight mb-2">Yakin Ingin Keluar?</h3>
        <p class="text-sm text-[var(--ink-soft)] mb-6">Sesi kasir akan diakhiri dan kamu perlu login kembali.</p>
        <div class="flex gap-3">
          <button @click="showLogoutModal = false" class="till-button-ghost flex-1">Batal</button>
          <button @click="logout" class="till-button-danger flex-1">Ya, Keluar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const token = useCookie('token')
const riwayatList = ref([])
const loading = ref(true)

const showModal = ref(false)
const showLogoutModal = ref(false)
const detailData = ref(null)

const formatTanggal = (dateString) => {
  if (!dateString) return '-'
  const date = new Date(dateString)
  return date.toLocaleDateString('id-ID', {
    day: 'numeric', month: 'short', year: 'numeric',
    hour: '2-digit', minute: '2-digit'
  })
}

const loadRiwayat = async () => {
  loading.value = true
  try {
    riwayatList.value = await $fetch('http://localhost/crud-laravel/public/api/transaksi', {
      headers: { Authorization: `Bearer ${token.value}` }
    })
  } catch (err) {
    if (err.status === 401) {
      navigateTo('/')
    }
    console.error('Gagal mengambil data riwayat', err)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  if (!token.value) {
    navigateTo('/')
  } else {
    loadRiwayat()
  }
})

const bukaDetail = (trx) => {
  detailData.value = trx
  showModal.value = true
}

const tutupModal = () => {
  showModal.value = false
  detailData.value = null
}

const logout = () => {
  token.value = null
  navigateTo('/')
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

.dashed-line { border-top: 1.5px dashed var(--line); }

header.relative { position: relative; }
.receipt-edge {
  position: absolute; left: 0; right: 0; bottom: -11px; height: 22px;
  background-image: radial-gradient(circle at 11px 0, transparent 11px, var(--ink) 11.5px);
  background-size: 22px 22px;
  background-repeat: repeat-x;
}
.receipt-edge-bottom { position: relative; }
.receipt-edge-bottom::after {
  content: '';
  position: absolute; left: 0; right: 0; bottom: -11px; height: 22px;
  background-image: radial-gradient(circle at 11px 0, transparent 11px, var(--paper) 11.5px);
  background-size: 22px 22px;
  background-repeat: repeat-x;
}

.nav-pill {
  padding: 8px 14px;
  border-radius: 999px;
  color: var(--paper);
  opacity: 0.65;
  transition: opacity 0.15s ease, background 0.15s ease;
}
.nav-pill:hover { opacity: 1; }
.nav-pill-active { opacity: 1; background: var(--till); font-weight: 700; }

.logout-btn {
  display: flex; align-items: center; gap: 6px;
  font-size: 13px; font-weight: 600;
  color: var(--paper);
  background: rgba(255,255,255,0.08);
  border: 1px solid rgba(255,255,255,0.15);
  padding: 8px 14px;
  border-radius: 999px;
  transition: background 0.15s ease, border-color 0.15s ease;
}
.logout-btn:hover { background: var(--brick); border-color: var(--brick); }

.table-panel {
  background: white;
  border: 1.5px solid var(--line);
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 10px 30px -18px rgba(28,42,34,0.2);
}
.table-panel thead tr { border-bottom: 1.5px solid var(--line); background: var(--paper-dim); }
.row-line { border-bottom: 1px solid var(--line); transition: background 0.15s ease; }
.row-line:last-child { border-bottom: none; }
.row-line:hover { background: var(--paper-dim); }

.detail-btn {
  display: flex; align-items: center; gap: 5px;
  background: var(--till);
  color: var(--paper);
  font-weight: 700;
  font-size: 12px;
  padding: 7px 12px;
  border-radius: 999px;
  transition: background 0.15s ease;
}
.detail-btn:hover { background: var(--till-dark); }

.till-button-danger {
  display: flex; align-items: center; justify-content: center; gap: 8px;
  background: var(--brick);
  color: var(--paper);
  font-weight: 700;
  font-size: 14px;
  padding: 10px 16px;
  border-radius: 8px;
  transition: background 0.15s ease;
}
.till-button-danger:hover { background: #8f331f; }

.till-button-ghost {
  background: transparent;
  border: 1.5px solid var(--line);
  color: var(--ink);
  font-weight: 700;
  font-size: 14px;
  padding: 9px 18px;
  border-radius: 8px;
}
.till-button-ghost:hover { border-color: var(--ink); }

.loader-dot {
  width: 8px; height: 8px;
  border-radius: 50%;
  background: var(--till);
  animation: pulse-dot 1s ease-in-out infinite;
}
@keyframes pulse-dot {
  0%, 100% { opacity: 0.3; transform: scale(0.8); }
  50% { opacity: 1; transform: scale(1.1); }
}
</style>
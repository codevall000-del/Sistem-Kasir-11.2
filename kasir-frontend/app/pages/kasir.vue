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
          <NuxtLink to="/kasir" class="nav-pill nav-pill-active">Kasir</NuxtLink>
          <NuxtLink to="/barang" class="nav-pill">Kelola Barang</NuxtLink>
          <NuxtLink to="/riwayattransaksi" class="nav-pill">Riwayat</NuxtLink>
        </nav>

        <button @click="logout" class="logout-btn">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          <span class="hidden sm:inline">Keluar</span>
        </button>
      </div>
      <div class="receipt-edge"></div>
    </header>

    <!-- ============================ MAIN ============================ -->
    <main class="no-print print:hidden max-w-7xl mx-auto px-6 pt-10 pb-16">
      <div class="mb-8">
        <p class="font-mono text-[10px] tracking-[0.3em] uppercase text-[var(--till)] font-bold mb-1">— Transaksi Baru —</p>
        <h2 class="text-3xl font-extrabold tracking-tight">Halaman Kasir</h2>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Daftar Barang -->
        <section class="lg:col-span-2">
          <div class="flex items-center gap-3 mb-4">
            <h3 class="font-mono text-xs tracking-[0.2em] uppercase font-bold text-[var(--ink-soft)]">Pilih Barang</h3>
            <div class="dashed-line flex-1"></div>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            <button
              v-for="b in daftarBarang"
              :key="b.id"
              type="button"
              @click="tambahKeKeranjang(b)"
              class="item-card text-left"
              :class="{ 'item-card-disabled': b.stok === 0 }"
            >
              <div class="font-semibold text-[var(--ink)] leading-snug">{{ b.nama_barang }}</div>
              <div class="font-mono font-bold text-[var(--till)] mt-2">Rp {{ b.harga.toLocaleString() }}</div>
              <div class="flex items-center justify-between mt-3">
                <span class="stock-chip" :class="b.stok === 0 ? 'stock-chip-out' : b.stok <= 5 ? 'stock-chip-low' : 'stock-chip-ok'">
                  Stok {{ b.stok }}
                </span>
                <span class="text-[var(--till)] opacity-0 group-hover:opacity-100">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                </span>
              </div>
            </button>
          </div>

          <div v-if="daftarBarang.length === 0" class="empty-state">
            Belum ada barang. Tambahkan barang di menu <strong>Kelola Barang</strong> terlebih dahulu.
          </div>
        </section>

        <!-- Keranjang Belanja: styled as a live receipt -->
        <aside class="lg:sticky lg:top-6 self-start">
          <div class="receipt-panel receipt-edge-bottom">
            <div class="text-center mb-4">
              <p class="font-mono text-[9px] tracking-[0.3em] uppercase text-[var(--ink-soft)]">Struk Sementara</p>
              <h3 class="font-mono font-extrabold text-lg tracking-tight">KERANJANG</h3>
            </div>

            <div class="dashed-line mb-3"></div>

            <div v-if="keranjang.length === 0" class="text-center py-10 text-sm text-[var(--ink-soft)]">
              Keranjang masih kosong.<br />Ketuk barang untuk menambahkan.
            </div>

            <div v-else class="space-y-3 max-h-[320px] overflow-y-auto pr-1 -mr-1">
              <div v-for="item in keranjang" :key="item.id" class="flex items-start justify-between gap-2">
                <div class="min-w-0">
                  <div class="font-medium text-sm truncate">{{ item.nama_barang }}</div>
                  <div class="font-mono text-[11px] text-[var(--ink-soft)]">Rp {{ item.harga.toLocaleString() }} × {{ item.qty }}</div>
                </div>
                <div class="flex items-center gap-1.5 shrink-0">
                  <button @click="kurangiQty(item)" class="qty-btn">–</button>
                  <span class="font-mono text-xs w-4 text-center">{{ item.qty }}</span>
                  <button @click="tambahQty(item)" :disabled="item.qty >= item.stok" class="qty-btn">+</button>
                </div>
              </div>
            </div>

            <div class="dashed-line my-4"></div>

            <div class="flex justify-between items-baseline mb-4">
              <span class="font-mono text-xs uppercase tracking-wide text-[var(--ink-soft)]">Total</span>
              <span class="font-mono font-extrabold text-2xl text-[var(--ink)]">Rp {{ totalHarga.toLocaleString() }}</span>
            </div>

            <label class="block font-mono text-[10px] tracking-[0.2em] uppercase text-[var(--ink-soft)] mb-2">Uang Tunai</label>
            <input v-model.number="bayar" type="number" class="till-input mb-3" placeholder="0" />

            <div v-if="bayar > 0" class="flex justify-between text-sm font-bold font-mono mb-4" :class="kembalian >= 0 ? 'text-[var(--till)]' : 'text-[var(--brick)]'">
              <span>{{ kembalian >= 0 ? 'KEMBALIAN' : 'KURANG BAYAR' }}</span>
              <span>Rp {{ Math.abs(kembalian).toLocaleString() }}</span>
            </div>

            <button
              @click="prosesTransaksi"
              :disabled="bayar < totalHarga || totalHarga === 0 || processing"
              class="till-button-primary w-full"
            >
              {{ processing ? 'Memproses…' : 'Bayar Sekarang' }}
            </button>
          </div>
        </aside>
      </div>
    </main>

    <!-- ============================ MODAL STRUK ============================ -->
    <div v-if="showStrukModal" class="no-print print:hidden fixed inset-0 bg-[var(--ink)]/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
      <div class="bg-[var(--paper)] w-full max-w-sm p-6 receipt-edge-bottom shadow-2xl">
        <div class="text-center mb-4">
          <div class="stamp-badge inline-block mb-2">LUNAS</div>
          <h3 class="text-lg font-extrabold text-[var(--ink)]">Transaksi Berhasil</h3>
          <p class="font-mono text-xs text-[var(--ink-soft)] mt-1">Kembalian Rp {{ strukData.kembalian.toLocaleString() }}</p>
        </div>

        <div class="dashed-line mb-3"></div>

        <div class="font-mono text-xs text-[var(--ink)] max-h-56 overflow-y-auto pr-1 -mr-1">
          <div class="text-center mb-3">
            <p class="font-bold">POS KASIR TOKO</p>
            <p class="text-[var(--ink-soft)] text-[10px]">Jl. Contoh Alamat No. 123</p>
          </div>
          <p class="text-[var(--ink-soft)] mb-2">Tgl: {{ strukData.tanggal }}</p>
          <div class="dashed-line mb-2"></div>
          <div v-for="item in strukData.items" :key="item.id" class="mb-1.5 flex justify-between gap-2">
            <div>
              {{ item.nama_barang }}
              <div class="text-[10px] text-[var(--ink-soft)]">{{ item.qty }} × {{ item.harga.toLocaleString() }}</div>
            </div>
            <div class="text-right shrink-0">{{ (item.harga * item.qty).toLocaleString() }}</div>
          </div>
          <div class="dashed-line my-2"></div>
          <div class="flex justify-between font-bold"><span>TOTAL</span><span>Rp {{ strukData.total.toLocaleString() }}</span></div>
          <div class="flex justify-between text-[var(--ink-soft)]"><span>TUNAI</span><span>Rp {{ strukData.bayar.toLocaleString() }}</span></div>
          <div class="flex justify-between text-[var(--ink-soft)]"><span>KEMBALI</span><span>Rp {{ strukData.kembalian.toLocaleString() }}</span></div>
        </div>

        <div class="dashed-line my-4"></div>

        <div class="flex flex-col gap-2.5">
          <button @click="cetakStruk" class="till-button-primary w-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            <span>Cetak Struk</span>
          </button>
          <button @click="tutupModalStruk" class="till-button-ghost w-full">Transaksi Baru</button>
        </div>
      </div>
    </div>

    <!-- ============================ STRUK THERMAL PRINT ============================ -->
    <div v-if="strukData" class="hidden print:block w-[58mm] mx-auto text-black font-mono text-xs p-1">
      <div class="text-center mb-3">
        <h2 class="font-bold text-sm uppercase">POS KASIR TOKO</h2>
        <p class="text-[10px]">Jl. Contoh Alamat No. 123</p>
        <p class="text-[10px]">Telp: 0812-xxxx-xxxx</p>
      </div>
      <div class="border-b border-dashed border-black mb-2 pb-1 text-[10px]">
        <p>Tgl: {{ strukData.tanggal }}</p>
        <p>Kasir: Admin</p>
      </div>
      <div class="border-b border-dashed border-black mb-2 pb-2">
        <div v-for="item in strukData.items" :key="item.id" class="mb-1">
          <div class="font-semibold">{{ item.nama_barang }}</div>
          <div class="flex justify-between text-[10px]">
            <span>{{ item.qty }} x {{ item.harga.toLocaleString() }}</span>
            <span>{{ (item.harga * item.qty).toLocaleString() }}</span>
          </div>
        </div>
      </div>
      <div class="text-[11px] mb-3">
        <div class="flex justify-between font-bold"><span>TOTAL</span><span>Rp {{ strukData.total.toLocaleString() }}</span></div>
        <div class="flex justify-between"><span>TUNAI</span><span>Rp {{ strukData.bayar.toLocaleString() }}</span></div>
        <div class="flex justify-between"><span>KEMBALI</span><span>Rp {{ strukData.kembalian.toLocaleString() }}</span></div>
      </div>
      <div class="text-center mt-3 text-[10px]">
        <p>*** LUNAS ***</p>
        <p>Terima Kasih Atas Kunjungan Anda</p>
      </div>
    </div>

    <!-- ============================ MODAL KONFIRMASI LOGOUT ============================ -->
    <div v-if="showLogoutModal" class="no-print fixed inset-0 bg-[var(--ink)]/60 backdrop-blur-sm flex items-center justify-center z-50 p-4" @click.self="showLogoutModal = false">
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
import { ref, computed, onMounted } from 'vue'

const token = useCookie('token')
const daftarBarang = ref([])
const keranjang = ref([])
const bayar = ref(0)
const processing = ref(false)

const showStrukModal = ref(false)
const strukData = ref(null)
const showLogoutModal = ref(false)

const loadBarang = async () => {
  try {
    daftarBarang.value = await $fetch('http://localhost/crud-laravel/public/api/barang', {
      headers: { Authorization: `Bearer ${token.value}` }
    })
  } catch (err) {
    if (err.status === 401) navigateTo('/')
  }
}

onMounted(() => {
  if (!token.value) navigateTo('/')
  loadBarang()
})

const tambahKeKeranjang = (barang) => {
  if (barang.stok === 0) return
  const item = keranjang.value.find(i => i.id === barang.id)
  if (item) {
    if (item.qty < barang.stok) item.qty++
  } else {
    keranjang.value.push({ ...barang, qty: 1 })
  }
}

const tambahQty = (item) => {
  if (item.qty < item.stok) item.qty++
}

const kurangiQty = (item) => {
  item.qty--
  if (item.qty <= 0) {
    keranjang.value = keranjang.value.filter(i => i.id !== item.id)
  }
}

const totalHarga = computed(() => {
  return keranjang.value.reduce((total, item) => total + (item.harga * item.qty), 0)
})

const kembalian = computed(() => {
  return bayar.value - totalHarga.value
})

const prosesTransaksi = async () => {
  processing.value = true
  try {
    await $fetch('http://localhost/crud-laravel/public/api/transaksi', {
      method: 'POST',
      headers: { Authorization: `Bearer ${token.value}` },
      body: {
        bayar: bayar.value,
        items: keranjang.value.map(item => ({
          id: item.id,
          qty: item.qty
        }))
      }
    })

    strukData.value = {
      tanggal: new Date().toLocaleString('id-ID'),
      items: [...keranjang.value],
      total: totalHarga.value,
      bayar: bayar.value,
      kembalian: kembalian.value
    }

    showStrukModal.value = true

  } catch (err) {
    const errorMessage = err.response?._data?.message || 'Transaksi gagal, coba lagi.'
    alert(errorMessage)
    console.error(err)
  } finally {
    processing.value = false
  }
}

const cetakStruk = () => {
  window.print()
}

const tutupModalStruk = async () => {
  showStrukModal.value = false
  strukData.value = null
  keranjang.value = []
  bayar.value = 0
  await loadBarang()
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
.nav-pill-active {
  opacity: 1;
  background: var(--till);
  font-weight: 700;
}

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

.item-card {
  background: white;
  border: 1.5px solid var(--line);
  border-radius: 10px;
  padding: 14px;
  transition: border-color 0.15s ease, transform 0.1s ease, box-shadow 0.15s ease;
}
.item-card:hover { border-color: var(--till); transform: translateY(-2px); box-shadow: 0 6px 16px -8px rgba(15,107,76,0.35); }
.item-card:active { transform: translateY(0); }
.item-card-disabled { opacity: 0.4; pointer-events: none; }

.stock-chip {
  font-family: 'JetBrains Mono', monospace;
  font-size: 10px;
  font-weight: 700;
  padding: 2px 8px;
  border-radius: 999px;
}
.stock-chip-ok { background: rgba(15,107,76,0.12); color: var(--till); }
.stock-chip-low { background: rgba(193,133,46,0.14); color: var(--stamp); }
.stock-chip-out { background: rgba(174,64,42,0.12); color: var(--brick); }

.empty-state {
  text-align: center;
  padding: 40px 20px;
  color: var(--ink-soft);
  font-size: 14px;
  border: 1.5px dashed var(--line);
  border-radius: 10px;
  margin-top: 12px;
}

.receipt-panel {
  background: var(--paper);
  border: 1.5px solid var(--line);
  padding: 22px;
  box-shadow: 0 10px 30px -14px rgba(28,42,34,0.25);
}

.qty-btn {
  width: 22px; height: 22px;
  display: flex; align-items: center; justify-content: center;
  background: var(--paper-dim);
  border: 1px solid var(--line);
  border-radius: 6px;
  font-weight: 700;
  color: var(--ink);
  transition: background 0.15s ease;
}
.qty-btn:hover:not(:disabled) { background: var(--till); color: var(--paper); border-color: var(--till); }
.qty-btn:disabled { opacity: 0.35; }

.till-input {
  width: 100%;
  background: white;
  border: 1.5px solid var(--line);
  border-radius: 8px;
  padding: 10px 12px;
  font-size: 14px;
  color: var(--ink);
  font-family: 'JetBrains Mono', monospace;
}
.till-input:focus { outline: none; border-color: var(--till); }

.till-button-primary {
  display: flex; align-items: center; justify-content: center; gap: 8px;
  background: var(--till);
  color: var(--paper);
  font-weight: 700;
  font-size: 14px;
  padding: 12px 18px;
  border-radius: 8px;
  transition: background 0.15s ease, transform 0.1s ease;
}
.till-button-primary:hover:not(:disabled) { background: var(--till-dark); }
.till-button-primary:disabled { background: var(--line); color: var(--ink-soft); cursor: not-allowed; }

.till-button-ghost {
  background: transparent;
  border: 1.5px solid var(--line);
  color: var(--ink);
  font-weight: 700;
  font-size: 14px;
  padding: 11px 18px;
  border-radius: 8px;
  transition: border-color 0.15s ease;
}
.till-button-ghost:hover { border-color: var(--ink); }

.till-button-danger {
  display: flex; align-items: center; justify-content: center; gap: 8px;
  background: var(--brick);
  color: var(--paper);
  font-weight: 700;
  font-size: 14px;
  padding: 11px 18px;
  border-radius: 8px;
  transition: background 0.15s ease, transform 0.1s ease;
}
.till-button-danger:hover { background: #8f331f; }
.till-button-danger:active { transform: scale(0.98); }

.stamp-badge {
  font-family: 'JetBrains Mono', monospace;
  font-weight: 800;
  font-size: 10px;
  letter-spacing: 0.15em;
  color: var(--till);
  border: 2px solid var(--till);
  padding: 5px 12px;
  border-radius: 999px;
  transform: rotate(-3deg);
}

/* Guaranteed print hiding — doesn't depend on Tailwind's print: variant
   being generated by your build's content-scanning config. */
@media print {
  .no-print {
    display: none !important;
  }
}
</style>
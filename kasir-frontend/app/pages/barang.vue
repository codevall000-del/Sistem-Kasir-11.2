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
          <NuxtLink to="/barang" class="nav-pill nav-pill-active">Kelola Barang</NuxtLink>
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
    <main class="max-w-7xl mx-auto px-6 pt-10 pb-16">
      <div class="mb-8">
        <p class="font-mono text-[10px] tracking-[0.3em] uppercase text-[var(--till)] font-bold mb-1">— Master Data —</p>
        <h2 class="text-3xl font-extrabold tracking-tight">Kelola Barang</h2>
      </div>

      <!-- Form Tambah Barang -->
      <form @submit.prevent="simpanBarang" class="form-panel mb-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
          <div class="lg:col-span-1">
            <label class="field-label">Kode Barang</label>
            <input v-model="form.kode_barang" placeholder="BRG-001" class="till-input" required />
          </div>
          <div class="lg:col-span-1">
            <label class="field-label">Nama Barang</label>
            <input v-model="form.nama_barang" placeholder="Nama barang" class="till-input" required />
          </div>
          <div class="lg:col-span-1">
            <label class="field-label">Harga (Rp)</label>
            <input v-model.number="form.harga" type="number" placeholder="0" class="till-input" required />
          </div>
          <div class="lg:col-span-1">
            <label class="field-label">Stok</label>
            <input v-model.number="form.stok" type="number" placeholder="0" class="till-input" required />
          </div>
          <div class="lg:col-span-1 flex items-end">
            <button type="submit" class="till-button-primary w-full">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              <span>Tambah</span>
            </button>
          </div>
        </div>
      </form>

      <!-- Tabel Data Barang -->
      <div class="flex items-center gap-3 mb-4">
        <h3 class="font-mono text-xs tracking-[0.2em] uppercase font-bold text-[var(--ink-soft)]">Daftar Barang · {{ barangList.length }} item</h3>
        <div class="dashed-line flex-1"></div>
      </div>

      <div class="table-panel">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="font-mono text-[10px] tracking-[0.15em] uppercase text-[var(--ink-soft)]">
              <th class="p-4">Kode</th>
              <th class="p-4">Nama Barang</th>
              <th class="p-4">Harga</th>
              <th class="p-4">Stok</th>
              <th class="p-4 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="barangList.length === 0">
              <td colspan="5" class="p-10 text-center text-[var(--ink-soft)] text-sm">
                Belum ada barang. Tambahkan barang pertama menggunakan form di atas.
              </td>
            </tr>
            <tr v-for="b in barangList" :key="b.id" class="row-line">
              <td class="p-4 font-mono text-xs font-bold text-[var(--till)]">{{ b.kode_barang }}</td>
              <td class="p-4 font-medium">{{ b.nama_barang }}</td>
              <td class="p-4 font-mono">Rp {{ b.harga.toLocaleString() }}</td>
              <td class="p-4">
                <span class="stock-chip" :class="b.stok === 0 ? 'stock-chip-out' : b.stok <= 5 ? 'stock-chip-low' : 'stock-chip-ok'">
                  {{ b.stok }}
                </span>
              </td>
              <td class="p-4">
                <div class="flex gap-2 justify-center">
                  <button @click="bukaModalEdit(b)" class="icon-btn icon-btn-edit" title="Edit">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>
                  </button>
                  <button @click="hapusBarang(b.id)" class="icon-btn icon-btn-danger" title="Hapus">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <polyline points="3 6 5 6 21 6" />
                      <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                      <path d="M10 11v6" />
                      <path d="M14 11v6" />
                      <path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2" />
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>

    <!-- ============================ MODAL EDIT ============================ -->
    <div v-if="showModal" class="fixed inset-0 bg-[var(--ink)]/60 backdrop-blur-sm flex items-center justify-center z-50 p-4" @click.self="tutupModal">
      <div class="bg-[var(--paper)] w-full max-w-md p-6 receipt-edge-bottom shadow-2xl">
        <div class="mb-5">
          <p class="font-mono text-[10px] tracking-[0.3em] uppercase text-[var(--till)] font-bold mb-1">— Ubah Data —</p>
          <h3 class="text-xl font-extrabold tracking-tight">Edit Barang</h3>
        </div>

        <form @submit.prevent="updateBarang" class="space-y-4">
          <div>
            <label class="field-label">Kode Barang</label>
            <input v-model="editForm.kode_barang" class="till-input" required />
          </div>
          <div>
            <label class="field-label">Nama Barang</label>
            <input v-model="editForm.nama_barang" class="till-input" required />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="field-label">Harga (Rp)</label>
              <input v-model.number="editForm.harga" type="number" class="till-input" required />
            </div>
            <div>
              <label class="field-label">Stok</label>
              <input v-model.number="editForm.stok" type="number" class="till-input" required />
            </div>
          </div>

          <div class="dashed-line my-2"></div>

          <div class="flex gap-3 justify-end pt-1">
            <button type="button" @click="tutupModal" class="till-button-ghost">Batal</button>
            <button type="submit" class="till-button-primary">Update Barang</button>
          </div>
        </form>
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
const token = useCookie('token')
const barangList = ref([])
const form = ref({ kode_barang: '', nama_barang: '', harga: '', stok: '' })

const showModal = ref(false)
const showLogoutModal = ref(false)
const editId = ref(null)
const editForm = ref({ kode_barang: '', nama_barang: '', harga: '', stok: '' })

const loadBarang = async () => {
  barangList.value = await $fetch('http://localhost/crud-laravel/public/api/barang', {
    headers: { Authorization: `Bearer ${token.value}` }
  })
}

onMounted(() => {
  if (!token.value) navigateTo('/')
  loadBarang()
})

const simpanBarang = async () => {
  await $fetch('http://localhost/crud-laravel/public/api/barang', {
    method: 'POST',
    headers: { Authorization: `Bearer ${token.value}` },
    body: form.value
  })
  form.value = { kode_barang: '', nama_barang: '', harga: '', stok: '' }
  loadBarang()
}

const bukaModalEdit = (b) => {
  editId.value = b.id
  editForm.value = {
    kode_barang: b.kode_barang,
    nama_barang: b.nama_barang,
    harga: b.harga,
    stok: b.stok
  }
  showModal.value = true
}

const tutupModal = () => {
  showModal.value = false
  editId.value = null
}

const updateBarang = async () => {
  await $fetch(`http://localhost/crud-laravel/public/api/barang/${editId.value}`, {
    method: 'PUT',
    headers: { Authorization: `Bearer ${token.value}` },
    body: editForm.value
  })
  tutupModal()
  loadBarang()
}

const hapusBarang = async (id) => {
  if (confirm('Yakin ingin menghapus?')) {
    await $fetch(`http://localhost/crud-laravel/public/api/barang/${id}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${token.value}` }
    })
    loadBarang()
  }
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

.form-panel {
  background: white;
  border: 1.5px solid var(--line);
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 10px 30px -18px rgba(28,42,34,0.2);
}

.field-label {
  display: block;
  font-family: 'JetBrains Mono', monospace;
  font-size: 10px;
  letter-spacing: 0.15em;
  text-transform: uppercase;
  color: var(--ink-soft);
  margin-bottom: 6px;
}

.till-input {
  width: 100%;
  background: var(--paper-dim);
  border: 1.5px solid var(--line);
  border-radius: 8px;
  padding: 10px 12px;
  font-size: 14px;
  color: var(--ink);
}
.till-input:focus { outline: none; border-color: var(--till); background: white; }

.till-button-primary {
  display: flex; align-items: center; justify-content: center; gap: 6px;
  background: var(--till);
  color: var(--paper);
  font-weight: 700;
  font-size: 14px;
  padding: 10px 16px;
  border-radius: 8px;
  transition: background 0.15s ease;
  white-space: nowrap;
}
.till-button-primary:hover { background: var(--till-dark); }

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
  padding: 9px 16px;
  border-radius: 8px;
}
.till-button-ghost:hover { border-color: var(--ink); }

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

.stock-chip {
  font-family: 'JetBrains Mono', monospace;
  font-size: 11px;
  font-weight: 700;
  padding: 3px 10px;
  border-radius: 999px;
}
.stock-chip-ok { background: rgba(15,107,76,0.12); color: var(--till); }
.stock-chip-low { background: rgba(193,133,46,0.14); color: var(--stamp); }
.stock-chip-out { background: rgba(174,64,42,0.12); color: var(--brick); }

.icon-btn {
  width: 32px; height: 32px;
  display: flex; align-items: center; justify-content: center;
  border-radius: 8px;
  transition: background 0.15s ease, color 0.15s ease;
}
.icon-btn-edit { color: var(--stamp); background: rgba(193,133,46,0.1); }
.icon-btn-edit:hover { background: var(--stamp); color: white; }
.icon-btn-danger { color: var(--brick); background: rgba(174,64,42,0.1); }
.icon-btn-danger:hover { background: var(--brick); color: white; }
</style>
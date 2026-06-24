@extends('layouts.siwarga')

@section('title', 'Daftar Iuran')

@section('content')
<div x-data="iuranApp()" x-init="init()">
    <!-- Stats Cards - Buku Kas Style -->
    <section class="ledger-card rounded-sm overflow-hidden mb-8">
        <div class="grid grid-cols-1 sm:grid-cols-4">
            <div class="p-5 sm:col-divider sm:border-l-0">
                <p class="text-[11px] uppercase tracking-[0.18em] text-[var(--ink-soft)]">Total Warga</p>
                <p class="font-display text-3xl font-bold text-[var(--moss-deep)] mt-1">{{ $stats['total_warga'] }} <span class="text-sm font-mono font-normal text-[var(--ink-soft)]">KK</span></p>
            </div>
            <div class="p-5 sm:col-divider">
                <p class="text-[11px] uppercase tracking-[0.18em] text-[var(--ink-soft)]">Total Iuran</p>
                <p class="font-display text-3xl font-bold text-[var(--moss-deep)] mt-1">{{ $stats['total_iuran'] }}</p>
            </div>
            <div class="p-5 sm:col-divider">
                <p class="text-[11px] uppercase tracking-[0.18em] text-[var(--ink-soft)]">Iuran Pending</p>
                <p class="font-display text-3xl font-bold text-[var(--brick)] mt-1">{{ $stats['iuran_pending'] }}</p>
            </div>
            <div class="p-5 sm:col-divider">
                <p class="text-[11px] uppercase tracking-[0.18em] text-[var(--brick)]">Total Tunggakan</p>
                <p class="font-display text-3xl font-bold text-[var(--brick)] mt-1">Rp {{ number_format($stats['total_tunggakan'], 0, ',', '.') }}</p>
            </div>
        </div>
    </section>

    <!-- Main Table Card -->
    <div class="ledger-card rounded-sm">
        <div class="flex flex-wrap items-center justify-between gap-3 p-4 border-b border-[var(--ledger-line)]">
            <div>
                <h2 class="font-display text-xl font-bold text-[var(--moss-deep)]">Buku Catatan Iuran</h2>
                <p class="text-xs text-[var(--ink-soft)] font-mono">Menampilkan seluruh transaksi iuran warga</p>
            </div>
            <button @click="openModal()" class="btn-brick px-5 py-2.5 rounded-sm text-sm font-mono font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                + Catat Iuran Baru
            </button>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto scrollbar-thin">
            <table class="w-full text-sm min-w-[640px]">
                <thead>
                    <tr class="text-left text-[11px] uppercase tracking-[0.12em] text-[var(--ink-soft)] border-b border-[var(--ledger-line)]">
                        <th class="py-3 pl-4 pr-2 font-mono font-medium">No.</th>
                        <th class="py-3 px-2 font-mono font-medium">Nama Warga</th>
                        <th class="py-3 px-2 font-mono font-medium hide-mobile">Alamat</th>
                        <th class="py-3 px-2 font-mono font-medium">Bulan</th>
                        <th class="py-3 px-2 font-mono font-medium text-right">Jumlah</th>
                        <th class="py-3 px-2 font-mono font-medium">Status</th>
                        <th class="py-3 px-4 font-mono font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--ledger-line)]">
                    @forelse($iurans as $iuran)
                    <tr class="ledger-row">
                        <td class="py-3 pl-4 pr-2 font-mono text-[var(--ink-soft)]">{{ str_pad($loop->iteration + ($iurans->currentPage() - 1) * $iurans->perPage(), 3, '0', STR_PAD_LEFT) }}</td>
                        <td class="py-3 px-2">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-[var(--moss-deep)] text-[var(--paper)] flex items-center justify-center font-display font-bold text-sm shrink-0">
                                    {{ strtoupper(substr($iuran->warga->nama, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-medium">{{ $iuran->warga->nama }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-2 text-[var(--ink-soft)] hide-mobile">{{ $iuran->warga->alamat }}</td>
                        <td class="py-3 px-2 font-mono">{{ \Carbon\Carbon::parse($iuran->bulan)->format('M Y') }}</td>
                        <td class="py-3 px-2 font-mono text-right">Rp {{ number_format($iuran->jumlah_iuran, 0, ',', '.') }}</td>
                        <td class="py-3 px-2">
                            @if($iuran->status === 'pending')
                            <span class="status-pending text-[11px] px-2 py-0.5 rounded-sm font-mono uppercase">Pending</span>
                            @else
                            <span class="status-selesai text-[11px] px-2 py-0.5 rounded-sm font-mono uppercase">Selesai</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right">
                            <button @click="editIuran({{ $iuran->id }})" class="text-xs font-mono underline text-[var(--moss-mid)] hover:text-[var(--moss-deep)]">Ubah</button>
                            <span class="text-[var(--ledger-line)] mx-1">/</span>
                            <button @click="deleteIuran({{ $iuran->id }})" class="text-xs font-mono underline text-[var(--brick)] hover:text-[var(--brick-deep)]">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-[var(--ledger-line)] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-[var(--ink-soft)] text-lg font-display">Belum ada data iuran</p>
                                <p class="text-[var(--ink-soft)] text-sm font-mono mt-1">Klik tombol "+ Catat Iuran Baru" untuk menambahkan data</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($iurans->hasPages())
        <div class="flex items-center justify-between p-4 border-t border-[var(--ledger-line)] text-xs font-mono text-[var(--ink-soft)]">
            <span>Menampilkan {{ $iurans->count() }} dari {{ $iurans->total() }} entri</span>
            <div>
                {{ $iurans->links() }}
            </div>
        </div>
        @endif
    </div>

    <!-- Modal -->
    <div x-show="isModalOpen" 
         x-on:keydown.escape.window="closeModal()"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div @click="closeModal()" class="fixed inset-0 transition-opacity" style="background-color: rgba(0,0,0,0.4);"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div x-show="isModalOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom rounded-sm overflow-hidden transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full shadow-2xl"
                 style="background-color: #EAE3D1; border: 1px solid #D9CFA8;">
                <div class="p-5" style="border-bottom: 1px dashed #D9CFA8; background-color: #EAE3D1;">
                    <h3 class="font-display text-xl font-bold" style="color: #2F3B2C;" x-text="isEdit ? 'Ubah Data Iuran' : 'Catat Iuran Baru'"></h3>
                    <p class="text-xs font-mono mt-1" style="color: #5A5648;" x-text="isEdit ? 'PUT /api/iuran/{id}' : 'POST /api/iuran'"></p>
                </div>
                <form @submit.prevent="submitForm()" class="p-5 md:p-7 relative" style="background-color: #EAE3D1;">
                    <!-- Perforated edge -->
                    <div class="perforation absolute left-0 top-0 bottom-0 w-3 -ml-1.5 hide-mobile"></div>
                    
                    <input type="hidden" x-model="form.id" id="iuran_id">
                    <div class="space-y-5">
                        <div>
                            <label for="id_warga" class="block text-[11px] uppercase tracking-[0.15em] mb-1.5" style="color: #5A5648;">Nama Warga</label>
                            <select id="id_warga" x-model="form.id_warga" required
                                    class="w-full px-3 py-2.5 rounded-sm text-sm"
                                    style="background-color: #F2EEE2; border: 1px solid #D9CFA8; color: #1C1B17;">
                                <option value="">— Pilih warga terdaftar —</option>
                                @foreach($wargas as $warga)
                                <option value="{{ $warga->id }}">{{ $warga->nama }} — {{ $warga->alamat }}</option>
                                @endforeach
                            </select>
                            <p x-show="errors.id_warga" class="mt-1 text-xs font-mono" style="color: #B5482F;" x-text="errors.id_warga"></p>
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase tracking-[0.15em] mb-1.5" style="color: #5A5648;">Bulan Iuran</label>
                            <div class="flex gap-2">
                                <select id="bulan_bulan" x-model="form.bulan_month" required
                                        class="flex-1 px-3 py-2.5 rounded-sm text-sm"
                                        style="background-color: #F2EEE2; border: 1px solid #D9CFA8; color: #1C1B17;">
                                    <option value="">-- Bulan --</option>
                                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $index => $bulan)
                                    <option value="{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}">{{ $bulan }}</option>
                                    @endforeach
                                </select>
                                <select id="bulan_tahun" x-model="form.bulan_year" required
                                        class="w-28 px-3 py-2.5 rounded-sm text-sm"
                                        style="background-color: #F2EEE2; border: 1px solid #D9CFA8; color: #1C1B17;">
                                    <option value="">-- Tahun --</option>
                                    @for($y = date('Y'); $y >= date('Y') - 10; $y--)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <p x-show="errors.bulan" class="mt-1 text-xs font-mono" style="color: #B5482F;" x-text="errors.bulan"></p>
                        </div>
                        <div>
                            <label for="jumlah_iuran" class="block text-[11px] uppercase tracking-[0.15em] mb-1.5" style="color: #5A5648;">Jumlah Iuran</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm" style="color: #5A5648;">Rp</span>
                                <input type="number" id="jumlah_iuran" x-model="form.jumlah_iuran" required min="0"
                                       class="w-full pl-9 pr-3 py-2.5 rounded-sm text-sm"
                                       style="background-color: #F2EEE2; border: 1px solid #D9CFA8; color: #1C1B17;">
                            </div>
                            <p x-show="errors.jumlah_iuran" class="mt-1 text-xs font-mono" style="color: #B5482F;" x-text="errors.jumlah_iuran"></p>
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase tracking-[0.15em] mb-1.5" style="color: #5A5648;">Status Pembayaran</label>
                            <div class="flex gap-3">
                                <label for="status-pending" class="flex-1 flex items-center gap-2 rounded-sm px-3 py-2.5 cursor-pointer"
                                       style="border: 1px solid #B5482F; background-color: rgba(181,72,47,0.06);">
                                    <input type="radio" id="status-pending" x-model="form.status" value="pending" class="accent-[#B5482F]" />
                                    <span class="text-sm font-mono" style="color: #1C1B17;">Pending</span>
                                </label>
                                <label for="status-selesai" class="flex-1 flex items-center gap-2 rounded-sm px-3 py-2.5 cursor-pointer"
                                       style="border: 1px solid #D9CFA8;">
                                    <input type="radio" id="status-selesai" x-model="form.status" value="selesai" class="accent-[#5C6E4F]" />
                                    <span class="text-sm font-mono" style="color: #1C1B17;">Selesai</span>
                                </label>
                            </div>
                            <p x-show="errors.status" class="mt-1 text-xs font-mono" style="color: #B5482F;" x-text="errors.status"></p>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3 pt-4" style="border-top: 1px dashed #D9CFA8;">
                        <button type="button" @click="closeModal()" class="px-5 py-2.5 rounded-sm text-sm font-mono"
                                style="border: 1px solid #5A5648; color: #5A5648; background: transparent;">Batal</button>
                        <button type="submit" class="px-6 py-2.5 rounded-sm text-sm font-mono font-medium flex items-center"
                                style="background-color: #B5482F; color: #F2EEE2; border: 1px solid #8E371F;">
                            <span x-show="!isLoading" x-text="isEdit ? 'Simpan Perubahan' : 'Simpan Iuran'"></span>
                            <svg x-show="isLoading" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function iuranApp() {
    return {
        isModalOpen: false,
        isEdit: false,
        isLoading: false,
        form: { 
            id: '', 
            id_warga: '', 
            bulan_month: '', 
            bulan_year: new Date().getFullYear().toString(),
            jumlah_iuran: '', 
            status: 'pending' 
        },
        errors: {},
        init() {},
        openModal() { 
            this.isModalOpen = true; 
            this.isEdit = false; 
            this.resetForm(); 
        },
        closeModal() { 
            this.isModalOpen = false; 
            this.resetForm(); 
        },
        resetForm() { 
            this.form = { 
                id: '', 
                id_warga: '', 
                bulan_month: '', 
                bulan_year: new Date().getFullYear().toString(),
                jumlah_iuran: '', 
                status: 'pending' 
            }; 
            this.errors = {}; 
        },
        async editIuran(id) {
            try {
                const response = await axios.get(`/api/iuran/${id}`);
                const data = response.data.data;
                const dateParts = data.bulan.split('-');
                this.form = { 
                    id: data.id, 
                    id_warga: data.id_warga, 
                    bulan_month: dateParts[1], 
                    bulan_year: dateParts[0],
                    jumlah_iuran: data.jumlah_iuran, 
                    status: data.status 
                };
                this.isEdit = true; 
                this.isModalOpen = true;
            } catch (error) { 
                window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Gagal mengambil data iuran', type: 'error' } })); 
            }
        },
        async submitForm() {
            this.isLoading = true; 
            this.errors = {};
            try {
                // Combine bulan_month and bulan_year into bulan format YYYY-MM
                const bulan = `${this.form.bulan_year}-${this.form.bulan_month}`;
                const payload = { 
                    id_warga: this.form.id_warga, 
                    bulan: bulan, 
                    jumlah_iuran: parseInt(this.form.jumlah_iuran), 
                    status: this.form.status 
                };
                let response;
                if (this.isEdit) { 
                    response = await axios.put(`/api/iuran/${this.form.id}`, payload); 
                } else { 
                    response = await axios.post('/api/iuran', payload); 
                }
                window.dispatchEvent(new CustomEvent('toast', { detail: { message: response.data.message, type: 'success' } }));
                this.closeModal(); 
                location.reload();
            } catch (error) {
                if (error.response && error.response.status === 422) { 
                    this.errors = error.response.data.errors || {}; 
                } else { 
                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: error.response?.data?.message || 'Terjadi kesalahan', type: 'error' } })); 
                }
            } finally { 
                this.isLoading = false; 
            }
        },
        async deleteIuran(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus data iuran ini?')) { return; }
            try {
                const response = await axios.delete(`/api/iuran/${id}`);
                window.dispatchEvent(new CustomEvent('toast', { detail: { message: response.data.message, type: 'success' } }));
                location.reload();
            } catch (error) { 
                window.dispatchEvent(new CustomEvent('toast', { detail: { message: error.response?.data?.message || 'Gagal menghapus data', type: 'error' } })); 
            }
        }
    }
}
</script>
@endsection

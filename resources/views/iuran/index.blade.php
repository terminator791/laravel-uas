@extends('layouts.app')

@section('title', 'Daftar Iuran')

@section('content')
<div x-data="iuranApp()" x-init="init()">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Daftar Iuran Warga</h1>
        <p class="text-gray-600 mt-1">Kelola data iuran bulanan warga RT</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Warga</p>
                    <p class="text-3xl font-bold mt-1">{{ $stats['total_warga'] }}</p>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Iuran</p>
                    <p class="text-3xl font-bold mt-1">{{ $stats['total_iuran'] }}</p>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Tunggakan</p>
                    <p class="text-3xl font-bold mt-1">{{ $stats['iuran_pending'] }}</p>
                </div>
                <div class="bg-yellow-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Tunggakan</p>
                    <p class="text-3xl font-bold mt-1">Rp {{ number_format($stats['total_tunggakan'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h2 class="text-xl font-semibold text-gray-800">Data Iuran</h2>
                <button @click="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium transition-colors flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Iuran
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Warga</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Bulan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($iurans as $iuran)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $loop->iteration + ($iurans->currentPage() - 1) * $iurans->perPage() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="bg-blue-100 text-blue-600 w-10 h-10 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $iuran->warga->nama }}</p>
                                    <p class="text-sm text-gray-500">{{ $iuran->warga->alamat }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ \Carbon\Carbon::parse($iuran->bulan)->format('F Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">Rp {{ number_format($iuran->jumlah_iuran, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($iuran->status === 'pending')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full mr-1.5"></span>
                                Pending
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span>
                                Selesai
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <button @click="editIuran({{ $iuran->id }})" class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-50 transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button @click="deleteIuran({{ $iuran->id }})" class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50 transition-colors" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p class="text-gray-500 text-lg">Belum ada data iuran</p>
                                <p class="text-gray-400 text-sm mt-1">Klik tombol "Tambah Iuran" untuk menambahkan data</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($iurans->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $iurans->links() }}
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
            <div @click="closeModal()" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div x-show="isModalOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800" x-text="isEdit ? 'Edit Iuran' : 'Tambah Iuran Baru'"></h3>
                </div>
                <form @submit.prevent="submitForm()" class="p-6">
                    <input type="hidden" x-model="form.id" id="iuran_id">
                    <div class="space-y-5">
                        <div>
                            <label for="id_warga" class="block text-sm font-medium text-gray-700 mb-2">Nama Warga</label>
                            <select id="id_warga" x-model="form.id_warga" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">-- Pilih Warga --</option>
                                @foreach($wargas as $warga)
                                <option value="{{ $warga->id }}">{{ $warga->nama }} - {{ $warga->alamat }}</option>
                                @endforeach
                            </select>
                            <p x-show="errors.id_warga" class="mt-1 text-sm text-red-600" x-text="errors.id_warga"></p>
                        </div>
                        <div>
                            <label for="bulan" class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                            <input type="month" id="bulan" x-model="form.bulan" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <p x-show="errors.bulan" class="mt-1 text-sm text-red-600" x-text="errors.bulan"></p>
                        </div>
                        <div>
                            <label for="jumlah_iuran" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Iuran (Rp)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                                <input type="number" id="jumlah_iuran" x-model="form.jumlah_iuran" required min="0"
                                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                            <p x-show="errors.jumlah_iuran" class="mt-1 text-sm text-red-600" x-text="errors.jumlah_iuran"></p>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="status" x-model="form.status" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="pending">Pending</option>
                                <option value="selesai">Selesai</option>
                            </select>
                            <p x-show="errors.status" class="mt-1 text-sm text-red-600" x-text="errors.status"></p>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="closeModal()" class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center">
                            <span x-show="!isLoading" x-text="isEdit ? 'Simpan Perubahan' : 'Simpan'"></span>
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
        form: { id: '', id_warga: '', bulan: '', jumlah_iuran: '', status: 'pending' },
        errors: {},
        openModal() { this.isModalOpen = true; this.isEdit = false; this.resetForm(); },
        closeModal() { this.isModalOpen = false; this.resetForm(); },
        resetForm() { this.form = { id: '', id_warga: '', bulan: '', jumlah_iuran: '', status: 'pending' }; this.errors = {}; },
        async editIuran(id) {
            try {
                const response = await axios.get(`/api/iuran/${id}`);
                const data = response.data.data;
                this.form = { id: data.id, id_warga: data.id_warga, bulan: data.bulan.substring(0, 7), jumlah_iuran: data.jumlah_iuran, status: data.status };
                this.isEdit = true; this.isModalOpen = true;
            } catch (error) { window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Gagal mengambil data iuran', type: 'error' } })); }
        },
        async submitForm() {
            this.isLoading = true; this.errors = {};
            try {
                const payload = { id_warga: this.form.id_warga, bulan: this.form.bulan, jumlah_iuran: parseInt(this.form.jumlah_iuran), status: this.form.status };
                let response;
                if (this.isEdit) { response = await axios.put(`/api/iuran/${this.form.id}`, payload); }
                else { response = await axios.post('/api/iuran', payload); }
                window.dispatchEvent(new CustomEvent('toast', { detail: { message: response.data.message, type: 'success' } }));
                this.closeModal(); location.reload();
            } catch (error) {
                if (error.response && error.response.status === 422) { this.errors = error.response.data.errors || {}; }
                else { window.dispatchEvent(new CustomEvent('toast', { detail: { message: error.response?.data?.message || 'Terjadi kesalahan', type: 'error' } })); }
            } finally { this.isLoading = false; }
        },
        async deleteIuran(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus data iuran ini?')) { return; }
            try {
                const response = await axios.delete(`/api/iuran/${id}`);
                window.dispatchEvent(new CustomEvent('toast', { detail: { message: response.data.message, type: 'success' } }));
                location.reload();
            } catch (error) { window.dispatchEvent(new CustomEvent('toast', { detail: { message: error.response?.data?.message || 'Gagal menghapus data', type: 'error' } })); }
        }
    }
}
</script>
@endsection

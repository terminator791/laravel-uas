@extends('layouts.app')

@section('title', 'Laporan Tunggakan')

@section('content')
<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Laporan Tunggakan Iuran</h1>
        <p class="text-gray-600 mt-1">Daftar warga yang memiliki tunggakan iuran</p>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <form action="{{ route('iuran.tunggakan') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="flex-1">
                <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">Pilih Tahun</label>
                <select name="tahun" id="tahun" onchange="this.form.submit()"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium transition-colors">
                Tampilkan
            </button>
        </form>
    </div>

    <!-- Summary -->
    <div class="bg-gradient-to-r from-red-500 to-orange-500 rounded-xl shadow-lg p-6 mb-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-red-100 text-sm font-medium">Total Tunggakan Tahun {{ $tahun }}</p>
                <p class="text-3xl font-bold mt-1">Rp {{ number_format($data->sum('total_tunggakan'), 0, ',', '.') }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Data -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Daftar Warga dengan Tunggakan</h2>
        </div>

        <div class="overflow-x-auto">
            @forelse($data as $item)
            <div class="p-6 border-b border-gray-200 last:border-b-0 hover:bg-gray-50 transition-colors">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <div class="bg-red-100 text-red-600 w-12 h-12 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $item['warga']->nama }}</h3>
                                <p class="text-sm text-gray-500">{{ $item['warga']->alamat }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Total Tunggakan</p>
                        <p class="text-2xl font-bold text-red-600">Rp {{ number_format($item['total_tunggakan'], 0, ',', '.') }}</p>
                    </div>
                </div>

                @if($item['detail_tunggakan']->count() > 0)
                <div class="mt-4 pl-16">
                    <p class="text-sm font-medium text-gray-700 mb-2">Detail Tunggakan:</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                        @foreach($item['detail_tunggakan'] as $tunggakan)
                        <div class="flex items-center justify-between bg-yellow-50 border border-yellow-200 rounded-lg px-4 py-2">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm text-gray-700">{{ \Carbon\Carbon::parse($tunggakan->bulan)->format('F Y') }}</span>
                            </div>
                            <span class="text-sm font-medium text-yellow-700">Rp {{ number_format($tunggakan->jumlah_iuran, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @empty
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-green-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-500 text-lg">Tidak ada tunggakan untuk tahun {{ $tahun }}</p>
                <p class="text-gray-400 text-sm mt-1">Semua warga telah menyelesaikan iurannya!</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

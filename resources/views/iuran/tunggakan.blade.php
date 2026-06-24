@extends('layouts.siwarga')

@section('title', 'Laporan Tunggakan')

@section('content')
<div>
    <!-- Header -->
    <div class="flex flex-wrap items-baseline justify-between gap-3 mb-6">
        <div class="flex items-baseline gap-3">
            <h2 class="font-display text-xl font-bold text-[var(--brick)]">Laporan Tunggakan</h2>
            <p class="text-xs text-[var(--ink-soft)] font-mono">Daftar warga dengan tunggakan iuran</p>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="ledger-card rounded-sm p-5 mb-6">
        <form action="{{ route('iuran.tunggakan') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label for="tahun" class="block text-[11px] uppercase tracking-[0.15em] text-[var(--ink-soft)] mb-1.5">Pilih Tahun</label>
                <select name="tahun" id="tahun" onchange="this.form.submit()"
                        class="w-full px-3 py-2.5 rounded-sm text-sm">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="btn-moss px-5 py-2.5 rounded-sm text-sm font-mono font-medium">
                Tampilkan
            </button>
        </form>
    </div>

    <!-- Summary Strip -->
    <div class="ledger-card rounded-sm p-5 mb-6 inline-flex items-center gap-4 w-full sm:w-auto">
        <span class="text-xs font-mono uppercase tracking-[0.15em] text-[var(--ink-soft)]">Total tunggakan tahun {{ $tahun }}</span>
        <span class="font-display font-bold text-xl text-[var(--brick)]">Rp {{ number_format($data->sum('total_tunggakan'), 0, ',', '.') }}</span>
    </div>

    <!-- Data Cards -->
    <div class="space-y-3">
        @forelse($data as $item)
        <div class="ledger-card rounded-sm">
            <div class="flex flex-wrap items-center justify-between gap-3 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-[var(--moss-deep)] text-[var(--paper)] flex items-center justify-center font-display font-bold text-sm shrink-0">
                        {{ strtoupper(substr($item['warga']->nama, 0, 2)) }}
                    </div>
                    <div>
                        <p class="font-medium text-sm">{{ $item['warga']->nama }}</p>
                        <p class="text-xs text-[var(--ink-soft)] font-mono">{{ $item['warga']->alamat }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[10px] uppercase tracking-[0.15em] text-[var(--ink-soft)]">Total Tunggakan</p>
                    <p class="font-display font-bold text-lg text-[var(--brick)]">Rp {{ number_format($item['total_tunggakan'], 0, ',', '.') }}</p>
                </div>
            </div>

            @if($item['detail_tunggakan']->count() > 0)
            <div class="border-t border-dashed border-[var(--ledger-line)] px-4 py-3 bg-[rgba(181,72,47,0.04)]">
                <p class="text-[11px] uppercase tracking-[0.15em] text-[var(--ink-soft)] mb-2">Rincian bulan belum dibayar</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($item['detail_tunggakan'] as $tunggakan)
                    <span class="status-pending text-xs font-mono px-2.5 py-1 rounded-sm">
                        {{ \Carbon\Carbon::parse($tunggakan->bulan)->format('M Y') }} · Rp {{ number_format($tunggakan->jumlah_iuran, 0, ',', '.') }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @empty
        <div class="ledger-card rounded-sm p-12 text-center">
            <svg class="w-16 h-16 text-[var(--moss-mid)] mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-[var(--ink-soft)] text-lg font-display">Tidak ada tunggakan untuk tahun {{ $tahun }}</p>
            <p class="text-[var(--ink-soft)] text-sm font-mono mt-1">Semua warga telah menyelesaikan iurannya!</p>
        </div>
        @endforelse
    </div>
</div>
@endsection

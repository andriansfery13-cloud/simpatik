@extends('layouts.app')

@section('title', 'Sedang Dikembangkan')

@section('content')
<div class="flex items-center justify-center min-h-[60vh] animate-fade-in">
    <div class="text-center p-8 bg-white rounded-2xl shadow-card border border-gray-100 max-w-md w-full">
        <div class="w-20 h-20 bg-simpatik-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-simpatik-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Segera Hadir</h2>
        <p class="text-gray-500 text-sm mb-6">
            Modul ini sedang dalam tahap pengembangan (Tahap Lanjutan). Fitur ini akan segera tersedia untuk melengkapi ekosistem SIMPATIK.
        </p>
        <a href="{{ route('dashboard') }}" class="btn-primary inline-flex justify-center w-full py-2.5">
            Kembali ke Dashboard Utama
        </a>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Tambah Kegiatan Pembangunan')

@section('content')
<div class="space-y-6 animate-fade-in max-w-4xl mx-auto">

    {{-- Page Header --}}
    <div class="bg-white p-5 rounded-xl shadow-card border border-gray-100 flex items-center gap-4">
        <a href="{{ route('kegiatan.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tambah Kegiatan Baru</h1>
            <p class="text-sm text-gray-500 mt-1">Daftarkan rencana kegiatan pembangunan fisik di desa Anda.</p>
        </div>
    </div>

    {{-- Livewire Form Component --}}
    <div class="bg-white rounded-xl shadow-card border border-gray-100 p-6">
        @livewire('kegiatan-form')
    </div>
</div>
@endsection

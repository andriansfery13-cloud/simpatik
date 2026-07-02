@extends('layouts.app')

@section('title', 'Edit Data Desa')

@section('content')
<div class="space-y-6 animate-fade-in max-w-2xl mx-auto">

    <div class="bg-white p-5 rounded-xl shadow-card border border-gray-100 flex items-center gap-4">
        <a href="{{ route('desa.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Data Desa: {{ $desa->nama }}</h1>
            <p class="text-sm text-gray-500 mt-1">Perbarui informasi profil dan wilayah desa.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden p-6">
        @livewire('desa-form', ['desa' => $desa])
    </div>
</div>
@endsection

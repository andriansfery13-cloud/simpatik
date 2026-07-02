@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
    {{-- Header --}}
    <div class="flex items-center gap-4 bg-white p-5 rounded-xl shadow-card border border-gray-100">
        <a href="{{ route('users.index') }}" class="p-2 rounded-lg hover:bg-gray-50 text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Pengguna: {{ $user->name }}</h1>
            <p class="text-sm text-gray-500">Perbarui profil dan hak akses pengguna ini.</p>
        </div>
    </div>

    {{-- Livewire Form Component --}}
    <div class="bg-white rounded-xl shadow-card border border-gray-100 p-6">
        @livewire('user-form', ['user' => $user])
    </div>
</div>
@endsection

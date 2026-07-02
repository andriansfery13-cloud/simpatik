@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('content')
<div class="space-y-6 animate-fade-in max-w-4xl mx-auto">

    <div class="bg-white p-5 rounded-xl shadow-card border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-simpatik-100 text-simpatik-600 rounded-full flex items-center justify-center text-xl font-bold uppercase">
            {{ substr(auth()->user()->name, 0, 1) }}
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Profil Saya</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola informasi profil dan kata sandi akun Anda.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="md:col-span-1 space-y-4">
            <div class="bg-white p-5 rounded-xl shadow-card border border-gray-100">
                <h3 class="font-bold text-gray-800 border-b border-gray-100 pb-2 mb-3">Informasi Akun</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-xs text-gray-500">Jabatan</p>
                        <p class="font-medium text-gray-800">{{ auth()->user()->jabatan ?? 'Aparatur Sipil Negara' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Peran Akses (Role)</p>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700 mt-1 uppercase">
                            {{ str_replace('_', ' ', auth()->user()->role) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="md:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-card border border-gray-100">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-card border border-gray-100">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-card border border-gray-100 border-t-4 border-t-red-500">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

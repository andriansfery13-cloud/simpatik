@extends('layouts.app')

@section('title', 'Pengaturan Integrasi Sistem')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
    {{-- Header --}}
    <div class="flex items-center gap-4 bg-white p-5 rounded-xl shadow-card border border-gray-100">
        <div class="w-12 h-12 bg-simpatik-50 rounded-xl flex items-center justify-center text-simpatik-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pengaturan Integrasi Sistem</h1>
            <p class="text-sm text-gray-500">Kelola kunci API dan integrasi pihak ketiga untuk modul lanjutan SIMPATIK.</p>
        </div>
    </div>

    {{-- Forms --}}
    <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden">
        <form action="{{ route('settings.integrations.update') }}" method="POST" class="p-6">
            @csrf
            
            <div class="border-b border-gray-100 pb-4 mb-6">
                <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    Integrasi OpenAI (AI Analytics)
                </h3>
                <p class="text-xs text-gray-500 mt-1 ml-7">Digunakan untuk menghasilkan analisis cerdas dan rekomendasi pada modul AI Analytics.</p>
            </div>

            <div class="space-y-4 max-w-2xl">
                <div>
                    <label for="openai_api_key" class="form-label block text-sm font-medium text-gray-700 mb-1">OpenAI API Key</label>
                    <div class="relative">
                        <input type="password" id="openai_api_key" name="openai_api_key" class="form-input block w-full rounded-lg border-gray-300 shadow-sm focus:border-simpatik-500 focus:ring-simpatik-500 text-sm font-mono" value="{{ $openaiKey }}" placeholder="sk-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx" autocomplete="off">
                        <button type="button" onclick="document.getElementById('openai_api_key').type = document.getElementById('openai_api_key').type === 'password' ? 'text' : 'password'" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Dapatkan API Key dari dashboard <a href="https://platform.openai.com/api-keys" target="_blank" class="text-blue-500 hover:underline">platform.openai.com</a>. Kunci akan disimpan dengan aman.</p>
                </div>
            </div>

            <div class="mt-8 pt-5 border-t border-gray-100 flex justify-end">
                <button type="submit" class="btn-primary px-6 py-2.5">Simpan Pengaturan</button>
            </div>
        </form>
    </div>
</div>
@endsection

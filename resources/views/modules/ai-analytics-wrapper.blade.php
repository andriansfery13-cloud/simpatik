@extends('layouts.app')

@section('title', 'AI Analytics')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto animate-fade-in">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-simpatik-900 via-simpatik-800 to-simpatik-700 rounded-2xl p-8 text-white shadow-lg relative overflow-hidden">
        {{-- Decorative elements --}}
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-1/3 w-32 h-32 bg-white/5 rounded-full translate-y-1/2"></div>
        <svg class="absolute -bottom-8 -right-8 w-40 h-40 text-white opacity-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
        </svg>

        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-3">
                <span class="bg-white/20 backdrop-blur-sm text-xs font-bold px-3 py-1 rounded-full">🤖 AI-POWERED</span>
                <span class="bg-white/10 text-xs px-3 py-1 rounded-full">OpenAI GPT-4o-mini</span>
            </div>
            <h1 class="text-3xl font-bold mb-2">AI Analytics System</h1>
            <p class="text-white/70 max-w-3xl text-sm leading-relaxed">
                Kecerdasan buatan untuk analisis risiko, rekomendasi strategis, penyusunan laporan otomatis, dan asisten virtual — semua berbasis data real-time dari SIMPATIK Kabupaten Bandung.
            </p>
        </div>
    </div>

    {{-- Livewire Component --}}
    @livewire('ai-analytics')
</div>
@endsection

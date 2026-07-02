<div>
    {{-- Tab Navigation --}}
    <div class="bg-white rounded-xl shadow-card border border-gray-100 mb-6 overflow-hidden">
        <div class="flex flex-wrap border-b border-gray-100">
            <button wire:click="$set('activeTab', 'executive')" class="flex items-center gap-2 px-5 py-3.5 text-sm font-medium transition-all duration-200 {{ $activeTab === 'executive' ? 'border-b-2 border-simpatik-600 text-simpatik-700 bg-simpatik-50/50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Executive Dashboard
            </button>
            <button wire:click="$set('activeTab', 'risk')" class="flex items-center gap-2 px-5 py-3.5 text-sm font-medium transition-all duration-200 {{ $activeTab === 'risk' ? 'border-b-2 border-red-500 text-red-600 bg-red-50/50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Risk & Priority
            </button>
            <button wire:click="$set('activeTab', 'report')" class="flex items-center gap-2 px-5 py-3.5 text-sm font-medium transition-all duration-200 {{ $activeTab === 'report' ? 'border-b-2 border-blue-500 text-blue-600 bg-blue-50/50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Smart Report
            </button>
            <button wire:click="$set('activeTab', 'chat')" class="flex items-center gap-2 px-5 py-3.5 text-sm font-medium transition-all duration-200 {{ $activeTab === 'chat' ? 'border-b-2 border-purple-500 text-purple-600 bg-purple-50/50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                Chat Assistant
            </button>
        </div>
    </div>

    {{-- Loading indicator for tab switching --}}
    <div wire:loading wire:target="switchTab" class="w-full text-center py-10">
        <svg class="animate-spin h-8 w-8 text-simpatik-600 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        <p class="mt-2 text-sm text-gray-500">Memuat modul AI...</p>
    </div>

    <div class="animate-fade-in">
        {{-- Tab 1: Executive Dashboard --}}
        @if($activeTab === 'executive')
        <div wire:key="tab-exec">
            {{-- Feature Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white p-4 rounded-xl shadow-card border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-simpatik-100 flex items-center justify-center text-simpatik-700 text-lg">📊</div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-800">Executive Insight</h4>
                            <p class="text-xs text-gray-500">Ringkasan otomatis kondisi pembangunan</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-card border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-700 text-lg">💰</div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-800">Budget Analytics</h4>
                            <p class="text-xs text-gray-500">Analisis serapan & optimalisasi anggaran</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-card border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center text-yellow-700 text-lg">🎯</div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-800">Recommendation Engine</h4>
                            <p class="text-xs text-gray-500">Rekomendasi tindakan untuk Camat/Bupati</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action & Result --}}
            <div class="bg-white rounded-xl shadow-card border border-gray-100 p-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
                    <div>
                        <h3 class="font-bold text-gray-800">Analisis Executive Dashboard</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Mencakup Executive Insight, Budget Analytics, Recommendation Engine & Early Warning</p>
                    </div>
                    <button wire:click="analyzeExecutive" class="btn-primary px-5 py-2.5 flex items-center gap-2 whitespace-nowrap" wire:loading.attr="disabled" wire:target="analyzeExecutive">
                        <svg wire:loading wire:target="analyzeExecutive" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span wire:loading.remove wire:target="analyzeExecutive">🚀 Mulai Analisis</span>
                        <span wire:loading wire:target="analyzeExecutive">AI Sedang Berpikir...</span>
                    </button>
                </div>

                @if($executiveError)
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                        <div class="flex items-start gap-3">
                            <svg class="h-5 w-5 text-red-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <div>
                                <p class="text-sm font-medium text-red-800">Gagal Mendapatkan Analisis</p>
                                <p class="text-sm text-red-700 mt-1">{{ $executiveError }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($executiveResult)
                    <div class="border-t border-gray-100 pt-6 animate-fade-in">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-simpatik-600 to-simpatik-400 flex items-center justify-center text-white shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">Laporan Eksekutif AI</h4>
                                <p class="text-[11px] text-gray-400">Dihasilkan oleh OpenAI GPT-4o-mini • {{ now()->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="ai-markdown-content prose prose-sm max-w-none text-gray-700">
                            {!! Str::markdown($executiveResult) !!}
                        </div>
                    </div>
                @elseif(!$executiveLoading && !$executiveError)
                    <div class="bg-gray-50 rounded-xl border border-dashed border-gray-300 p-10 text-center animate-fade-in">
                        <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <p class="text-sm font-medium text-gray-600">Klik "Mulai Analisis" untuk menghasilkan laporan eksekutif</p>
                        <p class="text-xs text-gray-400 mt-1">Sistem akan menganalisis seluruh data kegiatan dan anggaran</p>
                    </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Tab 2: Risk & Priority --}}
        @if($activeTab === 'risk')
        <div wire:key="tab-risk">
            @livewire('ai-risk-monitor')
        </div>
        @endif

        {{-- Tab 3: Smart Report --}}
        @if($activeTab === 'report')
        <div wire:key="tab-report">
            @livewire('ai-report-generator')
        </div>
        @endif

        {{-- Tab 4: Chat Assistant --}}
        @if($activeTab === 'chat')
        <div wire:key="tab-chat">
            @livewire('ai-chat-assistant')
        </div>
        @endif
    </div>

    {{-- Global markdown styles --}}
    <style>
        .ai-markdown-content h1, .ai-markdown-content h2 { color: #1a202c; font-weight: 700; margin-top: 1.5em; margin-bottom: 0.5em; font-size: 1.15rem; }
        .ai-markdown-content h3 { color: #2d3748; font-weight: 600; margin-top: 1.25em; margin-bottom: 0.4em; font-size: 1rem; }
        .ai-markdown-content p { margin-bottom: 0.75em; line-height: 1.7; }
        .ai-markdown-content ul { list-style-type: disc; padding-left: 1.5em; margin-bottom: 1em; }
        .ai-markdown-content ol { list-style-type: decimal; padding-left: 1.5em; margin-bottom: 1em; }
        .ai-markdown-content li { margin-bottom: 0.25em; }
        .ai-markdown-content strong { color: #1a202c; }
        .ai-markdown-content table { width: 100%; border-collapse: collapse; margin: 1em 0; font-size: 0.85rem; }
        .ai-markdown-content th { background: #f0fdf4; padding: 8px 12px; border: 1px solid #e2e8f0; text-align: left; font-weight: 600; }
        .ai-markdown-content td { padding: 8px 12px; border: 1px solid #e2e8f0; }
        .ai-markdown-content tr:hover { background: #fafafa; }
    </style>
</div>

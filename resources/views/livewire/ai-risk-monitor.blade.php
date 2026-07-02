<div>
    {{-- Feature Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-xl shadow-card border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center text-red-700 text-lg">🔴</div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800">Risk Scoring</h4>
                    <p class="text-xs text-gray-500">Skor risiko per kegiatan</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-card border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center text-orange-700 text-lg">🎯</div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800">Priority Monitoring</h4>
                    <p class="text-xs text-gray-500">Prioritas monitoring mendesak</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-card border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center text-purple-700 text-lg">📈</div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800">Predictive Analytics</h4>
                    <p class="text-xs text-gray-500">Prediksi keterlambatan</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-card border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center text-yellow-700 text-lg">🏆</div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800">Performance Ranking</h4>
                    <p class="text-xs text-gray-500">Ranking kinerja desa</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Action Button --}}
    <div class="bg-white rounded-xl shadow-card border border-gray-100 p-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <div>
                <h3 class="font-bold text-gray-800">Analisis Risiko & Prioritas</h3>
                <p class="text-xs text-gray-500 mt-0.5">Mencakup Risk Scoring, Priority Monitoring, Predictive Analytics & Performance Ranking</p>
            </div>
            <button wire:click="analyzeRisk" class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 whitespace-nowrap" wire:loading.attr="disabled" wire:target="analyzeRisk">
                <svg wire:loading wire:target="analyzeRisk" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span wire:loading.remove wire:target="analyzeRisk">⚡ Analisis Risiko Sekarang</span>
                <span wire:loading wire:target="analyzeRisk">AI Menganalisis Risiko...</span>
            </button>
        </div>

        @if($riskError)
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                <p class="text-sm text-red-700">{{ $riskError }}</p>
            </div>
        @endif

        @if($riskResult)
            <div class="border-t border-gray-100 pt-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-red-600 to-orange-400 flex items-center justify-center text-white shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">Laporan Risiko & Prioritas AI</h4>
                        <p class="text-[11px] text-gray-400">Dihasilkan oleh OpenAI GPT-4o-mini • {{ now()->format('d M Y H:i') }}</p>
                    </div>
                </div>
                <div class="ai-markdown-content prose prose-sm max-w-none text-gray-700">
                    {!! Str::markdown($riskResult) !!}
                </div>
            </div>
        @elseif(!$riskLoading && !$riskError)
            <div class="bg-gray-50 rounded-xl border border-dashed border-gray-300 p-10 text-center">
                <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <p class="text-sm font-medium text-gray-600">Klik "Analisis Risiko Sekarang" untuk memulai</p>
                <p class="text-xs text-gray-400 mt-1">AI akan menilai skor risiko, memprediksi keterlambatan, dan meranking desa</p>
            </div>
        @endif
    </div>
</div>

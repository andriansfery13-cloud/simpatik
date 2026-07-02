<div>
    {{-- Report Configuration --}}
    <div class="bg-white rounded-xl shadow-card border border-gray-100 p-6 mb-6">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            AI Smart Report Generator
        </h3>
        <p class="text-sm text-gray-500 mb-6">Susun laporan resmi pemerintahan secara otomatis lengkap dengan analisis data, identifikasi masalah, dan rekomendasi tindak lanjut.</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <label class="form-label block text-sm font-medium text-gray-700 mb-1">Jenis Laporan</label>
                <select wire:model="reportType" class="form-select block w-full rounded-lg border-gray-300 shadow-sm focus:border-simpatik-500 focus:ring-simpatik-500 text-sm">
                    <option value="bulanan">📅 Laporan Bulanan</option>
                    <option value="triwulan">📊 Laporan Triwulan</option>
                    <option value="semester">📋 Laporan Semester</option>
                    <option value="tahunan">📑 Laporan Tahunan</option>
                </select>
            </div>
            <div>
                <label class="form-label block text-sm font-medium text-gray-700 mb-1">Periode</label>
                <input type="text" wire:model="reportPeriod" class="form-input block w-full rounded-lg border-gray-300 shadow-sm focus:border-simpatik-500 focus:ring-simpatik-500 text-sm" placeholder="Contoh: Juli 2026">
            </div>
            <div class="flex items-end">
                <button wire:click="generateReport" class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200" wire:loading.attr="disabled" wire:target="generateReport">
                    <svg wire:loading wire:target="generateReport" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span wire:loading.remove wire:target="generateReport">📝 Susun Laporan</span>
                    <span wire:loading wire:target="generateReport">AI Menyusun Laporan...</span>
                </button>
            </div>
        </div>
    </div>

    @if($reportError)
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg mb-6">
            <p class="text-sm text-red-700">{{ $reportError }}</p>
        </div>
    @endif

    @if($reportResult)
        <div class="bg-white rounded-xl shadow-card border border-gray-100 p-6 md:p-8">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-600 to-blue-400 flex items-center justify-center text-white shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">Laporan {{ ucfirst($reportType) }} - {{ $reportPeriod }}</h4>
                        <p class="text-[11px] text-gray-400">Dihasilkan oleh AI • {{ now()->format('d M Y H:i') }}</p>
                    </div>
                </div>
                <button onclick="navigator.clipboard.writeText(document.getElementById('report-content').innerText).then(() => alert('Laporan berhasil disalin ke clipboard!'))" class="btn-secondary px-4 py-2 text-xs flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                    Salin Laporan
                </button>
            </div>
            <div id="report-content" class="ai-markdown-content prose prose-sm max-w-none text-gray-700">
                {!! Str::markdown($reportResult) !!}
            </div>
        </div>
    @elseif(!$reportLoading && !$reportError)
        <div class="bg-white rounded-xl shadow-card border border-gray-100 p-6">
            <div class="bg-gray-50 rounded-xl border border-dashed border-gray-300 p-10 text-center">
                <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <p class="text-sm font-medium text-gray-600">Pilih jenis laporan dan periode, lalu klik "Susun Laporan"</p>
                <p class="text-xs text-gray-400 mt-1">AI akan menyusun laporan formal pemerintahan lengkap secara otomatis</p>
            </div>
        </div>
    @endif
</div>

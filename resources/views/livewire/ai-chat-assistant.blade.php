<div class="flex flex-col" style="height: calc(100vh - 320px); min-height: 500px;">
    {{-- Chat Header --}}
    <div class="bg-white rounded-t-xl shadow-card border border-gray-100 p-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-purple-600 to-purple-400 flex items-center justify-center text-white shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            </div>
            <div>
                <h3 class="font-bold text-gray-800 text-sm">AI Chat Assistant SIMPATIK</h3>
                <p class="text-[11px] text-gray-400 flex items-center gap-1">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    Online • Siap membantu analisis data
                </p>
            </div>
        </div>
        <button wire:click="clearChat" class="text-xs text-gray-400 hover:text-red-500 flex items-center gap-1 transition-colors" title="Hapus riwayat percakapan">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            Reset Chat
        </button>
    </div>

    {{-- Messages Area --}}
    <div class="flex-1 bg-gray-50 border-x border-gray-100 overflow-y-auto p-4 space-y-4" id="chat-messages" style="scroll-behavior: smooth;">
        @foreach($messages as $index => $msg)
            @if($msg['role'] === 'assistant')
                <div class="flex gap-3 animate-fade-in">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-tr from-purple-600 to-purple-400 flex items-center justify-center text-white text-xs shadow">
                        🤖
                    </div>
                    <div class="flex-1 bg-white rounded-xl rounded-tl-none p-4 shadow-sm border border-gray-100 max-w-[85%]">
                        <div class="ai-markdown-content prose prose-sm max-w-none text-gray-700 text-sm">
                            {!! Str::markdown($msg['content']) !!}
                        </div>
                    </div>
                </div>
            @else
                <div class="flex gap-3 justify-end animate-fade-in">
                    <div class="bg-simpatik-600 text-white rounded-xl rounded-tr-none p-4 shadow-sm max-w-[85%]">
                        <p class="text-sm">{{ $msg['content'] }}</p>
                    </div>
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-simpatik-700 flex items-center justify-center text-white text-xs shadow">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                </div>
            @endif
        @endforeach

        {{-- Typing indicator --}}
        @if($chatLoading)
            <div class="flex gap-3 animate-fade-in">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-tr from-purple-600 to-purple-400 flex items-center justify-center text-white text-xs shadow">
                    🤖
                </div>
                <div class="bg-white rounded-xl rounded-tl-none px-5 py-3 shadow-sm border border-gray-100">
                    <div class="flex items-center gap-1">
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms;"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms;"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms;"></div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Input Area --}}
    <div class="bg-white rounded-b-xl shadow-card border border-gray-100 p-4">
        {{-- Quick Prompts --}}
        @if(count($messages) <= 1)
        <div class="flex flex-wrap gap-2 mb-3">
            <button wire:click="$set('userMessage', 'Desa mana yang serapan anggarannya paling rendah?')" class="text-xs bg-gray-100 hover:bg-simpatik-50 hover:text-simpatik-700 text-gray-600 px-3 py-1.5 rounded-full transition-colors border border-gray-200 hover:border-simpatik-200">
                💡 Serapan anggaran terendah?
            </button>
            <button wire:click="$set('userMessage', 'Kegiatan apa saja yang statusnya terlambat?')" class="text-xs bg-gray-100 hover:bg-red-50 hover:text-red-700 text-gray-600 px-3 py-1.5 rounded-full transition-colors border border-gray-200 hover:border-red-200">
                ⚠️ Kegiatan terlambat?
            </button>
            <button wire:click="$set('userMessage', 'Berikan ringkasan kinerja pembangunan bulan ini')" class="text-xs bg-gray-100 hover:bg-blue-50 hover:text-blue-700 text-gray-600 px-3 py-1.5 rounded-full transition-colors border border-gray-200 hover:border-blue-200">
                📊 Ringkasan kinerja bulan ini
            </button>
            <button wire:click="$set('userMessage', 'Desa mana yang paling baik kinerjanya?')" class="text-xs bg-gray-100 hover:bg-green-50 hover:text-green-700 text-gray-600 px-3 py-1.5 rounded-full transition-colors border border-gray-200 hover:border-green-200">
                🏆 Desa terbaik?
            </button>
        </div>
        @endif

        <form wire:submit.prevent="sendMessage" class="flex gap-3">
            <input type="text" wire:model="userMessage" class="flex-1 form-input block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 text-sm" placeholder="Ketik pertanyaan Anda di sini..." autocomplete="off" @if($chatLoading) disabled @endif>
            <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-200 disabled:opacity-50" @if($chatLoading) disabled @endif>
                <svg wire:loading.remove wire:target="sendMessage" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                <svg wire:loading wire:target="sendMessage" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </button>
        </form>

        @if($chatError)
            <p class="text-xs text-red-500 mt-2">{{ $chatError }}</p>
        @endif
    </div>
</div>

<script>
    // Auto-scroll to bottom when new messages appear
    document.addEventListener('livewire:updated', () => {
        const chatContainer = document.getElementById('chat-messages');
        if (chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    });
</script>

<div>
    <form wire:submit.prevent="save" class="space-y-6">
        
        {{-- Section 1: Profil Administratif --}}
        <div class="border-b border-gray-100 pb-4 mb-2">
            <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                <span class="w-6 h-6 bg-simpatik-100 rounded-full flex items-center justify-center text-xs text-simpatik-700 font-bold">1</span>
                Profil Administratif
            </h3>
            <p class="text-xs text-gray-500 mt-1 ml-8">Informasi dasar wilayah administratif desa.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label for="kecamatan_id" class="form-label">Kecamatan Induk <span class="text-red-500">*</span></label>
                @if(count($kecamatans) > 1)
                    <select wire:model.blur="kecamatan_id" id="kecamatan_id" class="form-select @error('kecamatan_id') border-red-500 @enderror">
                        <option value="">-- Pilih Kecamatan --</option>
                        @foreach($kecamatans as $kecamatan)
                            <option value="{{ $kecamatan->id }}">{{ $kecamatan->nama }}</option>
                        @endforeach
                    </select>
                @elseif(count($kecamatans) === 1)
                    <input type="text" class="form-input bg-gray-100 cursor-not-allowed" value="{{ $kecamatans[0]->nama }}" disabled>
                @endif
                @error('kecamatan_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="kode" class="form-label">Kode Desa <span class="text-red-500">*</span></label>
                <input type="text" wire:model.blur="kode" id="kode" class="form-input @error('kode') border-red-500 @enderror" placeholder="Contoh: 3204xxxxxxxx">
                @error('kode') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="nama" class="form-label">Nama Desa <span class="text-red-500">*</span></label>
                <input type="text" wire:model.blur="nama" id="nama" class="form-input @error('nama') border-red-500 @enderror" placeholder="Contoh: Margahayu Selatan">
                @error('nama') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Section 2: Data Kepala Desa & Kontak --}}
        <div class="border-b border-gray-100 pb-4 mb-2 mt-8">
            <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                <span class="w-6 h-6 bg-simpatik-100 rounded-full flex items-center justify-center text-xs text-simpatik-700 font-bold">2</span>
                Data Kepala Desa & Kontak
            </h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="kepala_desa" class="form-label">Nama Kepala Desa</label>
                <input type="text" wire:model.blur="kepala_desa" id="kepala_desa" class="form-input" placeholder="Nama lengkap kepala desa">
            </div>
            <div>
                <label for="telepon" class="form-label">Telepon Kantor</label>
                <input type="text" wire:model.blur="telepon" id="telepon" class="form-input" placeholder="Contoh: 022-XXXXXXX">
            </div>
            <div class="md:col-span-2">
                <label for="alamat" class="form-label">Alamat Kantor Desa</label>
                <textarea wire:model.blur="alamat" id="alamat" rows="2" class="form-input" placeholder="Jalan Raya No. X, Desa Y"></textarea>
            </div>
        </div>

        {{-- Section 3: Statistik Wilayah --}}
        <div class="border-b border-gray-100 pb-4 mb-2 mt-8">
            <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                <span class="w-6 h-6 bg-simpatik-100 rounded-full flex items-center justify-center text-xs text-simpatik-700 font-bold">3</span>
                Statistik Wilayah
            </h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="jumlah_penduduk" class="form-label">Jumlah Penduduk (Jiwa)</label>
                <div class="relative">
                    <input type="number" wire:model.blur="jumlah_penduduk" id="jumlah_penduduk" class="form-input pr-12" placeholder="0">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 text-sm">Jiwa</span>
                    </div>
                </div>
            </div>
            <div>
                <label for="luas_wilayah" class="form-label">Luas Wilayah (km²)</label>
                <div class="relative">
                    <input type="number" step="0.01" wire:model.blur="luas_wilayah" id="luas_wilayah" class="form-input pr-12" placeholder="0.00">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 text-sm">km²</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-gray-100 flex justify-between items-center gap-3">
            <div class="text-xs text-gray-500"><span class="text-red-500">*</span> Wajib diisi</div>
            <div class="flex gap-3">
                <a href="{{ route('desa.index') }}" class="btn-secondary px-6 py-2.5">Batal</a>
                <button type="submit" class="btn-primary px-6 py-2.5 flex items-center gap-2" wire:loading.attr="disabled">
                    <svg wire:loading wire:target="save" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="save">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Data Desa' }}</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </div>
    </form>
</div>

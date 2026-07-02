<div>
    <form wire:submit.prevent="save" class="space-y-6">

        {{-- Section 1: Data Utama --}}
        <div class="border-b border-gray-100 pb-4 mb-2">
            <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                <span class="w-6 h-6 bg-simpatik-100 rounded-full flex items-center justify-center text-xs text-simpatik-700 font-bold">1</span>
                Data Utama Kegiatan
            </h3>
            <p class="text-xs text-gray-500 mt-1 ml-8">Informasi dasar mengenai kegiatan yang akan dilaksanakan.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label for="nama_kegiatan" class="form-label">Nama Kegiatan <span class="text-red-500">*</span></label>
                <input type="text" wire:model.blur="nama_kegiatan" id="nama_kegiatan" class="form-input @error('nama_kegiatan') border-red-500 @enderror" placeholder="Contoh: Pembangunan Rabat Beton Jalan RW 03">
                @error('nama_kegiatan') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Kecamatan (only for kabupaten users) --}}
            @if(count($kecamatans) > 0)
            <div>
                <label for="kecamatan_id" class="form-label">Kecamatan <span class="text-red-500">*</span></label>
                <select wire:model.live="kecamatan_id" id="kecamatan_id" class="form-select">
                    <option value="">-- Pilih Kecamatan --</option>
                    @foreach($kecamatans as $kec)
                        <option value="{{ $kec->id }}">{{ $kec->nama }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div>
                <label for="desa_id" class="form-label">Desa Lokasi <span class="text-red-500">*</span></label>
                <select wire:model.blur="desa_id" id="desa_id" class="form-select @error('desa_id') border-red-500 @enderror">
                    <option value="">-- Pilih Desa --</option>
                    @foreach($desas as $desa)
                        <option value="{{ $desa->id }}">{{ $desa->nama }}</option>
                    @endforeach
                </select>
                @error('desa_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                @if($kecamatan_id && count($desas) === 0)
                    <p class="text-xs text-yellow-600 mt-1">Belum ada data desa di kecamatan ini.</p>
                @endif
            </div>

            <div>
                <label for="lokasi" class="form-label">Detail Lokasi (Alamat/RT/RW)</label>
                <input type="text" wire:model.blur="lokasi" id="lokasi" class="form-input" placeholder="Contoh: Kp. Sukamaju RT 01/RW 03">
            </div>

            <div class="md:col-span-2">
                <label for="deskripsi" class="form-label">Deskripsi Singkat</label>
                <textarea wire:model.blur="deskripsi" id="deskripsi" rows="3" class="form-input" placeholder="Jelaskan secara singkat ruang lingkup pekerjaan ini..."></textarea>
            </div>
        </div>

        {{-- Section 2: Keuangan & Waktu --}}
        <div class="border-b border-gray-100 pb-4 mb-2 mt-8">
            <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                <span class="w-6 h-6 bg-simpatik-100 rounded-full flex items-center justify-center text-xs text-simpatik-700 font-bold">2</span>
                Keuangan & Waktu Pelaksanaan
            </h3>
            <p class="text-xs text-gray-500 mt-1 ml-8">Alokasi pagu anggaran dan estimasi waktu penyelesaian.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="sumber_dana_id" class="form-label">Sumber Dana <span class="text-red-500">*</span></label>
                <select wire:model.blur="sumber_dana_id" id="sumber_dana_id" class="form-select @error('sumber_dana_id') border-red-500 @enderror">
                    <option value="">-- Pilih Sumber Dana --</option>
                    @foreach($sumberDanas as $sd)
                        <option value="{{ $sd->id }}">{{ $sd->kode }} - {{ $sd->nama }}</option>
                    @endforeach
                </select>
                @error('sumber_dana_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="periode_anggaran" class="form-label">Periode Anggaran</label>
                <select wire:model.blur="periode_anggaran" id="periode_anggaran" class="form-select @error('periode_anggaran') border-red-500 @enderror">
                    <option value="">-- Pilih Periode (Opsional) --</option>
                    <option value="Semester 1">Semester 1</option>
                    <option value="Semester 2">Semester 2</option>
                    <option value="Semester 3">Semester 3</option>
                </select>
                @error('periode_anggaran') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="pagu_anggaran" class="form-label">Pagu Anggaran (Rp) <span class="text-red-500">*</span></label>
                <input type="number" wire:model.blur="pagu_anggaran" id="pagu_anggaran" class="form-input @error('pagu_anggaran') border-red-500 @enderror" min="0" placeholder="Contoh: 150000000">
                @error('pagu_anggaran') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            @if($isEdit)
            <div>
                <label for="realisasi_anggaran" class="form-label">Realisasi Anggaran (Rp)</label>
                <input type="number" wire:model.blur="realisasi_anggaran" id="realisasi_anggaran" class="form-input" min="0">
            </div>
            <div>
                <label for="progres_fisik" class="form-label">Progres Fisik (%)</label>
                <input type="number" wire:model.blur="progres_fisik" id="progres_fisik" class="form-input" min="0" max="100">
                @error('progres_fisik') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            @endif

            <div>
                <label for="tanggal_mulai" class="form-label">Estimasi Tanggal Mulai</label>
                <input type="date" wire:model.blur="tanggal_mulai" id="tanggal_mulai" class="form-input">
            </div>

            <div>
                <label for="tanggal_selesai" class="form-label">Estimasi Tanggal Selesai</label>
                <input type="date" wire:model.blur="tanggal_selesai" id="tanggal_selesai" class="form-input @error('tanggal_selesai') border-red-500 @enderror">
                @error('tanggal_selesai') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            @if($isEdit)
            <div>
                <label for="status" class="form-label">Status <span class="text-red-500">*</span></label>
                <select wire:model.blur="status" id="status" class="form-select">
                    <option value="belum_mulai">Belum Mulai</option>
                    <option value="berjalan">Berjalan</option>
                    <option value="selesai">Selesai</option>
                    <option value="terlambat">Terlambat</option>
                </select>
            </div>
            @endif
        </div>

        {{-- Section 3: Pelaksana --}}
        <div class="border-b border-gray-100 pb-4 mb-2 mt-8">
            <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                <span class="w-6 h-6 bg-simpatik-100 rounded-full flex items-center justify-center text-xs text-simpatik-700 font-bold">3</span>
                Pelaksana Kegiatan
            </h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="pelaksana" class="form-label">Tim Pelaksana / Pihak Ketiga</label>
                <input type="text" wire:model.blur="pelaksana" id="pelaksana" class="form-input" placeholder="Contoh: TPK Desa Sukamaju / CV. X">
            </div>
            <div>
                <label for="penanggung_jawab" class="form-label">Penanggung Jawab</label>
                <input type="text" wire:model.blur="penanggung_jawab" id="penanggung_jawab" class="form-input" placeholder="Nama Kepala Desa / Ketua TPK">
            </div>

            @if($isEdit)
            <div class="md:col-span-2">
                <label for="catatan" class="form-label">Catatan</label>
                <textarea wire:model.blur="catatan" id="catatan" rows="3" class="form-input" placeholder="Catatan tambahan terkait kegiatan..."></textarea>
            </div>
            @endif
        </div>

        @if(!$isEdit)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex gap-3 text-blue-700 text-sm">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p>Kegiatan yang baru ditambahkan akan memiliki status <strong>Belum Mulai</strong> dan progres fisik <strong>0%</strong>. Anda dapat memperbarui progres dan lokasi titik koordinat melalui menu Detail Kegiatan nanti.</p>
        </div>
        @endif

        {{-- Actions --}}
        <div class="flex justify-between items-center pt-6 border-t border-gray-100">
            <div class="text-xs text-gray-500"><span class="text-red-500">*</span> Wajib diisi</div>
            <div class="flex gap-3">
                <a href="{{ route('kegiatan.index') }}" class="btn-secondary py-2.5 px-6">Batal</a>
                <button type="submit" class="btn-primary py-2.5 px-6 flex items-center gap-2" wire:loading.attr="disabled">
                    <svg wire:loading wire:target="save" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="save">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Kegiatan' }}</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </div>
    </form>
</div>

<div>
    <form wire:submit="save" class="space-y-6">

        {{-- Informasi Dasar --}}
        <div class="border-b border-gray-100 pb-4 mb-2">
            <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                <span class="w-6 h-6 bg-simpatik-100 rounded-full flex items-center justify-center text-xs text-simpatik-700 font-bold">1</span>
                Informasi Akun
            </h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Nama --}}
            <div>
                <label for="name" class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" wire:model.blur="name" id="name" class="form-input @error('name') border-red-500 @enderror" placeholder="Masukkan nama lengkap">
                @error('name') <p class="text-xs text-red-500 mt-1 flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="form-label">Email <span class="text-red-500">*</span></label>
                <input type="email" wire:model.blur="email" id="email" class="form-input @error('email') border-red-500 @enderror" placeholder="contoh@simpatik.go.id">
                @error('email') <p class="text-xs text-red-500 mt-1 flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p> @enderror
            </div>
            
            {{-- Username --}}
            <div>
                <label for="username" class="form-label">Username <span class="text-red-500">*</span></label>
                <input type="text" wire:model.blur="username" id="username" class="form-input @error('username') border-red-500 @enderror" placeholder="Masukkan username">
                @error('username') <p class="text-xs text-red-500 mt-1 flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p> @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="form-label">
                    {{ $isEdit ? 'Password Baru (Kosongkan jika tidak diubah)' : 'Password' }}
                    @if(!$isEdit) <span class="text-red-500">*</span> @endif
                </label>
                <input type="password" wire:model.blur="password" id="password" class="form-input @error('password') border-red-500 @enderror" placeholder="Minimal 8 karakter">
                @error('password') <p class="text-xs text-red-500 mt-1 flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p> @enderror
            </div>

            {{-- Password Confirmation --}}
            <div>
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input type="password" wire:model.blur="password_confirmation" id="password_confirmation" class="form-input" placeholder="Ulangi password">
            </div>
        </div>

        {{-- Role & Tenant --}}
        <div class="border-b border-gray-100 pb-4 mb-2 mt-8">
            <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                <span class="w-6 h-6 bg-simpatik-100 rounded-full flex items-center justify-center text-xs text-simpatik-700 font-bold">2</span>
                Peran & Wilayah Tugas
            </h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Role --}}
            <div>
                <label for="role" class="form-label">Peran (Role) <span class="text-red-500">*</span></label>
                <select wire:model.live="role" id="role" class="form-select @error('role') border-red-500 @enderror">
                    <option value="">-- Pilih Peran --</option>
                    @foreach($availableRoles as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('role') <p class="text-xs text-red-500 mt-1 flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p> @enderror

                {{-- Role indicator badge --}}
                @if($role)
                    <div class="mt-2">
                        @if(in_array($role, ['admin', 'bupati', 'sekda', 'dpmd', 'bappeda', 'inspektorat']))
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 border border-blue-200 text-blue-700 text-[10px] font-bold rounded-full">🏛 Level Kabupaten</span>
                        @elseif(in_array($role, ['camat', 'sekcam', 'kasi_pmd', 'operator_kecamatan']))
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-simpatik-50 border border-simpatik-200 text-simpatik-700 text-[10px] font-bold rounded-full">🏢 Level Kecamatan</span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-50 border border-green-200 text-green-700 text-[10px] font-bold rounded-full">🏘 Level Desa</span>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Kecamatan (shown for kecamatan/desa level roles) --}}
            @if(in_array($role, ['camat', 'sekcam', 'kasi_pmd', 'operator_kecamatan', 'kepala_desa', 'sekretaris_desa', 'operator_desa']))
                <div>
                    <label for="kecamatan_id" class="form-label">Kecamatan <span class="text-red-500">*</span></label>
                    @if(count($kecamatans) > 0)
                        <select wire:model.live="kecamatan_id" id="kecamatan_id" class="form-select @error('kecamatan_id') border-red-500 @enderror">
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach($kecamatans as $kec)
                                <option value="{{ $kec->id }}">{{ $kec->nama }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="text" class="form-input bg-gray-100 cursor-not-allowed" value="{{ auth()->user()->kecamatan->nama ?? 'N/A' }}" disabled>
                    @endif
                    @error('kecamatan_id') <p class="text-xs text-red-500 mt-1 flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p> @enderror
                </div>
            @endif

            {{-- Desa (shown for desa level roles) --}}
            @if(in_array($role, ['kepala_desa', 'sekretaris_desa', 'operator_desa']))
                <div>
                    <label for="desa_id" class="form-label">Desa <span class="text-red-500">*</span></label>
                    <select wire:model.blur="desa_id" id="desa_id" class="form-select @error('desa_id') border-red-500 @enderror">
                        <option value="">-- Pilih Desa --</option>
                        @foreach($desas as $desa)
                            <option value="{{ $desa->id }}">{{ $desa->nama }}</option>
                        @endforeach
                    </select>
                    @error('desa_id') <p class="text-xs text-red-500 mt-1 flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p> @enderror

                    @if($kecamatan_id && count($desas) === 0)
                        <p class="text-xs text-yellow-600 mt-1 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            Belum ada data desa di kecamatan ini.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Informasi Tambahan --}}
        <div class="border-b border-gray-100 pb-4 mb-2 mt-8">
            <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                <span class="w-6 h-6 bg-simpatik-100 rounded-full flex items-center justify-center text-xs text-simpatik-700 font-bold">3</span>
                Informasi Tambahan
            </h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label for="nip" class="form-label">NIP</label>
                <input type="text" wire:model.blur="nip" id="nip" class="form-input" placeholder="197001012000011001">
            </div>
            <div>
                <label for="jabatan" class="form-label">Jabatan Spesifik</label>
                <input type="text" wire:model.blur="jabatan" id="jabatan" class="form-input" placeholder="Cth: Kepala Seksi PMD">
            </div>
            <div>
                <label for="telepon" class="form-label">Nomor Telepon</label>
                <input type="text" wire:model.blur="telepon" id="telepon" class="form-input" placeholder="08xxxxxxxxxx">
            </div>
        </div>

        <div class="flex items-center gap-3 mt-4 p-3 bg-gray-50 rounded-lg border border-gray-100">
            <input type="checkbox" wire:model="is_active" id="is_active" class="text-simpatik-600 rounded border-gray-300 focus:ring-simpatik-500 h-4 w-4">
            <label for="is_active" class="text-sm font-medium text-gray-700">
                Akun Aktif
                <span class="text-xs text-gray-500 block">Akun yang tidak aktif tidak dapat login ke sistem.</span>
            </label>
        </div>

        {{-- Actions --}}
        <div class="flex justify-between items-center pt-6 border-t border-gray-100">
            <div class="text-xs text-gray-500">
                <span class="text-red-500">*</span> Wajib diisi
            </div>
            <div class="flex gap-3">
                <a href="{{ route('users.index') }}" class="btn-secondary py-2.5 px-6">Batal</a>
                <button type="submit" class="btn-primary py-2.5 px-6 flex items-center gap-2" wire:loading.attr="disabled">
                    <svg wire:loading wire:target="save" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="save">{{ $isEdit ? 'Simpan Perubahan' : 'Tambah Pengguna' }}</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </div>
    </form>
</div>

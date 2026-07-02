@extends('layouts.app')

@section('title', 'Edit Alokasi Anggaran')

@section('content')
<div class="space-y-6 animate-fade-in max-w-3xl mx-auto">

    <div class="bg-white p-5 rounded-xl shadow-card border border-gray-100 flex items-center gap-4">
        <a href="{{ route('anggaran.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Alokasi Anggaran</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $anggaran->desa->nama }} — {{ $anggaran->sumberDana->nama }} ({{ $anggaran->tahun_anggaran }})</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden">
        <form action="{{ route('anggaran.update', $anggaran) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="form-label block text-sm font-medium text-gray-700 mb-1">Desa Tujuan <span class="text-red-500">*</span></label>
                    <select name="desa_id" class="form-select w-full" required>
                        <option value="">-- Pilih Desa --</option>
                        @foreach($desas as $desa)
                            <option value="{{ $desa->id }}" {{ old('desa_id', $anggaran->desa_id) == $desa->id ? 'selected' : '' }}>
                                {{ $desa->nama }} {{ $desa->kecamatan ? '(Kec. ' . $desa->kecamatan->nama . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('desa_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label block text-sm font-medium text-gray-700 mb-1">Sumber Dana <span class="text-red-500">*</span></label>
                    <select name="sumber_dana_id" class="form-select w-full" required>
                        @foreach($sumberDanas as $sd)
                            <option value="{{ $sd->id }}" {{ old('sumber_dana_id', $anggaran->sumber_dana_id) == $sd->id ? 'selected' : '' }}>
                                {{ $sd->kode }} - {{ $sd->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('sumber_dana_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label block text-sm font-medium text-gray-700 mb-1">Tahun Anggaran <span class="text-red-500">*</span></label>
                    <input type="number" name="tahun_anggaran" class="form-input w-full" value="{{ old('tahun_anggaran', $anggaran->tahun_anggaran) }}" min="2020" max="2030" required>
                    @error('tahun_anggaran') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label block text-sm font-medium text-gray-700 mb-1">Pagu Anggaran (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="pagu" class="form-input w-full" value="{{ old('pagu', $anggaran->pagu) }}" min="0" required>
                    @error('pagu') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label block text-sm font-medium text-gray-700 mb-1">Realisasi Anggaran (Rp)</label>
                    <input type="number" name="realisasi" class="form-input w-full" value="{{ old('realisasi', $anggaran->realisasi) }}" min="0">
                    @error('realisasi') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="form-label block text-sm font-medium text-gray-700 mb-2">Status Earmark <span class="text-red-500">*</span></label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer hover:bg-gray-50 flex-1">
                            <input type="radio" name="status_earmark" value="earmarked" class="text-simpatik-600 focus:ring-simpatik-500" {{ old('status_earmark', $anggaran->status_earmark) == 'earmarked' ? 'checked' : '' }} required>
                            <div>
                                <p class="font-medium text-gray-800 text-sm">Earmarked</p>
                                <p class="text-[10px] text-gray-500">Sudah ditentukan peruntukannya.</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer hover:bg-gray-50 flex-1">
                            <input type="radio" name="status_earmark" value="non-earmarked" class="text-simpatik-600 focus:ring-simpatik-500" {{ old('status_earmark', $anggaran->status_earmark) == 'non-earmarked' ? 'checked' : '' }}>
                            <div>
                                <p class="font-medium text-gray-800 text-sm">Non-Earmarked</p>
                                <p class="text-[10px] text-gray-500">Block grant diserahkan ke desa.</p>
                            </div>
                        </label>
                    </div>
                    @error('status_earmark') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="form-label block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                    <textarea name="keterangan" rows="3" class="form-input w-full">{{ old('keterangan', $anggaran->keterangan) }}</textarea>
                    @error('keterangan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('anggaran.index') }}" class="btn-secondary px-6 py-2">Batal</a>
                <button type="submit" class="btn-primary px-6 py-2">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

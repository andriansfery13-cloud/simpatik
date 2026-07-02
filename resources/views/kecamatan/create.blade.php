@extends('layouts.app')

@section('title', 'Tambah Data Kecamatan')

@section('content')
<div class="space-y-6 animate-fade-in max-w-2xl mx-auto">

    <div class="bg-white p-5 rounded-xl shadow-card border border-gray-100 flex items-center gap-4">
        <a href="{{ route('kecamatan.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tambah Data Kecamatan</h1>
            <p class="text-sm text-gray-500 mt-1">Registrasi kecamatan baru di sistem SIMPATIK.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden">
        <form action="{{ route('kecamatan.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label block text-sm font-medium text-gray-700 mb-1">Kode Kecamatan <span class="text-red-500">*</span></label>
                    <input type="text" name="kode" class="form-input w-full" value="{{ old('kode') }}" required>
                    @error('kode') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label block text-sm font-medium text-gray-700 mb-1">Nama Kecamatan <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" class="form-input w-full" value="{{ old('nama') }}" required>
                    @error('nama') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="form-label block text-sm font-medium text-gray-700 mb-1">Nama Camat</label>
                    <input type="text" name="camat" class="form-input w-full" value="{{ old('camat') }}">
                    @error('camat') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="form-label block text-sm font-medium text-gray-700 mb-1">Telepon Kantor</label>
                    <input type="text" name="telepon" class="form-input w-full" value="{{ old('telepon') }}">
                    @error('telepon') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="form-label block text-sm font-medium text-gray-700 mb-1">Alamat Kantor</label>
                <textarea name="alamat" rows="2" class="form-input w-full">{{ old('alamat') }}</textarea>
                @error('alamat') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('kecamatan.index') }}" class="btn-secondary px-6 py-2">Batal</a>
                <button type="submit" class="btn-primary px-6 py-2">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="space-y-6 animate-fade-in" x-data="{ showImportModal: false }">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-5 rounded-xl shadow-card border border-gray-100">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-simpatik-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Manajemen Pengguna
            </h1>
            <p class="text-sm text-gray-500 mt-1">Kelola daftar pengguna sistem dan hak akses (role) multi-tenant.</p>
        </div>
        <div class="flex gap-2">
            <button @click="showImportModal = true" class="btn-secondary py-2 px-4 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Import
            </button>
            <a href="{{ route('users.export') }}" class="btn-secondary py-2 px-4 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export
            </a>
            <a href="{{ route('users.create') }}" class="btn-primary py-2 px-4 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Pengguna
            </a>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-gray-800">Daftar Pengguna Sistem</h3>
            <div class="relative">
                <input type="text" placeholder="Cari pengguna..." class="pl-8 pr-4 py-1.5 border border-gray-200 rounded-lg text-sm focus:ring-simpatik-500 focus:border-simpatik-500">
                <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th>Nama / NIP</th>
                        <th>Kontak</th>
                        <th>Peran (Role)</th>
                        <th>Tenant Wilayah</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($users as $u)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td>
                            <div class="flex items-center gap-3 py-2">
                                <div class="w-8 h-8 rounded-full bg-simpatik-100 text-simpatik-700 flex items-center justify-center font-bold text-xs shrink-0">
                                    {{ substr($u->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800">{{ $u->name }}</p>
                                    <p class="text-[10px] text-gray-500">{{ $u->nip ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p class="text-gray-700 font-medium">{{ $u->email }}</p>
                            <p class="text-[10px] text-gray-500">{{ $u->telepon ?? '-' }}</p>
                        </td>
                        <td>
                            <span class="px-2 py-1 bg-gray-100 border border-gray-200 rounded text-xs font-medium text-gray-600">
                                {{ $u->role_label }}
                            </span>
                        </td>
                        <td>
                            @if($u->isKabupaten())
                                <span class="text-xs font-bold text-blue-600 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    Kabupaten Bandung
                                </span>
                            @elseif($u->isKecamatan())
                                <span class="text-xs font-bold text-simpatik-600 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                    {{ $u->kecamatan->nama ?? 'Unknown' }}
                                </span>
                            @else
                                <span class="text-xs font-bold text-green-600 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                    {{ $u->desa->nama ?? 'Unknown' }}
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($u->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-error">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('users.edit', $u->id) }}" class="p-1.5 bg-yellow-50 text-yellow-600 rounded hover:bg-yellow-100" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                @if(auth()->id() !== $u->id)
                                <form action="{{ route('users.impersonate', $u->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin login sebagai {{ $u->name }}?');">
                                    @csrf
                                    <button type="submit" class="p-1.5 bg-green-50 text-green-600 rounded hover:bg-green-100" title="Login sebagai pengguna ini">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                                    </button>
                                </form>
                                <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 bg-red-50 text-red-600 rounded hover:bg-red-100" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500 text-sm">Tidak ada data pengguna yang sesuai.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    {{-- Import Modal --}}
    <div x-show="showImportModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showImportModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" @click="showImportModal = false" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showImportModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Import Data Pengguna</h3>
                                <div class="mt-4 space-y-4">
                                    <div class="bg-blue-50 border border-blue-200 text-blue-700 p-3 rounded-lg text-sm flex justify-between items-center">
                                        <span>Gunakan template standar. ID Wilayah disesuaikan otomatis.</span>
                                        <a href="{{ route('users.template') }}" class="font-bold underline hover:text-blue-900">Download Template</a>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">File Excel (.xlsx, .xls, .csv) <span class="text-red-500">*</span></label>
                                        <input type="file" name="file_excel" accept=".xlsx,.xls,.csv" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">Import Data</button>
                        <button type="button" @click="showImportModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

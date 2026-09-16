@extends('layouts.app')

@section('title', 'Buat Daftar Tugas Baru')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <!-- Page Header -->
    <div class="border-b border-[#232736] pb-6">
        <div class="flex items-center gap-2 text-xs font-mono text-zinc-500 mb-1">
            <a href="{{ route('task-lists.index') }}" class="hover:text-zinc-300 transition">DAFTAR TUGAS</a>
            <span>/</span>
            <span class="text-violet-400">BUAT BARU</span>
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-zinc-100 font-sans">Buat Daftar Tugas Baru</h1>
        <p class="text-xs text-zinc-400 mt-1">Isi informasi daftar tugas yang ingin dibuat.</p>
    </div>

    <!-- Create Form -->
    <form method="POST" action="{{ route('task-lists.store') }}" class="space-y-5">
        @csrf

        <!-- Name Field -->
        <div>
            <label for="name" class="block text-xs font-mono text-zinc-300 mb-1.5">
                NAMA DAFTAR TUGAS <span class="text-rose-400">*</span>
            </label>
            <input type="text" 
                   id="name" 
                   name="name" 
                   value="{{ old('name') }}"
                   required 
                   placeholder="Contoh: Project Website / Tugas Kuliah"
                   class="w-full px-3.5 py-2.5 rounded-md bg-[#0C0D12] border border-[#2B3042] text-zinc-100 text-sm focus:outline-none focus:border-violet-500 font-mono transition" />
            @if($errors->first('name'))
                <p class="text-xs text-rose-400 mt-1">{{ $errors->first('name') }}</p>
            @endif
        </div>

        <!-- Description Field -->
        <div>
            <label for="description" class="block text-xs font-mono text-zinc-300 mb-1.5">
                DESKRIPSI PROYEK (OPSIONAL)
            </label>
            <textarea id="description" 
                      name="description" 
                      rows="4"
                      placeholder="Tuliskan tujuan atau cakupan tugas ini..."
                      class="w-full px-3.5 py-2.5 rounded-md bg-[#0C0D12] border border-[#2B3042] text-zinc-100 text-sm focus:outline-none focus:border-violet-500 font-sans transition">{{ old('description') }}</textarea>
            @if($errors->first('description'))
                <p class="text-xs text-rose-400 mt-1">{{ $errors->first('description') }}</p>
            @endif
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#232736]">
            <a href="{{ route('task-lists.index') }}" 
               class="px-4 py-2 rounded-md border border-[#2E3347] bg-[#161823] text-zinc-400 hover:text-zinc-200 text-xs font-mono transition">
                Batal
            </a>
            <button type="submit" 
                    class="px-4 py-2 rounded-md bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white text-xs font-mono font-semibold uppercase tracking-wider transition border border-violet-400/30">
                Buat Daftar
            </button>
        </div>
    </form>
</div>
@endsection

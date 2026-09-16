{{-- Modal Konfirmasi Hapus Daftar Tugas --}}
{{-- Digunakan bersama Alpine.js: x-data="{ deleteModalOpen: false, deleteListId: null, deleteListName: '' }" --}}

<div x-show="deleteModalOpen"
     style="display: none;"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    <div @click.away="deleteModalOpen = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="w-full max-w-md rounded-lg border border-rose-800/40 bg-[#12141C] p-6 shadow-2xl space-y-4">

        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-[#232736] pb-3">
            <h3 class="text-sm font-bold font-mono uppercase tracking-wider text-rose-300 flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                Konfirmasi Penghapusan
            </h3>
            <button @click="deleteModalOpen = false" class="text-zinc-500 hover:text-zinc-300 text-lg leading-none">&times;</button>
        </div>

        {{-- Warning Body --}}
        <div class="space-y-3">
            <p class="text-sm text-zinc-300 leading-relaxed">
                Anda akan menghapus daftar tugas: 
                <strong class="text-zinc-100 font-semibold" x-text="deleteListName"></strong>
            </p>

            <div class="px-3 py-2.5 rounded-md bg-rose-950/30 border border-rose-800/40">
                <p class="text-xs text-rose-300/90 leading-relaxed">
                    Menghapus daftar ini akan menghapus seluruh tugas dan keanggotaan
                    di dalamnya secara permanen. Tindakan ini tidak dapat dibatalkan. Lanjutkan?
                </p>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center justify-end gap-3 pt-3 border-t border-[#232736]">
            <button type="button"
                    @click="deleteModalOpen = false"
                    class="px-4 py-2 rounded-md border border-[#2E3347] bg-[#161823] text-zinc-400 hover:text-zinc-200 text-xs font-mono transition">
                Batal
            </button>

            <form :action="'{{ url('task-lists') }}/' + deleteListId" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 rounded-md bg-rose-600 hover:bg-rose-500 text-white text-xs font-mono font-semibold uppercase tracking-wider transition border border-rose-400/30">
                    Ya, Hapus Permanen
                </button>
            </form>
        </div>
    </div>
</div>

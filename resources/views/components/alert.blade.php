{{-- Komponen Alert Reusable --}}
{{-- Menampilkan: session('success'), session('error'), $errors validasi, dan pesan 403 --}}

@props(['status' => null])

{{-- Flash Success --}}
@if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="mb-4 px-4 py-3 rounded-md bg-emerald-950/40 border border-emerald-800/60 text-emerald-300 text-xs flex items-center justify-between">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-400 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        <button @click="show = false" class="text-emerald-500 hover:text-emerald-300 ml-4">&times;</button>
    </div>
@endif

{{-- Flash Error --}}
@if(session('error'))
    <div x-data="{ show: true }" x-show="show"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="mb-4 px-4 py-3 rounded-md bg-rose-950/40 border border-rose-800/60 text-rose-300 text-xs flex items-center justify-between">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-rose-400 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
        <button @click="show = false" class="text-rose-500 hover:text-rose-300 ml-4">&times;</button>
    </div>
@endif

{{-- HTTP 403 Forbidden --}}
@if(isset($status) && $status == 403)
    <div class="mb-4 px-4 py-3 rounded-md bg-amber-950/40 border border-amber-800/60 text-amber-300 text-xs flex items-center gap-2">
        <svg class="w-4 h-4 text-amber-400 shrink-0" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
        </svg>
        <span>Permintaan ditolak. Anda tidak berwenang melakukan aksi ini.</span>
    </div>
@endif

{{-- Validation Errors --}}
@if($errors->any())
    <div class="mb-4 px-4 py-3 rounded-md bg-rose-950/40 border border-rose-800/60 text-rose-300 text-xs">
        <p class="font-semibold mb-1">Terdapat kesalahan pada isian form:</p>
        <ul class="list-disc list-inside space-y-0.5 text-zinc-400">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

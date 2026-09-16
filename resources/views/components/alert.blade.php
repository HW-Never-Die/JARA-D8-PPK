{{-- Alert Component: validation errors, session flash, 403 status --}}

@if(isset($status) && $status == 403)
    <div class="mb-4 px-4 py-3 rounded-md bg-rose-950/40 border border-rose-800/60 text-rose-300 text-xs flex items-center gap-2">
        <svg class="w-4 h-4 text-rose-400 shrink-0" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 008.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
        </svg>
        <span>Permintaan ditolak. Anda tidak berwenang melakukan aksi ini.</span>
    </div>
@endif

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

@if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
         class="mb-4 px-4 py-3 rounded-md bg-emerald-950/40 border border-emerald-800/60 text-emerald-300 text-xs flex items-center justify-between transition">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-400 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        <button @click="show = false" class="text-emerald-500 hover:text-emerald-300">&times;</button>
    </div>
@endif

@if(session('error'))
    <div x-data="{ show: true }" x-show="show" 
         class="mb-4 px-4 py-3 rounded-md bg-rose-950/40 border border-rose-800/60 text-rose-300 text-xs flex items-center justify-between transition">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-rose-400 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
        <button @click="show = false" class="text-rose-500 hover:text-rose-300">&times;</button>
    </div>
@endif

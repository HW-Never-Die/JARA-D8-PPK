<div x-data="{
    isDark: document.documentElement.classList.contains('dark') || !document.documentElement.classList.contains('light'),
    toggle() {
        this.isDark = !this.isDark;
        if (this.isDark) {
            document.documentElement.classList.add('dark');
            document.documentElement.classList.remove('light');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            document.documentElement.classList.add('light');
            localStorage.setItem('theme', 'light');
        }
    }
}" class="inline-flex items-center">
    <button type="button" 
            @click="toggle()" 
            :title="isDark ? 'Ganti ke Mode Terang (Light Mode)' : 'Ganti ke Mode Gelap (Dark Mode)'"
            class="p-2 rounded-md border border-[#232736] bg-[#12141C] text-zinc-400 hover:text-zinc-100 transition flex items-center justify-center cursor-pointer shadow-sm">
        <!-- Sun icon (Active when dark, switches to light) -->
        <svg x-show="isDark" class="w-4 h-4 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="5"/>
            <path d="M12 1v2m0 18v2M4.22 4.22l1.42 1.42m12.72 12.72l1.42 1.42M1 12h2m18 0h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
        </svg>
        <!-- Moon icon (Active when light, switches to dark) -->
        <svg x-show="!isDark" style="display: none;" class="w-4 h-4 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
        </svg>
    </button>
</div>

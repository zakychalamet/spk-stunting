@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="flex items-center justify-between sm:justify-end gap-2">
        {{-- Mobile view: Previous / Next simple buttons --}}
        <div class="flex items-center justify-between gap-2 w-full sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-400 text-xs font-medium cursor-not-allowed">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Sebelumnya</span>
                </span>
            @else
                <button type="button" 
                        wire:click="previousPage('{{ $paginator->getPageName() }}')" 
                        wire:loading.attr="disabled" 
                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-300 text-xs font-semibold shadow-2xs transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Sebelumnya</span>
                </button>
            @endif

            <span class="text-xs text-slate-500 font-medium">
                {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </span>

            @if ($paginator->hasMorePages())
                <button type="button" 
                        wire:click="nextPage('{{ $paginator->getPageName() }}')" 
                        wire:loading.attr="disabled" 
                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-300 text-xs font-semibold shadow-2xs transition">
                    <span>Selanjutnya</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            @else
                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-400 text-xs font-medium cursor-not-allowed">
                    <span>Selanjutnya</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif
        </div>

        {{-- Desktop view: Numbered buttons --}}
        <div class="hidden sm:inline-flex items-center gap-1.5">
            {{-- Tombol Sebelumnya (Previous) --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 bg-slate-50 text-slate-300 cursor-not-allowed text-xs transition" aria-disabled="true" title="Halaman Sebelumnya">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </span>
            @else
                <button type="button" 
                        wire:click="previousPage('{{ $paginator->getPageName() }}')" 
                        wire:loading.attr="disabled" 
                        rel="prev" 
                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-300 shadow-2xs transition text-xs font-semibold cursor-pointer" 
                        title="Halaman Sebelumnya">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
            @endif

            {{-- Angka Halaman & Separator --}}
            @foreach ($elements as $element)
                {{-- Separator titik-titik (...) --}}
                @if (is_string($element))
                    <span class="inline-flex items-center justify-center w-8 h-8 text-slate-400 text-xs font-bold select-none">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Angka Halaman --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-700 text-white font-bold text-xs shadow-xs border border-emerald-700 select-none" aria-current="page">
                                {{ $page }}
                            </span>
                        @else
                            <button type="button" 
                                    wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" 
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 hover:border-emerald-300 shadow-2xs transition text-xs font-semibold cursor-pointer">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Tombol Selanjutnya (Next) --}}
            @if ($paginator->hasMorePages())
                <button type="button" 
                        wire:click="nextPage('{{ $paginator->getPageName() }}')" 
                        wire:loading.attr="disabled" 
                        rel="next" 
                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-300 shadow-2xs transition text-xs font-semibold cursor-pointer" 
                        title="Halaman Selanjutnya">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            @else
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 bg-slate-50 text-slate-300 cursor-not-allowed text-xs transition" aria-disabled="true" title="Halaman Selanjutnya">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif
        </div>
    </nav>
@endif

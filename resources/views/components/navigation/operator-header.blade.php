<header {{ $attributes->class(['fixed top-0 left-64 right-0 h-16 bg-surface-container-lowest/90 backdrop-blur-xl z-40 px-8 flex items-center justify-between shadow-[0_1px_8px_rgba(0,0,0,0.04)] operator-header']) }}>
 <div class="flex items-center gap-3">
  <button aria-controls="operator-sidebar" aria-expanded="false" aria-label="Buka menu" class="lg:hidden p-2 rounded-lg hover:bg-surface-container" data-toggle-sidebar="" type="button">
   <span class="material-symbols-outlined">
    menu
   </span>
  </button>
  <span class="font-label-md text-label-md px-2.5 py-1 rounded bg-tertiary-fixed text-on-tertiary-fixed font-semibold">
   Portal Operasional
  </span>
 </div>
 <div class="flex items-center gap-4">
  <a class="hidden md:flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container-low text-on-surface hover:bg-surface-container font-title-sm text-title-sm transition-colors" href="{{ route('booking') }}">
   <span class="material-symbols-outlined text-[18px]">
    storefront
   </span>
   Lihat Portal Member
  </a>
  <div class="h-5 w-px bg-outline-variant/40">
  </div>
  <div class="flex items-center gap-2">
   <a href="{{ route('operations.verification') }}" aria-label="Verifikasi resi" class="rounded-lg p-2 text-secondary hover:bg-surface-container">
    <span class="material-symbols-outlined" aria-hidden="true">receipt_long</span>
   </a>
   <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="rounded-lg bg-surface-container-low px-3 py-2 text-title-sm font-semibold text-secondary hover:bg-surface-container">Keluar</button>
   </form>
  </div>
 </div>
</header>

<aside {{ $attributes->class(['fixed left-0 top-0 h-full w-64 bg-surface-container-low z-50 flex flex-col pt-4 pb-6 shadow-[0_1px_8px_rgba(0,0,0,0.04)] operator-sidebar']) }} id="operator-sidebar">
 <div class="px-6 mb-6">
  <div class="flex items-center gap-3">
   <span aria-hidden="true" class="material-symbols-outlined text-secondary text-[30px] shrink-0">
    airport_shuttle
   </span>
   <div class="flex flex-col">
    <span class="font-headline-sm text-title-md text-primary font-bold tracking-tight">
     Nusantara OPS
    </span>
    <span class="font-label-sm text-label-sm text-on-surface-variant font-medium">
     Konsol Loket &amp; Pool
    </span>
   </div>
  </div>
 </div>
 <div class="px-4 mb-4">
  <div class="p-1.5 rounded-lg bg-surface-container flex items-center justify-between">
   <span class="font-label-sm text-label-sm uppercase font-bold text-on-surface-variant px-2">
    Mode Akses
   </span>
   <a class="px-2 py-0.5 rounded font-label-sm text-label-sm bg-surface-container-lowest text-primary hover:text-secondary font-semibold transition-all" href="{{ route('booking') }}">
    Ke Portal Member
   </a>
  </div>
 </div>
 <div class="px-6 py-2">
  <span class="font-label-sm text-label-sm uppercase tracking-wider text-on-surface-variant font-semibold">
   Manajemen Perjalanan
  </span>
 </div>
 <nav class="flex-1 px-4 space-y-1" data-active-classes="bg-surface-container-highest text-primary font-title-sm font-semibold rounded-lg">
  <a class="operator-nav-link" href="{{ route('operations.dashboard') }}">
   <span class="material-symbols-outlined text-[20px]">
    monitoring
   </span>
   <span class="font-title-sm text-title-sm">
    Dashboard Operasi
   </span>
  </a>
  <a class="operator-nav-link" data-section="jadwal-armada" href="{{ route('operations.dashboard') }}#jadwal-armada">
   <span class="material-symbols-outlined text-[20px]">
    commute
   </span>
   <span class="font-title-sm text-title-sm">
    Jadwal &amp; Armada
   </span>
  </a>
  <a class="operator-nav-link" href="{{ route('operations.verification') }}">
   <span class="material-symbols-outlined text-[20px]">
    verified
   </span>
   <span class="font-title-sm text-title-sm">
    Verifikasi Resi &amp; Tiket
   </span>
  </a>
  @can('manage-operations')
  <a class="operator-nav-link" href="{{ route('operations.dashboard') }}#tambah-jadwal">
   <span class="material-symbols-outlined text-[20px]" aria-hidden="true">add_road</span>
   <span class="font-title-sm text-title-sm">Tambah Jadwal</span>
  </a>
  <a class="operator-nav-link" data-section="keluhan" href="{{ route('operations.dashboard') }}#keluhan">
   <span class="material-symbols-outlined text-[20px]">
    support_agent
   </span>
   <span class="font-title-sm text-title-sm">
    Layanan Keluhan
   </span>
  </a>
  @endcan
 </nav>
 <div class="px-4 pt-4">
  <div class="p-3 rounded-lg bg-surface-container-lowest flex items-center gap-3 shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
   <span aria-hidden="true" class="inline-flex w-10 h-10 shrink-0 rounded-full bg-secondary-fixed text-secondary items-center justify-center font-bold">
    <span class="material-symbols-outlined">person</span>
   </span>
   <div class="flex-1 min-w-0">
    <p class="font-title-sm text-title-sm text-on-surface font-semibold truncate">
     {{ auth()->user()->nama_lengkap ?? auth()->user()->nama }}
    </p>
    <p class="font-label-sm text-label-sm text-on-surface-variant truncate">
     {{ auth('admin')->check() ? 'Administrator' : 'Operator Loket' }}
    </p>
   </div>
   <span class="material-symbols-outlined text-secondary text-[20px]">
    shield_person
   </span>
  </div>
 </div>
</aside>

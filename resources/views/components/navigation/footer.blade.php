<footer {{ $attributes->class(['w-full bg-surface-container-low text-on-surface-variant']) }}>
 <div class="max-w-7xl mx-auto px-6 lg:px-12 py-12">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
   <div class="space-y-3">
    <div class="flex items-center gap-2">
     <span aria-hidden="true" class="material-symbols-outlined text-secondary text-[30px] shrink-0">
      airport_shuttle
     </span>
     <span class="font-headline-sm text-title-md font-bold text-on-surface">
      Nusantara Travel
     </span>
    </div>
    <p class="font-body-sm text-body-sm leading-relaxed text-on-surface-variant">
     Layanan transportasi antar-kota modern, aman, dan tepat waktu melintasi koridor utama Jawa dan Sumatera.
    </p>
   </div>
   <div>
    <h4 class="font-title-sm text-title-sm font-semibold text-on-surface mb-3">
     Pemesanan Cepat
    </h4>
    <ul class="space-y-2 font-body-sm text-body-sm">
     <li>
      <a class="hover:text-primary transition-colors" href="{{ route('booking') }}">
       Rute &amp; Tarif Shuttle
      </a>
     </li>
     <li>
      <a class="hover:text-primary transition-colors" href="{{ route('booking') }}">
       Denah Kursi HiAce &amp; Innova
      </a>
     </li>
     <li>
      <a class="hover:text-primary transition-colors" href="{{ route('payment') }}">
       Konfirmasi Resi Bank
      </a>
     </li>
    </ul>
   </div>
   <div>
    <h4 class="font-title-sm text-title-sm font-semibold text-on-surface mb-3">
     Layanan Pelanggan
    </h4>
    <ul class="space-y-2 font-body-sm text-body-sm">
     <li>
      <a class="hover:text-primary transition-colors" href="{{ route('payment') }}#tiket-saya">
       Cek E-Ticket &amp; QR Gate
      </a>
     </li>
     <li>
      <a class="hover:text-primary transition-colors" href="{{ route('payment') }}#tiket-saya">
       Pengaduan Layanan &amp; Refund
      </a>
     </li>
     <li>
      <span class="text-on-surface-variant">
       Call Center: 0800-1-NUSANTARA
      </span>
     </li>
    </ul>
   </div>
  </div>
  <div class="pt-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-center sm:text-left">
   <p class="font-body-sm text-body-sm">
    © 2025 PT Nusantara Travel Indonesia. Seluruh hak cipta dilindungi.
   </p>
   <div class="flex items-center gap-6 font-label-md text-label-md">
    <span class="text-on-surface-variant">
     Kebijakan Privasi
    </span>
    <span class="text-on-surface-variant">
     Syarat &amp; Ketentuan Angkutan
    </span>
   </div>
  </div>
 </div>
</footer>

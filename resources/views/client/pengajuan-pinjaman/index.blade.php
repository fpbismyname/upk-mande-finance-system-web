<x-layouts.client-app title="Pengajuan pinjaman">
    {{-- Pengajuan pinjaman --}}
    @if (!empty($kelompok))
        <div class="flex flex-col gap-6">
            <div class="flex flex-row justify-between flex-wrap gap-4 items-center">
                <div class="flex flex-col">
                    <h3>Pengajuan Pinjaman</h3>
                </div>
                <div class="flex flex-col">
                    @if ($kelompok->layak_mengajukan_pinjaman)
                        <a href="{{ route('client.pengajuan-pinjaman.create') }}" class="btn btn-primary">
                            <x-lucide-file-input class="w-4" />
                            Ajukan pinjaman sekarang
                        </a>
                    @endif
                </div>
            </div>
            <div class="flex flex-col">
                <ul class="list bg-base-200 rounded-box">
                    @if ($list_pengajuan_pinjaman->count())
                        @foreach ($list_pengajuan_pinjaman as $pengajuan)
                            <li class="list-row flex-wrap">
                                <div class="list-col-grow">
                                    <div class="font-bold">
                                        Pengajuan {{ $pengajuan->formatted_tanggal_pengajuan }}
                                    </div>
                                    <div>
                                        <small>
                                            @if ($pengajuan->jadwal_pencairan?->status_belum_terjadwal)
                                                Pencairan pinjaman sedang di proses penjadwalan.
                                            @endif
                                            @if ($pengajuan->jadwal_pencairan?->status_terjadwal)
                                                Pencairan pinjaman akan berlangsung pada
                                                {{ $pengajuan->jadwal_pencairan->formatted_tanggal_pencairan }}.
                                            @endif
                                            @if ($pengajuan->jadwal_pencairan?->status_telah_dicairkan)
                                                Dana pinjaman telah dicairan pada
                                                {{ $pengajuan->jadwal_pencairan->formatted_tanggal_pencairan }}.
                                            @endif
                                        </small>
                                    </div>
                                    @if ($pengajuan->status_dalam_proses_pengajuan)
                                        <div class="badge badge-warning badge-sm">{{ $pengajuan->formatted_status }}
                                        </div>
                                    @elseif($pengajuan->status_disetujui)
                                        <div class="badge badge-success badge-sm">{{ $pengajuan->formatted_status }}
                                        </div>
                                    @elseif($pengajuan->status_ditolak)
                                        <div class="badge badge-error badge-sm">{{ $pengajuan->formatted_status }}</div>
                                    @elseif($pengajuan->status_dibatalkan)
                                        <div class="badge badge-error badge-sm">{{ $pengajuan->formatted_status }}</div>
                                    @endif
                                </div>
                                <div class="sm:list-col-wrap">
                                    <a href="{{ route('client.pengajuan-pinjaman.show', [$pengajuan->id]) }}"
                                        class="btn btn-primary">
                                        <x-lucide-eye class="w-4" />
                                        Lihat
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    @else
                        <li class="list-row">
                            <div>Belum ada pengajuan pinjaman.</div>
                        </li>
                    @endif
                </ul>
            </div>
            <div>
                {{ $list_pengajuan_pinjaman->links() }}
            </div>
        </div>
    @else
        <div class="flex flex-col items-center">
            <x-ui.card>
                <div class="card-body text-center">
                    <x-lucide-users class="w-6 self-center" />
                    <h3>Buat kelompok anda untuk mengajukan pinjaman</h3>
                    <div class="card-actions justify-center">
                        <a href="{{ route('client.kelompok.index') }}" class="btn btn-primary">
                            <x-lucide-circle-plus class="w-4" />
                            Buat kelompok
                        </a>
                    </div>
                </div>
            </x-ui.card>
        </div>
    @endif
</x-layouts.client-app>

<x-layouts.client-app title="Pinjaman">
    {{-- Pinjaman kelompok --}}
    @if (!empty($kelompok))
        <div class="flex flex-col gap-6">
            <div class="flex flex-row justify-between flex-wrap gap-4 items-center">
                <div class="flex flex-col">
                    <h3>Pinjaman kelompok</h3>
                </div>
                @if ($kelompok->layak_mengajukan_pinjaman)
                    <a href="{{ route('client.pengajuan-pinjaman.index') }}" class="btn btn-primary">
                        <x-lucide-file-input class="w-4" />
                        Ajukan pinjaman sekarang
                    </a>
                @endif
            </div>
            <div class="flex flex-col">
                <ul class="list bg-base-200 rounded-box">
                    @if ($list_pinjaman_kelompok->count())
                        @foreach ($list_pinjaman_kelompok as $pinjaman)
                            <li class="list-row flex-wrap">
                                <div class="list-col-grow">
                                    <div class="font-bold">Pinjaman {{ $pinjaman->formatted_tanggal_mulai }}</div>
                                    <div>
                                        <div class="flex flex-col gap-2">
                                            <small>Progress cicilan : {{ $pinjaman->progres_cicilan }}</small>
                                            @if (!empty($kelompok))
                                                @if ($pinjaman->status_pinjaman_menunggak)
                                                    <div class="badge badge-sm badge-warning">
                                                        {{ $pinjaman->formatted_status }}
                                                    </div>
                                                @elseif($pinjaman->status_pinjaman_selesai)
                                                    <div class="badge badge-sm badge-success">
                                                        {{ $pinjaman->formatted_status }}
                                                    </div>
                                                @elseif($pinjaman->status_pinjaman_berlangsung)
                                                    <div class="badge badge-sm badge-primary">
                                                        {{ $pinjaman->formatted_status }}
                                                    </div>
                                                @elseif($pinjaman->status_pinjaman_dibatalkan)
                                                    <div class="badge badge-sm badge-error">
                                                        {{ $pinjaman->formatted_status }}
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="sm:list-col-wrap">
                                    <a href="{{ route('client.pinjaman-kelompok.show', [$pinjaman->id]) }}"
                                        class="btn btn-primary">
                                        <x-lucide-eye class="w-4" />
                                        Lihat
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    @else
                        <li class="list-row">
                            <div>Belum ada pinjaman kelompok.</div>
                        </li>
                    @endif
                </ul>
            </div>
            <div>
                {{ !empty($list_pinjaman_kelompok) ? $list_pinjaman_kelompok->links() : null }}
            </div>
        </div>
    @else
        @if (auth()->user()->pengajuan_keanggotaan_disetujui()->exists())
            <div class="flex flex-col items-center">
                <x-ui.card>
                    <div class="card-body text-center">
                        <x-lucide-users class="w-6 self-center" />
                        <h3>Buat kelompok anda untuk melanjutan pinjaman</h3>
                        <div class="card-actions justify-center">
                            <a href="{{ route('client.kelompok.index') }}" class="btn btn-primary">
                                <x-lucide-circle-plus class="w-4" />
                                Buat kelompok
                            </a>
                        </div>
                    </div>
                </x-ui.card>
            </div>
        @else
            <div class="flex flex-col items-center">
                <x-ui.card>
                    <div class="card-body text-center">
                        <x-lucide-users class="w-6 self-center" />
                        <h3>Anda belum menjadi anggota {{ config('site.website.title') }}</h3>
                        <div class="card-actions justify-center">
                            <a href="{{ route('client.pengajuan-keanggotaan.index') }}" class="btn btn-primary">
                                Ajukan keanggotaan
                            </a>
                        </div>
                    </div>
                </x-ui.card>
            </div>
        @endif
    @endif
</x-layouts.client-app>

<x-layouts.admin-app title="Dashboard">
    <x-slot:below_title>
        Selamat datang di halaman dashboard {{ $app['name'] }}
    </x-slot:below_title>
    <div class="flex flex-col gap-6 pt-6">
        {{-- Data kelompok UPK --}}
        <div class="flex flex-col gap-2">
            <div class="flex flex-row gap-2">
                <x-lucide-users-round class="w-4" />
                <h6>
                    Data kelompok
                </h6>
            </div>
            <div class="stats w-full stats-vertical lg:stats-horizontal">
                <div class="stat bg-base-200">
                    <div class="stat-title">Kelompok</div>
                    <div class="stat-value text-primary">{{ $jumlah_kelompok }}</div>
                    @can('manage-kelompok')
                        <div class="stat-actions">
                            <a href="{{ route('admin.kelompok.index') }}" class="flex gap-1 link-hover text-xs w-fit">
                                <x-lucide-eye class="w-4" />
                                Lihat
                            </a>
                        </div>
                    @endcan
                </div>

                <div class="stat bg-base-200">
                    <div class="stat-title">Kelompok aktif</div>
                    <div class="stat-value text-primary">{{ $jumlah_kelompok_aktif }}</div>
                    <div class="stat-actions">
                        @can('manage-kelompok')
                            <a href="{{ route('admin.kelompok.index', ['status' => 'aktif']) }}"
                                class="flex gap-1 link-hover text-xs w-fit">
                                <x-lucide-eye class="w-4" />
                                Lihat
                            </a>
                        @endcan
                    </div>
                </div>

                <div class="stat bg-base-200">
                    <div class="stat-title">Kelompok non-aktif</div>
                    <div class="stat-value text-primary">{{ $jumlah_kelompok_non_aktif }}</div>
                    <div class="stat-actions">
                        @can('manage-kelompok')
                            <a href="{{ route('admin.kelompok.index', ['status' => 'non-aktif']) }}"
                                class="flex gap-1 link-hover text-xs w-fit">
                                <x-lucide-eye class="w-4" />
                                Lihat
                            </a>
                        @endcan
                    </div>
                </div>

                <div class="stat bg-base-200">
                    <div class="stat-title">Jumlah anggota</div>
                    <div class="stat-value text-primary">{{ $jumlah_anggota }}</div>
                </div>
            </div>
        </div>

        {{-- Data Pengajuan pinjaman UPK --}}
        <div class="flex flex-col gap-2">
            <div class="flex flex-row gap-2">
                <x-lucide-files class="w-4" />
                <h6>
                    Data pengajuan pinjaman
                </h6>
            </div>
            <div class="stats w-full stats-vertical lg:stats-horizontal">
                <div class="stat bg-base-200">
                    <div class="stat-title">Pengajuan pinjaman</div>
                    <div class="stat-value text-primary">{{ $jumlah_pengajuan_pinjaman }}</div>
                    <div class="stat-actions">
                        @can('manage-pengajuan-pinjaman')
                            <a href="{{ route('admin.pengajuan-pinjaman.index') }}"
                                class="flex gap-1 link-hover text-xs w-fit">
                                <x-lucide-eye class="w-4" />
                                Lihat
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="stat bg-base-200">
                    <div class="stat-title">Pengajuan dalam proses</div>
                    <div class="stat-value text-primary">{{ $jumlah_pengajuan_proses_pengajuan }}</div>
                    <div class="stat-actions">
                        @can('manage-pengajuan-pinjaman')
                            <a href="{{ route('admin.pengajuan-pinjaman.index', ['status' => 'proses_pengajuan']) }}"
                                class="flex gap-1 link-hover text-xs w-fit">
                                <x-lucide-eye class="w-4" />
                                Lihat
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="stat bg-base-200">
                    <div class="stat-title">Pengajuan disetujui</div>
                    <div class="stat-value text-primary">{{ $jumlah_pengajuan_disetujui }}</div>
                    <div class="stat-actions">
                        @can('manage-pengajuan-pinjaman')
                            <a href="{{ route('admin.pengajuan-pinjaman.index', ['status' => 'disetujui']) }}"
                                class="flex gap-1 link-hover text-xs w-fit">
                                <x-lucide-eye class="w-4" />
                                Lihat
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="stat bg-base-200">
                    <div class="stat-title">Pengajuan ditolak</div>
                    <div class="stat-value text-primary">{{ $jumlah_pengajuan_ditolak }}</div>
                    <div class="stat-actions">
                        @can('manage-pengajuan-pinjaman')
                            <a href="{{ route('admin.pengajuan-pinjaman.index', ['status' => 'ditolak']) }}"
                                class="flex gap-1 link-hover text-xs w-fit">
                                <x-lucide-eye class="w-4" />
                                Lihat
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        {{-- Data Pinjaman UPK --}}
        <div class="flex flex-col gap-2">
            <div class="flex flex-row gap-2">
                <x-lucide-circle-dollar-sign class="w-4" />
                <h6>
                    Data pinjaman
                </h6>
            </div>
            <div class="stats w-full stats-vertical lg:stats-horizontal">
                <div class="stat bg-base-200">
                    <div class="stat-title">Pinjaman</div>
                    <div class="stat-value text-primary">{{ $jumlah_pinjaman }}</div>
                    <div class="stat-actions">
                        @can('manage-pinjaman-kelompok')
                            <a href="{{ route('admin.pinjaman-kelompok.index') }}"
                                class="flex gap-1 link-hover text-xs w-fit">
                                <x-lucide-eye class="w-4" />
                                Lihat
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="stat bg-base-200">
                    <div class="stat-title">Pinjaman sedang berlangsung</div>
                    <div class="stat-value text-primary">{{ $jumlah_pinjaman_berlangsung }}</div>
                    <div class="stat-actions">
                        @can('manage-pinjaman-kelompok')
                            <a href="{{ route('admin.pinjaman-kelompok.index', ['status' => 'berlangsung']) }}"
                                class="flex gap-1 link-hover text-xs w-fit">
                                <x-lucide-eye class="w-4" />
                                Lihat
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="stat bg-base-200">
                    <div class="stat-title">Pinjaman menunggak</div>
                    <div class="stat-value text-primary">{{ $jumlah_pinjaman_menunggak }}</div>
                    <div class="stat-actions">
                        @can('manage-pinjaman-kelompok')
                            <a href="{{ route('admin.pinjaman-kelompok.index', ['status' => 'menunggak']) }}"
                                class="flex gap-1 link-hover text-xs w-fit">
                                <x-lucide-eye class="w-4" />
                                Lihat
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="stat bg-base-200">
                    <div class="stat-title">Pinjaman selesai</div>
                    <div class="stat-value text-primary">{{ $jumlah_pinjaman_selesai }}</div>
                    <div class="stat-actions">
                        @can('manage-pinjaman-kelompok')
                            <a href="{{ route('admin.pinjaman-kelompok.index', ['status' => 'selesai']) }}"
                                class="flex gap-1 link-hover text-xs w-fit">
                                <x-lucide-eye class="w-4" />
                                Lihat
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        {{-- Data Kelompok yang belum bayar cicilan --}}
        <div class="flex flex-col gap-2">
            <div class="flex flex-row gap-2">
                <x-lucide-circle-dollar-sign class="w-4" />
                <h6>
                    Kelompok belum bayar cicilan
                </h6>
            </div>
            <ul class="list bg-base-200 rounded-box shadow-md">
                @if ($kelompok_pinjaman && $kelompok_pinjaman->count())
                    @foreach ($kelompok_pinjaman as $kelompok)
                        <li class="list-row items-center">
                            <div>
                                <h6>{{ $kelompok->name }}</h6>
                                <small class="text-base-content/75">
                                    Jatuh tempo :
                                    {{ $kelompok->pinjaman_kelompok_berlangsung->cicilan_kelompok()->filterCicilanJatuhTempo()->first()->formatted_tanggal_jatuh_tempo }}
                                </small>
                                <br>
                                <small class="text-base-content/75">
                                    Nominal cicilan :
                                    {{ $kelompok->pinjaman_kelompok_berlangsung->cicilan_kelompok()->filterCicilanJatuhTempo()->first()->formatted_nominal_cicilan }}
                                </small>
                                <br>
                                <small class="text-base-content/75">
                                    Progres pinjaman :
                                    {{ $kelompok->pinjaman_kelompok_berlangsung->progres_cicilan }}
                                </small>
                            </div>
                            <div class="ms-auto">
                                @can('manage-cicilan-kelompok')
                                    <a class="btn btn-primary"
                                        href="{{ route('admin.pinjaman-kelompok.show', [$kelompok->pinjaman_kelompok_berlangsung->id]) }}">
                                        <x-lucide-eye class="w-4" />
                                        Lihat
                                    </a>
                                @endcan
                            </div>
                        </li>
                    @endforeach
                @else
                    <li class="list-row">
                        <div>
                            <p class="text-base-content/75">
                                Belum ada cicilan yang jatuh tempo.
                            </p>
                        </div>
                    </li>
                @endif
            </ul>
            @if ($kelompok_pinjaman->isNotEmpty())
                <div>
                    {{ $kelompok_pinjaman->links() }}
                </div>
            @endif
        </div>

    </div>
</x-layouts.admin-app>

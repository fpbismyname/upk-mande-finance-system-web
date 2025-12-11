<x-layouts.client-app title="Detail pinjaman">
    {{-- Detail pinjaman --}}
    <div class="flex flex-col">
        <div class="flex flex-row justify-between items-center gap-4 flex-wrap">
            <div class="flex flex-col gap-2">
                <h3>Detail pinjaman</h3>
                @if ($pinjaman_kelompok->status_pinjaman_selesai)
                    <div class="badge badge-success">
                        {{ $pinjaman_kelompok->formatted_status }}
                    </div>
                @elseif ($pinjaman_kelompok->status_pinjaman_menunggak)
                    <div class="badge badge-warning">
                        {{ $pinjaman_kelompok->formatted_status }}
                    </div>
                @elseif ($pinjaman_kelompok->status_pinjaman_berlangsung)
                    <div class="badge badge-primary">
                        {{ $pinjaman_kelompok->formatted_status }}
                    </div>
                @endif
            </div>
            <div class="flex flex-row gap-2">
                <a href="{{ route('client.pinjaman-kelompok.index') }}" class="btn btn-neutral">
                    Kembali
                </a>
            </div>
        </div>
        <div class="divider"></div>
        @if (!$pinjaman_kelompok->status_pinjaman_selesai)
            <div class="flex flex-col">
                <h6>Berikut ini nomor rekening untuk melakukan pembayaran cicilan.</h6>
                <p>BRI : {{ $user_akuntan->pengajuan_keanggotaan_disetujui()->first()->nomor_rekening }}</p>
            </div>
            <div class="divider"></div>
        @endif
        <div class="grid grid-cols-2 gap-4">
            {{-- Nominal pinjaman --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nominal pinjaman</legend>
                <p>{{ $pinjaman_kelompok->formatted_nominal_pinjaman }}</p>
            </fieldset>
            {{-- Bunga pinjaman --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Bunga pinjaman</legend>
                <p>{{ $pinjaman_kelompok->formatted_bunga }}</p>
            </fieldset>
            {{-- Total nominal pinjaman --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Total nominal pinjaman</legend>
                <p>{{ $pinjaman_kelompok->formatted_total_nominal_pinjaman }}</p>
            </fieldset>
            {{-- Tenor pinjaman --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Tenor pinjaman</legend>
                <p>{{ $pinjaman_kelompok->formatted_tenor }}</p>
            </fieldset>
            {{-- Status pinjaman --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Status pinjaman</legend>
                <p>{{ $pinjaman_kelompok->formatted_status }}</p>
            </fieldset>
            {{-- Tanggal mulai pinjaman --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Tanggal mulai pinjaman</legend>
                <p>{{ $pinjaman_kelompok->formatted_tanggal_mulai }}</p>
            </fieldset>
            {{-- Tanggal jatuh tempo pinjaman --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Tanggal jatuh tempo akhir</legend>
                <p>{{ $pinjaman_kelompok->formatted_tanggal_jatuh_tempo }}</p>
            </fieldset>
        </div>
    </div>
    <div class="divider"></div>
    {{-- Detail cicilan --}}
    <div class="flex flex-col gap-4">
        <div class="flex flex-row">
            <div class="flex flex-col gap-2">
                <h3>Cicilan pinjaman</h3>
            </div>
        </div>
        <ul class="list bg-base-200 rounded-box">
            @foreach ($pinjaman_kelompok->cicilan_kelompok as $cicilan)
                <li class="list-row">
                    <div class="tabular-nums">{{ $loop->iteration }}</div>
                    <div class="list-col-grow">
                        <div class="flex flex-col gap-2">
                            Jatuh tempo pada {{ $cicilan->formatted_tanggal_jatuh_tempo }}
                            <small>Nominal cicilan : {{ $cicilan->formatted_nominal_cicilan }}</small>
                            @if ($cicilan->status_belum_bayar)
                                <div class="badge badge-sm badge-primary">{{ $cicilan->formatted_status }}</div>
                            @elseif($cicilan->status_sudah_bayar)
                                <div class="badge badge-sm badge-success">{{ $cicilan->formatted_status }}</div>
                            @elseif($cicilan->status_telat_bayar)
                                <div class="badge badge-sm badge-warning">{{ $cicilan->formatted_status }}</div>
                            @elseif($cicilan->status_dibatalkan)
                                <div class="badge badge-sm badge-error">{{ $cicilan->formatted_status }}</div>
                            @endif
                        </div>
                    </div>
                    @if ($cicilan->cicilan_belum_bayar)
                        <a href="{{ route('client.pinjaman-kelompok.cicilan-kelompok.edit', [$pinjaman_kelompok->id, $cicilan->id]) }}"
                            class="btn btn-primary">
                            <x-lucide-hand-coins class="w-4" />
                            Bayar
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
    <div class="flex flex-row justify-center">
        <a href="{{ route('client.pinjaman-kelompok.index') }}" class="btn btn-neutral">
            Kembali
        </a>
    </div>
</x-layouts.client-app>

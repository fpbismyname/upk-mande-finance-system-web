<x-layouts.client-app title="Detail pinjaman">
    {{-- Detail pinjaman --}}
    <div class="flex flex-col gap-4">
        <div class="flex flex-row">
            <div class="flex flex-col gap-2">
                <h3>Detail pinjaman</h3>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            {{-- Nominal pinjaman --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nominal pinjaman</legend>
                <input type="text" class="input w-full" value="{{ $pinjaman_kelompok->formatted_nominal_pinjaman }}" />
            </fieldset>
            {{-- Nominal pinjaman final --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nominal pinjaman final</legend>
                <input type="text" class="input w-full"
                    value="{{ $pinjaman_kelompok->formatted_nominal_pinjaman_final }}" />
            </fieldset>
            {{-- Tenor pinjaman --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Tenor pinjaman</legend>
                <input type="text" class="input w-full" value="{{ $pinjaman_kelompok->formatted_tenor }}" />
            </fieldset>
            {{-- Bunga pinjaman --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Bunga pinjaman</legend>
                <input type="text" class="input w-full" value="{{ $pinjaman_kelompok->formatted_bunga }}" />
            </fieldset>
            {{-- Tanggal mulai pinjaman --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Tanggal mulai pinjaman</legend>
                <input type="text" class="input w-full" value="{{ $pinjaman_kelompok->formatted_tanggal_mulai }}" />
            </fieldset>
            {{-- Tanggal jatuh tempo pinjaman --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Tanggal jatuh tempo akhir</legend>
                <input type="text" class="input w-full"
                    value="{{ $pinjaman_kelompok->formatted_tanggal_jatuh_tempo }}" />
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
                                @if ($cicilan->formatted_denda_dibayar)
                                    <small>Denda dibayar :
                                        {{ $cicilan->formatted_denda_dibayar }}</small>
                                @endif
                                <div class="badge badge-sm badge-success">{{ $cicilan->formatted_status }}</div>
                            @elseif($cicilan->status_telat_bayar)
                                <small>Denda telat bayar (perhari telat) :
                                    {{ $cicilan->formatted_denda_telat_bayar }}</small>
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

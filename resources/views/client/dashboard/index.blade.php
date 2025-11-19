<x-layouts.client-app title="Dashboard">
    <div class="flex flex-col gap-6">
        <x-ui.card class="flex bg-base-200">
            <div class="card-body flex flex-row justify-between items-center flex-wrap">
                <div class="flex flex-col gap-2">
                    <h6 class="card-title">
                        <x-lucide-banknote class="w-6" />
                        Limit pinjaman kelompok
                    </h6>
                    @if ($kelompok)
                        <h3 class="text-primary">
                            {{ $kelompok->formatted_sisa_limit_pinjaman }}
                        </h3>
                        <small class="text-neutral">
                            limit pinjaman terpakai : {{ $kelompok->formatted_limit_pinjaman_terpakai }}
                        </small>
                    @else
                        <h6 class="text-base-content/75">Buat kelompok anda untuk mengajukan pinjaman.</h6>
                    @endif
                </div>
                <div class="flex flex-row gap-4">
                    @if ($kelompok)
                        @if ($kelompok->pinjaman_kelompok_berlangsung)
                            <a href="{{ route('client.pinjaman-kelompok.index') }}" class="btn btn-primary">Lihat
                                cicilan</a>
                        @else
                            <a class="btn btn-primary" href="{{ route('client.pengajuan-pinjaman.create') }}">Ajukan
                                pinjaman</a>
                        @endif
                    @endif
                </div>
            </div>
        </x-ui.card>
    </div>
</x-layouts.client-app>

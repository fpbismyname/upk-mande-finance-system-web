<x-layouts.client-app class="Pengajuan keanggotaan">
    <div class="flex flex-col gap-6">
        <div class="flex flex-row justify-between flex-wrap gap-4 items-center">
            <div class="flex flex-col">
                <h3>Pengajuan keanggotaan</h3>
            </div>
            <div class="flex flex-col">
                @if (auth()->user()->dapat_mengajukan_keanggotaan)
                    <a href="{{ route('client.pengajuan-keanggotaan.create') }}" class="btn btn-primary">Ajukan
                        keanggotaan</a>
                @endif
            </div>
        </div>
        <div class="flex flex-col">
            <ul class="list bg-base-200 rounded-box">
                @if ($datas->isNotEmpty())
                    @foreach ($datas as $pengajuan)
                        <li class="list-row flex-wrap">
                            <div class="list-col-grow">
                                <div class="font-bold">
                                    Pengajuan keanggotaan {{ $pengajuan->formatted_created_at }}
                                </div>
                                @if ($pengajuan->status_proses_pengajuan)
                                    <div class="badge badge-warning badge-sm">{{ $pengajuan->status->label() }}
                                    </div>
                                @elseif($pengajuan->status_disetujui)
                                    <div class="badge badge-success badge-sm">{{ $pengajuan->status->label() }}
                                    </div>
                                @elseif($pengajuan->status_ditolak)
                                    <div class="badge badge-error badge-sm">{{ $pengajuan->status->label() }}</div>
                                @elseif($pengajuan->status_dibatalkan)
                                    <div class="badge badge-error badge-sm">{{ $pengajuan->status->label() }}</div>
                                @endif
                            </div>
                            <div class="sm:list-col-wrap">
                                <a href="{{ route('client.pengajuan-keanggotaan.show', [$pengajuan->id]) }}"
                                    class="btn btn-primary">
                                    <x-lucide-eye class="w-4" />
                                    Lihat
                                </a>
                            </div>
                        </li>
                    @endforeach
                @else
                    <li class="list-row">
                        <div>Belum ada pengajuan keanggotaan.</div>
                    </li>
                @endif
            </ul>
        </div>
        <div>
            {{ $datas->links() }}
        </div>
    </div>
</x-layouts.client-app>

<x-layouts.client-app title="Detail pengajuan pinjaman">
    <div class="flex flex-row justify-between items-center">
        <div class="flex flex-col">
            <h3>Detail pengajuan pinjaman</h3>
        </div>
        <div class="flex flex-col">
            <div class="flex flex-row gap-2 flex-wrap">
                @if (!$pengajuan_pinjaman->status_dibatalkan && !$pengajuan_pinjaman->status_disetujui)
                    <a href="{{ route('client.pengajuan-pinjaman.edit', [$pengajuan_pinjaman->id]) }}"
                        class="btn btn-primary">
                        <x-lucide-edit class="w-4" />
                        Edit
                    </a>
                    <form onsubmit="return confirm('Apakah anda yakin ingin membatalkan pengajuan ini ?')" method="post"
                        action="{{ route('client.pengajuan-pinjaman.cancel', [$pengajuan_pinjaman->id]) }}">
                        @csrf
                        @method('post')
                        <button type="submit" class="btn btn-error">
                            <x-lucide-circle-slash class="w-4" />
                            Batalkan
                        </button>
                    </form>
                @endif
                <a href="{{ route('client.pengajuan-pinjaman.index') }}" class="btn btn-neutral">
                    Kembali
                </a>
            </div>
        </div>
    </div>
    <div class="flex flex-col">
        <div class="grid md:grid-cols-2 gap-4">
            @if ($pengajuan_pinjaman->file_proposal)
                <div class="grid md:col-span-2 place-items-center gap-4 my-6">
                    <iframe src="{{ route('storage.get-file', ['path' => $pengajuan_pinjaman->file_proposal]) }}"
                        frameborder="0"
                        class="border-0 shadow-xl rounded-box aspect-square md:aspect-video max-w-6xl"></iframe>
                    <h6>Proposal pengajuan pinjaman</h6>
                </div>
            @endif

            <!-- Name Input -->
            <fieldset class="fieldset">
                <legend class="fieldset-legend">
                    Nominal pengajuan
                </legend>
                <input value="{{ $pengajuan_pinjaman->formatted_nominal_pinjaman }}" class="input w-full validator"
                    readonly />
            </fieldset>

            <!-- Tenor pinjaman -->
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Tenor pinjaman</legend>
                <input value="{{ $pengajuan_pinjaman->formatted_tenor }}" class="input w-full validator" readonly />
            </fieldset>

            <!-- Status pinjaman -->
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Status pengajuan</legend>
                <input value="{{ $pengajuan_pinjaman->formatted_status }}" class="input w-full validator" readonly />
            </fieldset>

            {{-- Catatan proposal --}}
            @if ($pengajuan_pinjaman->catatan)
                <div class="grid md:col-span-2">
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Catatan</legend>
                        <textarea class="textarea w-full" readonly>{{ $pengajuan_pinjaman->catatan }}</textarea>
                    </fieldset>
                </div>
            @endif

            <div class="grid place-items-center md:col-span-2">
                <div class="flex flex-row gap-4">
                    <a href="{{ route('client.pengajuan-pinjaman.index') }}" class="btn btn-neutral">Kembali</a>
                </div>
            </div>

        </div>
    </div>
</x-layouts.client-app>

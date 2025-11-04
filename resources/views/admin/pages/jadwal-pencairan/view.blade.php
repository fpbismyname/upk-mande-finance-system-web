<x-layouts.admin-app title='Buat penjadwalan ' :breadcrumbs="$breadcrumbs">
    <x-slot:right_item>
        @if (!empty($jadwal_pencairan->tanggal_pencairan))
            <a href="{{ route('admin.users.edit', ['id' => $jadwal_pencairan->id]) }}" class="btn btn-primary">Jadwalkan
                Ulang</a>
        @endif
    </x-slot:right_item>
    <div class="flex flex-col gap-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nama kelompok</legend>
                <input type="text" placeholder="Nama kelompok" class="input w-full"
                    value="{{ $jadwal_pencairan->kelompok_name ?? '-' }}" readonly />
            </fieldset>
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Ketua kelompok</legend>
                <input type="text" placeholder="Ketua kelompok" class="input w-full"
                    value="{{ $jadwal_pencairan->ketua_name ?? '-' }}" readonly />
            </fieldset>
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nominal pengajuan</legend>
                <input type="email" placeholder="Nominal pengajuan" class="input w-full"
                    value="{{ $jadwal_pencairan->pengajuan_nominal ?? '-' }}" readonly />
            </fieldset>
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Status pengajuan</legend>
                <input type="email" placeholder="Status pengajuan" class="input w-full"
                    value="{{ $jadwal_pencairan->pengajuan_status ?? '-' }}" readonly />
            </fieldset>
            @if (!empty($jadwal_pencairan->tanggal_pencairan))
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Tanggal pencairan</legend>
                    <input type="text" placeholder="Tanggal pencairan" class="input w-full"
                        value="{{ $jadwal_pencairan->formatted_tanggal_pencairan ?? '-' }}" readonly />
                </fieldset>
            @endif
            <div class="grid col-span-2 grid-cols-2 gap-4">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Tanggal pengajuan disetujui</legend>
                    <input type="text" placeholder="Tanggal pengajuan disetujui"
                        value="{{ $jadwal_pencairan->pengajuan_disetujui }}" class="input w-full" readonly />
                </fieldset>
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Status penjadwalan</legend>
                    <input placeholder="Status penjadwalan" class="input w-full"
                        value="{{ $jadwal_pencairan->formatted_status ?? '-' }}" readonly></input>
                </fieldset>
            </div>
        </div>
        <div class="flex flex-row gap-4 justify-center items-center">
            @if (!$jadwal_pencairan->telah_dicairkan)
                <button class="btn btn-primary" onclick="window.open_modal('modal-penjadwalan')">
                    @if ($jadwal_pencairan->belum_terjadwal)
                        Jadwalkan
                    @else
                        Atur ulang jadwal pencairan
                    @endif
                </button>
            @endif
            <a href="{{ route('admin.jadwal-pencairan.index') }}" class="btn btn-neutral">
                <span>
                    {{ __('Back') }}
                </span>
            </a>
        </div>
    </div>

    {{-- Modal buat penjadwalan --}}
    <dialog id="modal-penjadwalan" class="modal">
        <div class="modal-box">
            <h3 class="text-lg font-bold">
                @if ($jadwal_pencairan->belum_terjadwal)
                    Buat jadwal pencarian
                @else
                    Atur ulang jadwal pencairan
                @endif
            </h3>
            <form action="{{ route('admin.jadwal-pencairan.create-schedule', [$jadwal_pencairan->id]) }}"
                class="grid grid-cols-1 gap-4">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Tanggal pencairan</legend>
                    <input type="datetime-local" class="input w-full validator" name="tanggal_pencairan" required
                        min="{{ now()->format('Y-m-d\TH:i') }}" max="{{ now()->addDays(30)->format('Y-m-d\TH:i') }}"
                        value="{{ $jadwal_pencairan->datetimelocal_tanggal_pencairan }}" />
                    <p class="validator-hint hidden">
                        {{ __('validation.required', ['Attribute' => 'Tanggal pencairan']) }}
                        <br> Tanggal pencairan tidak boleh kurang dari tanggal sekarang.
                    </p>
                </fieldset>
                <div class="grid grid-cols-2 col-span-2 gap-4">
                    <button class="btn btn-primary">
                        @if ($jadwal_pencairan->belum_terjadwal)
                            Buat jadwal
                        @else
                            Atur ulang jadwal
                        @endif
                    </button>
                    <button class="btn btn-neutral" type="reset"
                        onclick="window.close_modal('modal-penjadwalan')">{{ __('crud.action.back') }}</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

</x-layouts.admin-app>

<x-layouts.admin-app title='Detail jadwal pencairan'>
    <div class="flex flex-col gap-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nama kelompok</legend>
                <p class="w-full">{{ $jadwal_pencairan->kelompok_name ?? '-' }}</p>
            </fieldset>
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Ketua kelompok</legend>
                <p class="w-full">{{ $jadwal_pencairan->ketua_name ?? '-' }}</p>
            </fieldset>
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nominal pengajuan</legend>
                <p class="w-full">{{ $jadwal_pencairan->formatted_pengajuan_nominal ?? '-' }}</p>
            </fieldset>
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Status pengajuan</legend>
                <p class="w-full">{{ $jadwal_pencairan->formatted_pengajuan_status ?? '-' }}</p>
            </fieldset>
            @if (!empty($jadwal_pencairan->tanggal_pencairan))
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Tanggal pencairan</legend>
                    <p class="w-full">{{ $jadwal_pencairan->formatted_tanggal_pencairan ?? '-' }}</p>
                </fieldset>
            @endif
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Tanggal pengajuan disetujui</legend>
                <p class="w-full">{{ $jadwal_pencairan->formatted_pengajuan_tanggal_disetujui }}</p>
            </fieldset>
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Status penjadwalan</legend>
                <p class="w-full">{{ $jadwal_pencairan->formatted_status ?? '-' }}</p>
            </fieldset>
        </div>
        <div class="flex flex-row gap-4 justify-center items-center">
            <a href="{{ route('admin.jadwal-pencairan.index') }}" class="btn btn-neutral">
                Kembali
            </a>
        </div>
    </div>

</x-layouts.admin-app>

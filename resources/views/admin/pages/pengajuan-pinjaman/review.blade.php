@push('scripts')
    <script>
        function open_modal_review(id) {
            const modal = document.getElementById(id)
            modal.showModal()
        }

        function close_modal_review(id) {
            const modal = document.getElementById(id)
            modal.close()
        }
    </script>
@endpush

<x-layouts.admin-app title="Review pengajuan pinjaman" :breadcrumbs="$breadcrumbs">
    <div class="flex flex-col gap-4">
        {{-- Data pengajuan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Preview dokumen proposal pengajuan pinjaman --}}
            <div class="grid md:col-span-2 place-items-center gap-4 my-6">
                <iframe src="{{ $pengajuan_pinjaman->file_proposal }}" frameborder="0"
                    class="border-0 shadow-xl rounded-box aspect-video max-w-6xl"></iframe>
                <h6>Proposal pengajuan pinjaman</h6>
            </div>

            {{-- field data pengajuan pinjaman --}}
            {{-- Nama kelompok --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nama kelompok</legend>
                <input type="text" class="input w-full" value="{{ $pengajuan_pinjaman->kelompok_name ?? '-' }}"
                    readonly />
            </fieldset>

            {{-- Status --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Status</legend>
                <input type="text" class="input w-full" value="{{ $pengajuan_pinjaman->status_name ?? '-' }}"
                    readonly />
            </fieldset>

            {{-- Nominal pinjaman --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nominal Pinjaman</legend>
                <input type="text" class="input w-full"
                    value="{{ $pengajuan_pinjaman->formatted_nominal_pinjaman ?? '-' }}" readonly />
            </fieldset>

            {{-- Tenor --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Tenor</legend>
                <input type="text" class="input w-full" value="{{ $pengajuan_pinjaman->formatted_tenor ?? '-' }}"
                    readonly />
            </fieldset>

            {{-- Tanggal pengajuan --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Pengajuan Pada</legend>
                <input type="text" class="input w-full" value="{{ $pengajuan_pinjaman->formatted_pengajuan ?? '-' }}"
                    readonly />
            </fieldset>

            {{-- Tanggal disetujui --}}
            @if ($pengajuan_pinjaman->disetujui_pada)
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Disetujui Pada</legend>
                    <input type="text" class="input w-full"
                        value="{{ $pengajuan_pinjaman->formatted_disetujui ?? '-' }}" readonly />
                </fieldset>
            @endif

            {{-- Tanggal di tolak --}}
            @if ($pengajuan_pinjaman->ditolak_pada)
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Ditolak Pada</legend>
                    <input type="text" class="input w-full"
                        value="{{ $pengajuan_pinjaman->formatted_ditolak ?? '-' }}" readonly />
                </fieldset>
            @endif

            {{-- Catatan proposal --}}
            @if ($pengajuan_pinjaman->catatan)
                <div class="grid md:col-span-2">
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Catatan</legend>
                        <textarea class="textarea w-full" readonly>{{ $pengajuan_pinjaman->catatan ?? '-' }}</textarea>
                    </fieldset>
                </div>
            @endif
        </div>

        {{-- Approval action --}}
        <div
            class="grid {{ $pengajuan_pinjaman->on_proses_pengajuan ? 'md:grid-cols-2' : 'md:grid-cols-1 place-items-center' }} gap-4 my-8">
            @if ($pengajuan_pinjaman->on_proses_pengajuan)
                <button href="{{ route('admin.users.index') }}" class="btn btn-primary"
                    onclick="open_modal_review('modal-review')">
                    {{ __('crud.action.review') }}
                </button>
            @endif
            <a href="{{ route('admin.pengajuan-pinjaman.index') }}" class="btn btn-neutral">
                <span>
                    {{ __('Back') }}
                </span>
            </a>
        </div>

        {{-- Modal review --}}
        <dialog id="modal-review" class="modal">
            <div class="modal-box">
                <h4 class="modal-title">Review pengajuan pinjaman.</h4>
                <form action="{{ route('admin.pengajuan-pinjaman.submit-review', [$pengajuan_pinjaman->id]) }}"
                    method="post" class="grid grid-cols-1 gap-4">
                    @csrf
                    @method('put')
                    {{-- Status pengajuan pinjaman --}}
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Status pengajuan</legend>
                        <select name="status_id" class="select w-full validator" required>
                            @if ($list_status_pengajuan)
                                <option value="" selected disabled>Pilih status pengajuan</option>
                                @foreach ($list_status_pengajuan as $status)
                                    <option value="{{ $status->id }}">{{ $status->formatted_name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <p class="validator-hint hidden">
                            {{ __('validation.required', ['Status pengajuan pinjaman']) }}
                        </p>
                        @error('status_id')
                            <small class="text-error">{{ $message }}</small>
                        @enderror
                    </fieldset>

                    {{-- Catatan pengajuan pinjaman --}}
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Catatan</legend>
                        <textarea name="catatan" class="textarea validator w-full"></textarea>
                        @error('catatan')
                            <small class="text-error">{{ $message }}</small>
                        @enderror
                    </fieldset>

                    {{-- Action form button --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <button type="submit" class="btn btn-success"
                            onclick="return confirm('Apakah anda yakin dengan review anda ?')">
                            {{ __('crud.action.send') }}
                        </button>
                        <button type="reset" class="btn btn-neutral" onclick="close_modal_review('modal-review')">
                            {{ __('crud.action.cancel') }}
                        </button>
                    </div>
                </form>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>
    </div>
</x-layouts.admin-app>

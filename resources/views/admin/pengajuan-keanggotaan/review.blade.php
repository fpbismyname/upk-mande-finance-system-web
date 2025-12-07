{{-- Modal review --}}
<dialog id="modal-review" class="modal">
    <div class="modal-box">
        <h4 class="modal-title">Review pengajuan pinjaman.</h4>
        <form action="{{ route('admin.pengajuan-keanggotaan.update', [$pengajuan_keanggotaan->id]) }}" method="post"
            class="grid grid-cols-1 gap-4">
            @csrf
            @method('put')
            {{-- Status pengajuan pinjaman --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Status pengajuan</legend>
                <select name="status" class="select w-full validator" required>
                    <option value="" selected disabled>Pilih status pengajuan</option>
                    @foreach (App\Enums\Admin\Status\EnumStatusPengajuanKeanggotaan::cases_review() as $status)
                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
                </select>
                <p class="validator-hint hidden">
                    Status pengajuan pinjaman wajib diisi
                </p>
                @error('status')
                    <small class="text-error">{{ $message }}</small>
                @enderror
            </fieldset>

            {{-- Catatan pengajuan pinjaman --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Catatan</legend>
                <textarea name="catatan" placeholder="Opsional" class="textarea validator w-full"></textarea>
                @error('catatan')
                    <small class="text-error">{{ $message }}</small>
                @enderror
            </fieldset>

            {{-- Action form button --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <button type="submit" class="btn btn-success"
                    onclick="return confirm('Apakah anda yakin dengan review anda ?')">
                    Review
                </button>
                <button type="reset" class="btn btn-neutral" onclick="window.close_modal('modal-review')">
                    Kembali
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

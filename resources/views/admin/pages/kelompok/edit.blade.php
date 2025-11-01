@php
    $is_empty_ketua_kelompok = $list_ketua_kelompok->isEmpty();
    $is_empty_status = $list_status->isEmpty();
@endphp
<x-layouts.admin-app :title="'Edit ' . $kelompok->nama_kelompok" :breadcrumbs="$breadcrumbs">
    <div class="flex flex-col gap-4">
        {{-- Add form --}}
        <form action="{{ route('admin.kelompok.update', [$kelompok->id]) }}" class="grid grid-cols-1 gap-2 md:grid-cols-2"
            method="POST">
            @csrf
            @method('put')
            {{-- Nama kelompok --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nama kelompok</legend>
                <input type="text" readonly
                    placeholder="Nama kelompok (contoh: kelompok mawar, kelompok anggrek, dsb.)"
                    class="input w-full validator" minlength="4" value="{{ old('nama_kelompok') ?? $kelompok->name }}"
                    required />
                <p class="validator-hint hidden">
                    {{ __('validation.required', ['attribute' => 'Nama kelompok']) }}
                    <br>{{ __('validation.min.string', ['Attribute' => 'Nama kelompok', 'min' => 4]) }}
                </p>
                @error('name')
                    <p class="fieldset-label">{{ $message }}</p>
                @enderror
            </fieldset>

            {{-- limit pinjaman --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">
                    Limit pinjaman
                    <small id="preview-limit-pinjaman">
                        {{ '(Rp ' . number_format($kelompok->limit_pinjaman, 0, ',', '.') . ')' }}
                    </small>
                </legend>
                <input type="number" name="limit_pinjaman" placeholder="Limit pinjaman" pattern="\s[0-9]"
                    value="{{ old('limit_pinjaman') ?? $kelompok->limit_pinjaman }}" min="1000000" max="9999999999999"
                    class="input w-full validator"
                    oninput="window.currency_format_element(this, 'preview-limit-pinjaman')" required />
                <p class="validator-hint hidden">
                    {{ __('validation.required', ['attribute' => 'Limit pinjaman']) }}
                    <br> {{ __('validation.min.numeric', ['Attribute' => 'Limit pinjaman', 'min' => 'Rp 1.000.000']) }}
                </p>
                @error('limit_pinjaman')
                    <p class="fieldset-label">{{ $message }}</p>
                @enderror
            </fieldset>

            {{-- Ketua kelompok --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Ketua kelompok</legend>
                <select name="ketua_id" class="select w-full validator" required>
                    @if (!$is_empty_ketua_kelompok)
                        <option value="" selected disabled>Pilih ketua kelompok</option>
                        @foreach ($list_ketua_kelompok as $user)
                            <option value="{{ $user->id }}" @if ($user->name == $kelompok->ketua_name) selected @endif>
                                {{ Str::of($user->name)->replace('_', ' ')->ucfirst() }}
                            </option>
                        @endforeach
                    @else
                        <option value="" selected disabled>
                            {{ __('crud.no_data', ['item' => 'data ketua kelompok']) }}
                        </option>
                    @endif
                </select>
                <p class="validator-hint hidden">
                    {{ __('validation.required', ['attribute' => 'Ketua kelompok']) }}
                </p>
                @error('ketua_id')
                    <p class="fieldset-label">{{ $message }}</p>
                @enderror
            </fieldset>

            {{-- Status --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Status kelompok</legend>
                <select name="status_id" class="select validator w-full" required>
                    @if (!$is_empty_status)
                        <option value="" selected disabled>Pilih status kelompok</option>
                        @foreach ($list_status as $status)
                            <option value="{{ $status->id }}" @if ($status->name == $kelompok->status_name) selected @endif>
                                {{ Str::of($status->name)->replace('_', ' ')->ucfirst() }}
                            </option>
                        @endforeach
                    @else
                        <option value="" selected disabled>
                            {{ __('crud.no_data', ['item' => 'data status']) }}
                        </option>
                    @endif
                </select>
                <p class="validator-hint hidden">
                    {{ __('validation.required', ['attribute' => 'Status kelompok']) }}
                </p>
                @error('status_id')
                    <p class="fieldset-label">{{ $message }}</p>
                @enderror
            </fieldset>

            {{-- Action button form --}}
            <div class="grid place-items-center md:col-span-2 pt-6">
                <div class="flex flex-row gap-4">
                    @if ($is_empty_ketua_kelompok)
                        <div class="tooltip tooltip-error"
                            data-tip="{{ __('crud.no_data', ['item' => 'ketua kelompok']) }}">
                    @endif
                    <button type="submit" class="btn btn-primary" @if ($is_empty_ketua_kelompok) disabled @endif>
                        {{ __('crud.action.save') }}
                    </button>
                    @if ($is_empty_ketua_kelompok)
                </div>
                @endif
                <a href="{{ route('admin.kelompok.index') }}" class="btn btn-neutral">
                    <span>
                        {{ __('Back') }}
                    </span>
                </a>
            </div>
    </div>
    </form>
    </div>
</x-layouts.admin-app>

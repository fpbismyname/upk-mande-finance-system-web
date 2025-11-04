@push('scripts')
    <script>
        // Open modal pendanaan
        function open_modal_pendanan(id) {
            const modal = document.getElementById(id)
            modal.showModal()
        }

        // Close modal pendanaan
        function close_modal_pendanaan(id) {
            const modal = document.getElementById(id)
            modal.close()
        }

        // Format saldo jadi currency
        function preview_amount(e, element) {
            const previewElement = document.getElementById(element)
            previewElement.innerText = window.format_currency(e.value)
        }
    </script>
@endpush
<x-layouts.admin-app title="Kelola Saldo Pendanaan" :breadcrumbs="$breadcrumbs">

    <!-- Pendanaan preview -->
    <div class="flex flex-row gap-4">
        <div class="stats stats-vertical xl:stats-horizontal w-full">
            <!-- Saldo rekening -->
            <div class="stat bg-base-300">
                <div class="stat-figure text-primary items-center">
                    <x-lucide-wallet class="w-6" />
                </div>
                <div class="stat-title">Saldo rekening</div>
                <div class="stat-value text-primary">{{ $datas_pendanaan->formatted_saldo }}</div>
            </div>
            <!-- Pemasukan -->
            <div class="stat bg-base-300">
                <div class="stat-figure text-success items-center">
                    <x-lucide-circle-arrow-down class="w-6" />
                </div>
                <div class="stat-title">Pemasukan</div>
                <div class="stat-value text-success">{{ $datas_pendanaan->inflow_data }}</div>
            </div>
            <!-- Pengeluaran -->
            <div class="stat bg-base-300">
                <div class="stat-figure text-error items-center">
                    <x-lucide-circle-arrow-up class="w-6" />
                </div>
                <div class="stat-title">Pengeluaran</div>
                <div class="stat-value text-error">{{ $datas_pendanaan->outflow_data }}</div>
            </div>
        </div>
    </div>
    <!-- Action pendanaan -->
    <div class="grid grid-cols-2 gap-2">
        <button class="btn btn-success w-full" onclick="open_modal_pendanan('modal-topup-saldo')">
            <x-lucide-banknote class="w-4" />
            Topup saldo
        </button>
        <button class="btn btn-error w-full" onclick="open_modal_pendanan('modal-tarik-saldo')">
            <x-lucide-hand-coins class="w-4" />
            Tarik saldo
        </button>
    </div>

    <div class="divider"></div>

    <!-- Data inflow & outflow -->
    <div class="flex flex-col gap-4">
        <div class="flex flex-row">
            <h2>Catatan pendanaan</h2>
        </div>
        {{-- Data table --}}
        <div class="flex flex-col gap-6">
            <div class="flex flex-row justify-between">
                <div class="flex flex-row gap-4">
                    {{-- Seach bar --}}
                    <form method="get">
                        <div class="join">
                            <div class="input join-item">
                                <input type="text" name="search" value="{{ request()->get('search') }}"
                                    placeholder="Cari catatan pendanaan..." />
                            </div>
                            <button type="submit" class="btn btn-primary join-item">
                                <x-lucide-search class="w-4" />
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="flex flex-col">
                <x-ui.table>
                    <thead>
                        <th>#</th>
                        <th>Catatan</th>
                        <th>Jumlah saldo</th>
                        <th>Tipe catatan</th>
                    </thead>
                    <tbody>
                        @if (count($datas_catatan_pendanaan) > 0)
                            @foreach ($datas_catatan_pendanaan as $item)
                                @php
                                    $current_account = optional(auth()->user())->is($item);
                                @endphp
                                <tr @if ($current_account) class="bg-primary/50" @endif>
                                    <td>{{ $loop->iteration + ($datas_catatan_pendanaan->currentPage() - 1) * $datas_catatan_pendanaan->perPage() }}
                                    </td>
                                    <td>{{ $item->catatan }}</td>
                                    <td>{{ $item->formatted_jumlah_saldo }}</td>
                                    <td
                                        class="{{ $item->tipe_catatan === App\Enum\Admin\CatatanPendanaan\EnumCatatanPendanaan::INFLOW ? 'text-success' : 'text-error' }}">
                                        {{ $item->formatted_tipe_catatan }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8">
                                    <div class="p-4 text-center">
                                        {{ __('crud.no_data', ['item' => 'catatan pendanaan']) }}
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </x-ui.table>
                <div class="p-4">
                    {{ $datas_catatan_pendanaan->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Topup -->
    <dialog id="modal-topup-saldo" class="modal">
        <div class="modal-box">
            <h3 class="modal-title">Topup saldo</h3>
            <small class="text-base-content/75">Minimal topup Rp 100.000</small>
            {{-- topup saldo preview --}}
            <div class="flex flex-col p-6">
                <div class="badge bg-success text-base-100 rounded-full p-4 badge-xl mx-auto">
                    <x-lucide-banknote class="w-6" />
                    <span id="preview-topup">
                        Rp 0
                    </span>
                </div>
            </div>
            <form action="{{ route('admin.pendanaan.topup') }}" method="POST" class="flex flex-col p-4 gap-4">
                @method('post')
                @csrf
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Jumlah topup</legend>
                    <label class="input validator w-full">
                        <span>Rp</span>
                        <input type="number" name="amount_topup_saldo" pattern="\d{6,15}" min="100000"
                            oninput="preview_amount(this, 'preview-topup')" max="999999999999999" inputmode="numeric"
                            value="0" required />
                    </label>
                    <p class="validator-hint hidden">
                        {{ __('validation.min.numeric', ['Attribute' => 'Jumlah saldo topup', 'min' => 'Rp 100.000']) }}
                    </p>
                    @error('amount_topup')
                        <small class="label text-error">{{ $message }}</small>
                    @enderror
                </fieldset>
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Catatan</legend>
                    <textarea name="notes_topup_saldo" class="textarea w-full validator" required>{{ __('Topup saldo pendanaan' ?? '') }}</textarea>
                    @error('notes_topup_saldo')
                        <small class="label text-error">{{ $message }}</small>
                    @enderror
                </fieldset>
                <div class="grid grid-cols-2 gap-2">
                    <button type="submit" class="btn btn-success">Topup</button>
                    <button type="reset" class="btn btn-neutral"
                        onclick="close_modal_pendanaan('modal-topup-saldo')">{{ __('crud.action.cancel') }}</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <!-- Modal Tarik saldo -->
    <dialog id="modal-tarik-saldo" class="modal">
        <div class="modal-box">
            <h3 class="modal-title">Tarik saldo</h3>
            <small class="text-base-content/75">Minimal tarik saldo Rp 100.000</small>
            {{-- tarik saldo preview --}}
            <div class="flex flex-col p-6">
                <div class="badge bg-error text-base-100 rounded-full p-4 badge-xl mx-auto">
                    <x-lucide-hand-coins class="w-6" />
                    <span id="preview-tarik-saldo">
                        Rp 0
                    </span>
                </div>
            </div>
            <form action="{{ route('admin.pendanaan.tarik-saldo') }}" method="POST" class="flex flex-col p-4 gap-4">
                @method('post')
                @csrf
                <!-- Jumlah tarik dana -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Jumlah tarik saldo</legend>
                    <label class="input validator w-full">
                        <span>Rp</span>
                        <input type="number" name="amount_tarik_saldo" pattern="\d{6,15}" min="100000"
                            oninput="preview_amount(this, 'preview-tarik-saldo')" max="{{ $datas_pendanaan->saldo }}"
                            value="0" inputmode="numeric" required />
                    </label>
                    <p class="validator-hint hidden">
                        {{ __('validation.min.numeric', ['Attribute' => 'Jumlah saldo ditarik', 'min' => 'Rp 100.000']) }}
                        <br> Pastikan jumlah saldo yang ditarik mencukupi
                    </p>
                    @error('amount_topup')
                        <small class="label text-error">{{ $message }}</small>
                    @enderror
                </fieldset>
                <!-- Catatan tarik saldo -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Catatan</legend>
                    <textarea name="notes_tarik_saldo" class="textarea w-full validator" required>{{ __('Tarik saldo pendanaan' ?? '') }}</textarea>
                    @error('notes_tarik_saldo')
                        <small class="label text-error">{{ $message }}</small>
                    @enderror
                </fieldset>
                <div class="grid grid-cols-2 gap-2">
                    <button type="submit" class="btn btn-error">Tarik dana</button>
                    <button type="reset" class="btn btn-neutral"
                        onclick="close_modal_pendanaan('modal-tarik-saldo')">{{ __('crud.action.cancel') }}</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

</x-layouts.admin-app>

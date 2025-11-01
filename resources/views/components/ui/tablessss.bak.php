@props([
    'datas' => [],
    'add_label' => 'Tambah Data',
    'create_route' => null,
    'route' => '',
    'context' => [],
    'view_mode' => false,
    'search_bar' => true,
    'action_modal' => false,
])
{{-- 
    Scripts js
 --}}
@push('scripts')
    <script>
        function open_modal_table(e, id_modal, data = {}) {
            // Open modal
            const current_modal = document.getElementById(id_modal)
            current_modal.showModal();
            // Get children element
            const modal_title = current_modal.querySelector('#modal-title')
            const modal_form = current_modal.querySelector('#modal-form')
            // Get children fieldset
            const fieldset = modal_form.querySelectorAll('.fieldset')
            // Get data element
            const data_title = e.getAttribute('data-title') ?? ''
            const data_action = e.getAttribute('data-action') ?? ''
            const data_form = JSON.parse(e.getAttribute('data-item')) ?? {}
            // Set attributes to modal element
            modal_title.innerHTML = data_title
            modal_form.action = data_action
            // Fill form data
            fieldset.forEach(el => {
                // Get input element
                const input = el.querySelector('.input')
                const key = input.name
                // console.log(data_form)
                input.value = data_form[key] ?? ""
            });
        }

        function close_modal_table(id_modal) {
            const current_modal = document.getElementById(id_modal)
            current_modal.close()
        }
    </script>
@endpush
{{-- 
    Vars
 --}}
@php
    $header_columns = $datas['header_columns'] ?? collect([]);
    $data_row = $datas['data_row'] ?? collect([]);
    $is_data_empty = $datas['is_empty'];
    function validate_route($route)
    {
        $current_route = Illuminate\Support\Facades\Route::getRoutes()->getByName($route);
        return $current_route !== null;
    }
    function create_action($route)
    {
        return validate_route($route) ? route($route) : '';
    }
    function update_action($route, $data = [])
    {
        return validate_route($route) ? route($route, $data) : '';
    }
    function delete_action($route, $data = [])
    {
        return validate_route($route) ? route($route, $data) : '';
    }
@endphp
<div class="flex flex-col gap-6 w-full">
    <div class="flex flex-row flex-wrap gap-4 justify-between">
        {{-- 
            Search bar
         --}}
        @if ($search_bar)
            <div class="flex flex-row items-center">
                <form method="GET">
                    <label class="join">
                        <input type="search" @if ($is_data_empty) disabled @endif name="search"
                            placeholder="Cari data..." class="input join-item max-w-xs" value="{{ request('search') }}" />
                        <button class="btn btn-secondary join-item" @if ($is_data_empty) disabled @endif>
                            <x-lucide-search class="w-4" />
                        </button>
                    </label>
                </form>
            </div>
        @endif
        {{-- 
            Add button
         --}}
        @if (!$view_mode)
            <div class="flex flex-row items-center">
                <a @if (validate_route("{$route}.create")) href="{{ $create_route ?? route("{$route}.create") }}" @endif
                    @if ($action_modal) onclick="open_modal_table(this, 'modal-add')" @endif
                    data-action="@if (validate_route('{$route}.add')) href='{{ $create_route ?? route('{$route}.add') }}' @endif"
                    data-title="Tambah data {{ $datas['title_name'] }}" class="btn btn-primary">
                    <x-lucide-circle-plus class="w-4" />
                    <span>{{ $add_label }}</span>
                </a>
            </div>
        @endif
    </div>
    <div class="overflow-x-auto w-full border rounded-box border-base-300">
        <table class="table">
            {{-- 
                Header Table
             --}}
            <thead>
                <tr>
                    <th>#</th>
                    @foreach ($header_columns as $row => $label)
                        <th>{{ $label }}</th>
                    @endforeach
                    @if (!$view_mode)
                        <th class="text-end">Action</th>
                    @endif
                </tr>
            </thead>

            {{--
                Data Row
             --}}
            <tbody>
                @if (!empty($data_row->first()))
                    @foreach ($data_row as $data)
                        <tr
                            class="hover:bg-base-200 transition-all @if (auth()->user()->email === $data['email']) bg-accent/25 @endif">
                            <td>{{ $loop->iteration + ($data_row->currentPage() - 1) * $data_row->perPage() }}</td>
                            @foreach ($header_columns as $row => $label)
                                @php
                                    // Handle relasional data
                                    $relation = explode('.', $row);
                                    // Handle virtual data
                                    $virtual = explode('-', $row);
                                @endphp
                                {{-- If has relations --}}
                                @if (count($relation) > 1)
                                    @php
                                        $accessor = $relation[0] ?? '';
                                        $property = $relation[1] ?? '';
                                        $current_data = Str::of($data->{$accessor}->{$property})
                                            ->replace('_', ' ')
                                            ->ucfirst();
                                    @endphp
                                    @if (count($data->{$accessor}->get()) > 1)
                                        @foreach ($data->{$accessor} as $item)
                                            @php
                                                $current_item = Str::of($item->{$property})
                                                    ->replace('_', ' ')
                                                    ->ucfirst();
                                            @endphp
                                            <td>{{ $current_item }}</th>
                                        @endforeach
                                    @else
                                        <td>{{ $current_data }}</th>
                                    @endif
                                @elseif(count($virtual) > 1)
                                    @php
                                        $virtual_column = $virtual[0] ?? '';
                                        $current_data = Str::of($data->{$virtual_column})
                                            ->replace('_', ' ')
                                            ->ucfirst();
                                    @endphp
                                    <td>{{ $current_data }}</th>
                                    @else
                                        {{-- If doesnt has relations, echo data --}}
                                    <td>{{ $data->{$row} }}</th>
                                @endif
                            @endforeach

                            {{-- 
                                Action row button
                             --}}
                            @if (!$view_mode)
                                <th>
                                    <span class="flex flex-row justify-end items-center">
                                        @if (auth()->user()->email !== $data['email'])
                                            <a @if (validate_route("{$route}.view")) href="{{ route("{$route}.view" ?? '', ['id' => $data['id']]) }}" @endif
                                                @if ($action_modal) onclick="open_modal_table(this, 'modal-view')" @endif
                                                data-id="{{ $data['id'] }}"
                                                data-title="Detail data {{ $datas['title_name'] }}"
                                                data-item='@json($data)'
                                                class="btn btn-sm btn-link link-hover">
                                                <x-lucide-eye class="w-4" />
                                                {{ __('crud.action.detail') }}
                                            </a>
                                            <a @if (validate_route("{$route}.edit")) href="{{ route("{$route}.edit" ?? '', ['id' => $data['id']]) }}" @endif
                                                @if ($action_modal) onclick="open_modal_table(this, 'modal-edit')" @endif
                                                data-id="{{ $data['id'] }}"
                                                data-title="Edit data {{ $datas['title_name'] }}"
                                                data-item='@json($data)'
                                                data-action="{{ route($route . '.update', ['id' => $data['id']]) }}"
                                                class="btn btn-sm btn-link link-hover">
                                                <x-lucide-pen class="w-4" />
                                                {{ __('crud.action.edit') }}
                                            </a>
                                            <button
                                                data-title="{{ __('crud.delete_confirm', ['item' => 'data ' . $data['name']]) }} "
                                                data-action="{{ route($route . '.delete', ['id' => $data['id']]) }}"
                                                onclick="open_modal_table(this, 'modal-delete')"
                                                class="btn btn-sm btn-link link-hover">
                                                <x-lucide-trash class="w-4" />
                                                {{ __('crud.action.delete') }}
                                            </button>
                                        @else
                                            <a href="{{ route('admin.index') }}">
                                                <span class="badge badge-accent">Akun anda</span>
                                            </a>
                                        @endif
                                    </span>
                                </th>
                            @endif
                        </tr>
                    @endforeach
                @else
                    <tr class="text-center h-32">
                        <td colspan="@js(count($header_columns) + 2)">
                            <div class="flex flex-col gap-2  items-center">
                                <x-lucide-circle-x class="w-8 opacity-75" />
                                {{ __('crud.no_data', ['item' => 'data']) }}
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
        @if (!empty($data_row->first()))
            <div class="p-4">
                {{ $data_row->links() }}
            </div>
        @endif
    </div>
</div>

{{-- 
    Modal actions
--}}

{{-- Modal add --}}
<dialog id="modal-add" class="modal">
    <div class="modal-box">
        <h3 id="modal-title"></h3>
        <div class="modal-action">
            <form id="modal-form" method="POST" class="grid grid-cols-1 gap-2 w-full">
                @method('post')
                @csrf
                @if ($context)
                    <input type="hidden" name="kelompok_id" value="{{ $context['id'] }}">
                @endif
                {{-- fields --}}
                @foreach ($header_columns as $column => $title)
                    @php
                        $column_pattern = App\Services\UI\TypeColumn::inputAttributes($column);
                    @endphp
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">{{ $title }}</legend>
                        <input type="{{ $column_pattern['type'] ?? 'text' }}" name="{{ $column }}"
                            placeholder="Masukan {{ $title }}" class="input w-full validator"
                            maxlength="{{ $column_pattern['max_length'] }}" max="{{ $column_pattern['max_length'] }}"
                            pattern="{{ $column_pattern['regex'] ?? '' }}" value="{{ old($column) }}" required />
                        <p class="validator-hint hidden">
                            {{ __('validation.required', ['attribute' => $title]) }}
                        </p>
                        @error('name')
                            <p class="fieldset-label">{{ $message }}</p>
                        @enderror
                    </fieldset>
                @endforeach
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <button type="submit" class="btn btn-primary w-full">{{ __('crud.action.create') }}</button>
                    <button type="reset" onclick="close_modal_table('modal-add')"
                        class="btn btn-neutral w-full">{{ __('crud.action.cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

{{-- Modal view --}}
<dialog id="modal-view" class="modal">
    <div class="modal-box">
        <h3 id="modal-title"></h3>
        <div class="modal-action">
            <div id="modal-form"class="grid grid-cols-1 gap-2 w-full">
                {{-- fields --}}
                @foreach ($header_columns as $column => $title)
                    @php
                        $column_pattern = App\Services\UI\TypeColumn::inputAttributes($column);
                    @endphp
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">{{ $title }}</legend>
                        <input type="{{ $column_pattern['type'] ?? 'text' }}" name="{{ $column }}"
                            placeholder="Masukan {{ $title }}" class="input w-full validator" readonly />
                        <p class="validator-hint hidden">
                            {{ __('validation.required', ['attribute' => $title]) }}
                        </p>
                        @error('name')
                            <p class="fieldset-label">{{ $message }}</p>
                        @enderror
                    </fieldset>
                @endforeach
                <div class="grid grid-cols-1 gap-4 mt-4">
                    <button type="reset" onclick="close_modal_table('modal-view')"
                        class="btn btn-neutral w-full">{{ __('crud.action.back') }}</button>
                </div>
            </div>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

{{-- Modal edit --}}
<dialog id="modal-edit" class="modal">
    <div class="modal-box">
        <h3 id="modal-title"></h3>
        <div class="modal-action">
            <form id="modal-form" method="POST" class="grid grid-cols-1 gap-2 w-full">
                @method('put')
                @csrf
                @if ($context)
                    <input type="hidden" name="kelompok_id" value="{{ $context['id'] }}">
                @endif
                {{-- fields --}}
                @foreach ($header_columns as $column => $title)
                    @php
                        $column_pattern = App\Services\UI\TypeColumn::inputAttributes($column);
                    @endphp
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">{{ $title }}</legend>
                        <input type="{{ $column_pattern['type'] ?? 'text' }}" name="{{ $column }}"
                            placeholder="Masukan {{ $title }}" class="input w-full validator"
                            maxlength="{{ $column_pattern['max_length'] }}"
                            max="{{ $column_pattern['max_length'] }}" pattern="{{ $column_pattern['regex'] ?? '' }}"
                            value="{{ old($column) }}" required />
                        <p class="validator-hint hidden">
                            {{ __('validation.required', ['attribute' => $title]) }}
                        </p>
                        @error('name')
                            <p class="fieldset-label">{{ $message }}</p>
                        @enderror
                    </fieldset>
                @endforeach
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <button type="submit" class="btn btn-primary w-full">{{ __('crud.action.save') }}</button>
                    <button type="reset" onclick="close_modal_table('modal-edit')"
                        class="btn btn-neutral w-full">{{ __('crud.action.cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

{{-- Modal Delete --}}
<dialog id="modal-delete" class="modal">
    <div class="modal-box">
        <x-lucide-circle-alert class="w-24 p-4 mx-auto text-error" />
        <h6 id="modal-title"></h6>
        <div class="modal-action">
            <form id="modal-form" method="POST" class="grid grid-cols-2 gap-2 w-full">
                @method('delete')
                @csrf
                @if ($context)
                    <input type="hidden" name="kelompok_id" value="{{ $context['id'] }}">
                @endif
                <button type="submit" class="btn btn-error w-full">{{ __('crud.action.delete') }}</button>
                <button type="reset" onclick="close_modal_table('modal-delete')"
                    class="btn btn-neutral w-full">{{ __('crud.action.cancel') }}</button>
            </form>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

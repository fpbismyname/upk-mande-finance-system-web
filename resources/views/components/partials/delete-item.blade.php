@props(['item' => '', 'route' => ''])
<div class="dropdown dropdown-start md:dropdown-end">
    <div tabindex="0" class="btn btn-error" role="button">
        {{ __('crud.delete', ['item' => $item]) }}
    </div>
    <div tabindex="-1" class="dropdown-content menu w-64 p-4 rounded-box bg-base-200">
        <div class="mb-4">
            {{ __('crud.delete_confirm', ['item' => $item]) }}
        </div>
        <li>
            <button class="btn btn-error"
                onclick="window.submit_form('delete-{{ Str::lower($item) }}')">{{ __('crud.action.delete') }}</button>
        </li>
    </div>
</div>
<form id="delete-{{ Str::lower($item) }}" action="{{ $route }}" method="post" hidden>
    @csrf
    @method('delete')
</form>

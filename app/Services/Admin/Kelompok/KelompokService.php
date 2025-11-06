<?php

namespace App\Services\Admin\Kelompok;
use App\Models\Kelompok;
use App\Services\Utils\Result;
use Illuminate\Support\Facades\DB;

class KelompokService
{
    public function add_kelompok($datas)
    {
        if (empty($datas))
            return false;
        $added_kelompok = DB::transaction(function () use ($datas) {
            return Kelompok::create($datas);
        });
        if ($added_kelompok->wasRecentlyCreated) {
            return Result::success(__('crud.create_success', ['item' => $added_kelompok->name]));
        }
        return Result::error(__('crud.create_failed', ['item' => $added_kelompok->name]));
    }
    public function update_kelompok($datas, $id)
    {
        if (empty($datas) || empty($id))
            return false;
        $updated_kelompok = DB::transaction(function () use ($datas, $id) {
            $current_kelompok = Kelompok::findOrFail($id);
            $current_kelompok->fill($datas);
            $current_kelompok->save();
            return $current_kelompok;
        });
        if ($updated_kelompok) {
            return Result::success(__('crud.update_success', ['item' => $updated_kelompok->name]));
        }
        return Result::error(__('crud.update_failed', ['item' => $updated_kelompok->name]));
    }
    public function delete_kelompok($id)
    {
        if (empty($id))
            return false;
        $deleted_kelompok = DB::transaction(function () use ($id) {
            $current_kelompok = Kelompok::findOrFail($id);
            $current_kelompok->delete();
            return $current_kelompok;
        });
        if ($deleted_kelompok) {
            return Result::success(__('crud.delete_success', ['item' => $deleted_kelompok->name]));
        }
        return Result::error(__('crud.delete_failed', ['item' => $deleted_kelompok->name]));
    }
}
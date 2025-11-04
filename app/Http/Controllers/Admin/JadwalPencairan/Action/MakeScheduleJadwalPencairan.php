<?php

namespace App\Http\Controllers\Admin\JadwalPencairan\Action;

use App\Enum\Admin\Status\EnumStatusJadwalPencairan;
use App\Http\Controllers\Controller;
use App\Services\Admin\JadwalPencairan\JadwalPencairanService;
use App\Services\UI\Toast;
use Illuminate\Http\Request;

class MakeScheduleJadwalPencairan extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $id, JadwalPencairanService $jadwal_pencairan_service)
    {
        $schedule_datas = $request->validate([
            'tanggal_pencairan' => 'required|date',
        ]);

        $make_schedule = $jadwal_pencairan_service->make_schedule($id, $schedule_datas);

        if ($make_schedule) {
            Toast::show(__('crud.update_success', ['item' => 'Jadwal pencairan']));
        } else {
            Toast::show(__('crud.update_failed', ['item' => 'Jadwal pencairan']));
        }

        return redirect()->back();
    }
}

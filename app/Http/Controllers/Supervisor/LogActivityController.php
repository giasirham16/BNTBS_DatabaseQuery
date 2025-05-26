<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class LogActivityController extends Controller
{
    public function index()
    {
        $logActivities = LogActivity::get();
        return view('supervisor.LogActivity')->with('data', $logActivities);
    }

    public function exportCsv()
    {
        $fileName = 'log_activity_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = [
            'ID',
            'Nama DB',
            'IP Host',
            'Port',
            'Driver',
            'Query Request',
            'Query Result',
            'Deskripsi',
            'Reason',
            'Performed By',
            'Role',
            'Status Approval',
            'Menu',
            'Action',
            'Tanggal Dibuat'
        ];

        $callback = function () use ($columns) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $columns); // Header

            $data = LogActivity::all();

            foreach ($data as $item) {
                fputcsv($handle, [
                    $item->id,
                    $item->namaDB,
                    $item->ipHost,
                    $item->port,
                    $item->driver,
                    $item->queryRequest,
                    $item->queryResult,
                    $item->deskripsi,
                    $item->reason,
                    $item->performedBy,
                    $item->role,
                    $this->getStatusLabel($item->menu, $item->statusApproval),
                    $item->menu,
                    $item->action,
                    $item->created_at,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Helper status label
    private function getStatusLabel($menu, $status)
    {
        $labels = [
            'Query' => [
                0 => 'Menunggu approval checker',
                1 => 'Menunggu approval supervisor',
                2 => 'Approved',
                3 => 'Reject by checker',
                4 => 'Reject by supervisor',
            ],
            'Database' => [
                0 => '(Add) Menunggu approval checker',
                1 => '(Add) Menunggu approval supervisor',
                2 => 'Approved',
                3 => '(Update) Menunggu approval checker',
                4 => '(Update) Menunggu approval supervisor',
                5 => '(Delete) Menunggu approval checker',
                6 => '(Delete) Menunggu approval supervisor',
                7 => 'Direject checker',
                8 => 'Direject supervisor',
            ],
        ];

        return $labels[$menu][$status] ?? 'Tidak diketahui';
    }
}

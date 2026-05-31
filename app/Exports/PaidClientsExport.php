<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaidClientsExport implements FromView, ShouldAutoSize, WithStyles
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
        ];
    }

    public function view(): View
    {
        return view('exports.paid_clients', [
            'paidClients' => $this->data['paidClients'],
            'totalAmount' => $this->data['totalAmount'],
            'totalPackage' => $this->data['totalPackage'],
            'totalAddons' => $this->data['totalAddons']
        ]);
    }
}

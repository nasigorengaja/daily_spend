<?php

namespace App\Exports;

use App\Models\Spend;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Log;

class SpendsExport implements FromCollection, WithHeadings
{
    protected $user_id;
    protected $startDate;
    protected $endDate;

    public function __construct($user_id, $startDate = null, $endDate = null)
    {
        $this->user_id = $user_id;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Spend::where('user_id', $this->user_id);

        if ($this->startDate && $this->endDate) {
            Log::info('Fetching spend data with date range', ['start' => $this->startDate, 'end' => $this->endDate]);
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
        } else {
            Log::info('Fetching spend data without date range');
        }

        $data = $query->get();
        Log::info('Fetched data count', ['count' => $data->count()]);
        return $data;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Amount',
            'User ID',
            'Created At',
            'Updated At',
        ];
    }
}

<?php

namespace App\Exports;

use App\Models\Spend;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SpendsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $userId = Auth::id(); // Get the authenticated user's ID
        return Spend::where('user_id', $userId)->get();
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



<?php

namespace App\Imports;

use App\Models\Spend;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SpendsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Spend([
            'name' => $row['name'],
            'amount' => $row['amount'],
            'user_id' => $row['user_id'],
            'created_at' => $row['created_at'],
            'updated_at' => $row['updated_at'],
        ]);
    }
}


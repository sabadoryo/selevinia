<?php

namespace App\Imports;

use App\Models\Author;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;

class AuthorsImport implements ToModel, SkipsOnFailure, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Author([
            'full_name' => $row[1],
            'about'    => $row[2], 
            'articles' => $row[3],
        ]);
    }

     /**
     * @return int
     */
    public function onFailure(...$failures)
    {
    // Handle the failures how you'd like.
    }

    public function startRow(): int
    {
        return 3;
    }
}

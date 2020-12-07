<?php

namespace App\Imports;

use App\Models\ResData;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;


class ResDataImport implements ToCollection
{

//    public function ToCollection(Collection $rows)
//    {
//        //如果需要去除表头
////        unset($rows[0]);
////        var_dump($rows);
////        die();
//        return $rows;
//    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        //
        unset($rows[0]);
//        var_dump(json_encode($collection));
//        die();
        return $rows;

    }
}

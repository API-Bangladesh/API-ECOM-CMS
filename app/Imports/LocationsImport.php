<?php

namespace App\Imports;

use App\Models\Location;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LocationsImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Location([
            'retail_code'       => $row['retail_code'],
            'name'              => $row['name'],
            'owner_name'        => $row['owner_name'],
            'postal_code'       => $row['postal_code'],
            'address'           => $row['address'],
            'zone'              => $row['zone'],
            'sales_person'      => $row['sales_person'],
            'phone'             => $row['phone'],
            'division'          => $row['division'],
            'district'          => $row['district'],
            'lat'               => $row['lat'],
            'long'              => $row['long'],
        ]);
    }
}

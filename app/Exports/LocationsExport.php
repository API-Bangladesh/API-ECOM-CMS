<?php

namespace App\Exports;


use App\Models\Location;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LocationsExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
         $location = Location::select('retail_code','name','owner_name','postal_code','address','zone','sales_person','phone','division','district','lat','long')->get();

         return $location;
    }
    public function headings(): array
    {
        return [
            'retail_code',
            'name',
            'owner_name',
            'postal_code',
            'address',
            'zone',
            'sales_person',
            'phone',
            'division',
            'district',
            'lat',
            'long',

        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Exports\LocationsExport;
use App\Imports\LocationsImport;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;

class LocationImoportExportController extends Controller
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function importExportView()
    {
        $data = [
            'locations' => Location::all(),
        ];
        return view('import',$data);
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function export()
    {
        return Excel::download(new LocationsExport, 'location-list.xlsx');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function import(Request $request)
    {
        Excel::import(new LocationsImport,$request->file('file'));

        return Redirect::route('location');
    }
}

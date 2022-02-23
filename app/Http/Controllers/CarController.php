<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Exports\CarsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class CarController extends Controller
{
    public function export() 
        {
            return Excel::download(new CarsExport, 'cars.xlsx');
        }

}

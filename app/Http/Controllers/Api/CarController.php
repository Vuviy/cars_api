<?php

namespace App\Http\Controllers\Api;

use App\Models\Car;
use App\Exports\CarsExport;
use Illuminate\Http\Request;
use App\Http\Resources\CarResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\CarStoreRequest;
use App\Http\Requests\CarUpdateRequest;

class CarController extends Controller
{
    protected function parseVIN($request){
        $response = Http::get('https://vpic.nhtsa.dot.gov/api/vehicles/decodevin/' . $request->VIN . '?format=json');
        $data = $request->all();
        foreach ($response['Results'] as $item) {
            switch ($item['VariableId']) {
                case '26':
                $data['make'] = $item['Value'];
                    break;
                case '28':
                $data['model'] = $item['Value'];
                    break;
                case '29':
                $data['year'] = $item['Value'];
                    break;
            }
        }
        return $data;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(isset(request()->export)){
            return response()->json(['url' => route('export', request()->all())]);
        }
        return CarResource::collection(Car::searchSortFilter((array)request()->all()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CarStoreRequest $request)
    {

        $data = $this->parseVIN($request);

        if(!$data['model'] || !$data['make'] || !$data['year']){
            $res = ['status' => 'failed', 'response' => 'Not found VIN'];
            return response()->json($res);
        }

        $added_car = Car::create($data);
        return new CarResource($added_car);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Car::find($id)){
            return new CarResource(Car::findOrFail($id));
        }
        else{
            $res = ['status' => 'failed', 'response' =>'Not found'];
            return response()->json($res);

        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CarUpdateRequest $request, $id)
    {
        $car = Car::findOrFail($id);
        if($request->VIN){
            $data = $this->parseVIN($request);

            if(!$data['model'] || !$data['make'] || !$data['year']){
                $res = ['status' => 'failed', 'response' => 'Not found VIN'];
                return response()->json($res);
            }
            $car->update($data);
        }
        $car->update($request->all());
        return new CarResource(Car::findOrFail($id));
        }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Car::destroy($id);
        $res = ['status' => 'ok', 'response' => 'deleted'];
        return response()->json($res);
    }
}

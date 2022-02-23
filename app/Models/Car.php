<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'num', 'color', 'VIN', 'make', 'model', 'year'];

    /**
     * Top level of categories
     *
     * @return Car[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|Collection
     */
    public static function searchSortFilter($arr = [])
    {
        if(isset($arr['sort'])){
            $query = Car::orderBy($arr['sort']);
        } else {
            $query = Car::orderBy('id');
        }
        if(isset($arr['search'])){
            $query->orWhere('name', 'like', '%' . $arr['search'] . '%');
            $query->orWhere('VIN', 'like', '%' . $arr['search'] . '%');
            $query->orWhere('num', 'like', '%' . $arr['search'] . '%');
        }
        if(isset($arr['make'])){
            $query->where('make', 'like', '%' . $arr['make'] . '%');
        }
        if(isset($arr['model'])){
            $query->where('model', 'like', '%' . $arr['model'] . '%');
        }
        if(isset($arr['year'])){
            $query->where('year', 'like', '%' . $arr['year'] . '%');
        }
        return $query->paginate(10);
    }

}

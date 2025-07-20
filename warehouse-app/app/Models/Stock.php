<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{
    //
    use HasFactory; 
    protected $table = "stocks";
    protected $fillable = ["stock"];

    public $timestamps = false;


    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function stocks()
    {
        return $this->belongsToMany(Warehouse::class);
    }
}

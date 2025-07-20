<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    //
    use HasFactory;
    protected $table = "warehouses";
    protected $fillable = ['name'];

    public $timestamps = false;

    public function stocks(){
        return $this->belongsToMany(Stock::class);
    }
    /*
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'stocks')
            ->withPivot('stock');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }*/


}

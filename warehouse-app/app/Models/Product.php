<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Product extends Model
{
    //
    use HasFactory; 
    protected $table = "products";
    protected $fillable = ["name", "price"];

    public $timestamps = false;

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    /*public function warehouses() {
        return $this->belongsToMany(Warehouse::class, 'stocks')->withPivot('stocks');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }*/
}

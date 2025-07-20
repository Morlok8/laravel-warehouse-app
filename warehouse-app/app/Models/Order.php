<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Order extends Model
{
    //
    use HasFactory; 
    protected $table = "orders";
    protected $fillable = ['customer', 'warehouse_id', 'status'];
    protected $dates = ['completed_at'];

    protected  $primaryKey = 'id';

    public $timestamps = false;


    public function products(){
        return $this->belongsToMany(Product::class);
    }

    public function warehouse(){
        return $this->belongsToMany(Warehouse::class);
    }

    /* public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(Product::class, OrderItem::class, 'order_id', 'id', 'id', 'product_id');
    }*/
}

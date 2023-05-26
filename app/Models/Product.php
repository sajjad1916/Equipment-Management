<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, Sortable;

    protected $fillable = [
        'product_name',
        'category_id',
        'unit_id',
        'product_code',
        'stock',
        'buying_price',
        'product_image',
        'productApproved_id',
        'productScrapped_id',
        'maintenance_id',
    ];

    
    public $sortable = [
        'product_name',
        'category_id',
        'unit_id',
        'product_code',
        'stock',
        'buying_price',
        'productApproved_id',
        'productScrapped_id',
        'maintenance_id',
    ];

    protected $guarded = [
        'id',
    ];

    protected $with = [
        'category',
        'unit',
    ];

    public function maintenance(){
        return $this->belongsTo(Maintenance::class, 'maintenance_id');
    }

    public function productApproved(){
        return $this->belongsTo(ProductApproved::class, 'productApproved_id');
    }

    public function productScrapped(){
        return $this->belongsTo(ProductScrapped::class, 'productScrapped_id');
    }

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function unit(){
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('product_name', 'like', '%' . $search . '%');
        });
    }
}

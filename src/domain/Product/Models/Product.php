<?php

namespace Domain\Product\Models;

use Database\Factories\Product\ProductFactory;
use Domain\Brand\Models\Brand;
use Domain\Category\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Shared\Traits\Uuid;
use Domain\Product\Models\ProductDetail;
use \Domain\Product\Models\ProductBrand;

class Product extends Model
{
    use HasFactory;
    use Uuid;
    use SoftDeletes;

    protected $table = 'products';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'title',
        'description',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }

    public function productDetail(): HasOne
    {
        return $this->hasOne(ProductDetail::class);
    }

    public function productColors(): HasManyThrough
    {
        return $this->hasManyThrough(ProductColor::class, ProductDetail::class);
    }

    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class, 'product_brand')
            ->using(new class extends Pivot {
                use Uuid;
            });
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_category')
            ->using(new class extends Pivot {
               use Uuid;
            });
    }
}

<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
  use HasFactory;
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'status',
    'title',
    'title_seo',
    'desc',
    'desc_seo',
    'img',
    'price',
    'color',
    'size',
    'weight',
    'qty',
    'views',
    'created_by',
    'user_id',
  ];

  public function categories()
  {
    return $this->hasMany(ProductsCategories::class);
  }
  public function variations()
  {
    return $this->hasMany(ProductsVariations::class);
  }
}

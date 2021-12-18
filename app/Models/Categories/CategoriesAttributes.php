<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriesAttributes extends Model
{
  use HasFactory;
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['key_en', 'key_ar', 'icon', 'categorie_id'];
}

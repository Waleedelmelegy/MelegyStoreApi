<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
  use HasFactory;
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name_en',
    'name_ar',
    'desc_en',
    'desc_ar',
    'desc_seo',
    'user_id',
    'created_by',
    'section_id',
  ];
}

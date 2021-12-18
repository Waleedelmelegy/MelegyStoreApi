<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sections extends Model
{
    use HasFactory;

      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_en', 'name_ar', 'desc_en', 'desc_ar', 'desc_seo', 'user_id', 'created_by'
    ];

     public function SectionCategories()
    {
        return $this->hasMany(Categories::class);
    }
}

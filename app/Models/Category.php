<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // SOLUSI: Mengizinkan semua field (termasuk name dan slug) masuk ke database
    protected $guarded = [];

    /**
     * RELASI: Satu Kategori bisa memiliki banyak Partner
     * Nama fungsi ini harus 'partners' agar sesuai dengan withCount('partners') di Controller
     */
    public function partners()
    {
        return $this->hasMany(Partner::class, 'category_id');
    }
}
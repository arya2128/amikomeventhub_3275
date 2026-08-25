<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'user_id', 'title', 'description', 'date',
        'location', 'price', 'stock', 'poster_path'
    ];


    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    public function getPosterUrlAttribute(): string
    {
        if (!empty($this->poster_path)) {
            if (str_starts_with($this->poster_path, 'http://') || str_starts_with($this->poster_path, 'https://')) {
                return $this->poster_path;
            }
            return asset('storage/' . $this->poster_path);
        }

        $text = strtolower($this->title . ' ' . ($this->category->name ?? ''));
        if (str_contains($text, 'hackaton') || str_contains($text, 'hackathon') || str_contains($text, 'developer') || str_contains($text, 'coding')) {
            return asset('assets/hackathon.png');
        }
        if (str_contains($text, 'ai') || str_contains($text, 'workshop') || str_contains($text, 'summit') || str_contains($text, 'tech')) {
            return asset('assets/workshop.png');
        }
        return asset('assets/concert.png');
    }
}



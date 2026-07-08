<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Resep extends Model
{
    use HasFactory;

    protected $table = 'resep';
    protected $primaryKey = 'id_resep';
    public $timestamps = false;

    protected $fillable = [
        'judul',
        'slug',
        'kategori',
        'deskripsi',
        'bahan',
        'langkah',
        'tips',
        'foto',
        'durasi_menit',
        'porsi',
        'kesulitan',
        'rating',
        'jumlah_rating',
        'likes',
        'user_id',
    ];

    protected $casts = [
        'bahan'   => 'array',
        'langkah' => 'array',
        'rating'  => 'float',
    ];

    // ── Relasi ──────────────────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'resep_likes');
    }

    // ── Helper ──────────────────────────────────────────────────────────

    /** Apakah user yang sedang login sudah menyukai resep ini */
    public function isLikedBy(?User $user): bool
    {
        if (! $user) return false;
        return $this->likedByUsers()->where('user_id', $user->id)->exists();
    }

    /** Warna badge berdasarkan kategori */
    public function kategoriBadgeColor(): string
    {
        return match ($this->kategori) {
            'Makanan Berat'  => '#F4A623',
            'Makanan Ringan' => '#4CAF50',
            'Dessert'        => '#E91E8C',
            'Minuman'        => '#2196F3',
            default          => '#888',
        };
    }

    /** Warna teks badge */
    public function kategoriBadgeText(): string
    {
        return match ($this->kategori) {
            'Makanan Berat'  => '#5A2D00',
            'Makanan Ringan' => '#1B5E20',
            'Dessert'        => '#4A0028',
            'Minuman'        => '#0D2F5E',
            default          => '#fff',
        };
    }

    /** Label emoji kesulitan */
    public function kesulitanEmoji(): string
    {
        return match ($this->kesulitan) {
            'Mudah' => '🟢 Mudah',
            'Sulit' => '🔴 Sulit',
            default => '🟡 Sedang',
        };
    }

    // ── Auto-slug saat disimpan ──────────────────────────────────────────
    protected static function booted(): void
    {
        static::creating(function (Resep $resep) {
            if (empty($resep->slug)) {
                $resep->slug = Str::slug($resep->judul);
            }
        });
    }
}

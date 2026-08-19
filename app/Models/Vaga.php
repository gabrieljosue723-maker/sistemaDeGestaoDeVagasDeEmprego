<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaga extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titulo',
        'descricao',
        'localizacao',
    ];

    public function empresa()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function candidaturas()
    {
        return $this->hasMany(Candidatura::class);
    }

    public function scopeBusca($query, string $termo)
    {
        return $query->where(function ($sub) use ($termo) {
            $sub->where('titulo', 'like', "%{$termo}%")
                ->orWhere('descricao', 'like', "%{$termo}%")
                ->orWhere('localizacao', 'like', "%{$termo}%")
                ->orWhereHas('empresa', fn ($q) => $q->where('name', 'like', "%{$termo}%"));
        });
    }
}

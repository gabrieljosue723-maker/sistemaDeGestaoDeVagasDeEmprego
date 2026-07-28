<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vaga extends Model
{
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
        return $query->where('titulo', 'like', "%{$termo}%")
            ->orWhere('localizacao', 'like', "%{$termo}%");
    }
}
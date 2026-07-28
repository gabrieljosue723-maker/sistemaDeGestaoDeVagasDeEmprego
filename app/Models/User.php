<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'tipo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function vagas()
    {
        return $this->hasMany(Vaga::class);
    }

    public function candidaturas()
    {
        return $this->hasMany(Candidatura::class);
    }

    public function isEmpresa(): bool
    {
        return $this->tipo === 'Empresa';
    }

    public function isCandidato(): bool
    {
        return $this->tipo === 'Candidato';
    }
}
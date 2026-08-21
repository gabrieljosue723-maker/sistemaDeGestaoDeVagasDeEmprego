<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'tipo',
        'anos_experiencia',
        'localizacao',
        'formacao',
        'disponibilidade',
        'bio',
        'curriculo_path',
        'curriculo_nome_original',
        'foto_path',
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

    public function skills()
    {
        return $this->belongsToMany(Skill::class);
    }

    public function temCurriculo(): bool
    {
        return ! empty($this->curriculo_path);
    }

    public function temFoto(): bool
    {
        return ! empty($this->foto_path);
    }

    public function fotoUrl(): ?string
    {
        return $this->temFoto() ? Storage::disk('public')->url($this->foto_path) : null;
    }

    /**
     * Filtra candidatos (tipo Candidato) por anos de experiência,
     * habilidades, localização, formação e disponibilidade.
     */
    public function scopeFiltrarCandidatos($query, array $filtros)
    {
        return $query->where('tipo', 'Candidato')
            ->when(
                $filtros['experiencia_min'] ?? null,
                fn ($q, $valor) => $q->where('anos_experiencia', '>=', (int) $valor)
            )
            ->when(
                $filtros['localizacao'] ?? null,
                fn ($q, $valor) => $q->where('localizacao', 'like', "%{$valor}%")
            )
            ->when(
                $filtros['formacao'] ?? null,
                fn ($q, $valor) => $q->where('formacao', 'like', "%{$valor}%")
            )
            ->when(
                $filtros['disponibilidade'] ?? null,
                fn ($q, $valor) => $q->where('disponibilidade', $valor)
            )
            ->when(
                $filtros['texto'] ?? null,
                fn ($q, $valor) => $q->where(function ($sub) use ($valor) {
                    $sub->where('name', 'like', "%{$valor}%")
                        ->orWhere('bio', 'like', "%{$valor}%");
                })
            )
            ->when(
                ! empty($filtros['skills']),
                fn ($q) => $q->whereHas(
                    'skills',
                    fn ($sub) => $sub->whereIn('skills.id', $filtros['skills'])
                )
            );
    }
}

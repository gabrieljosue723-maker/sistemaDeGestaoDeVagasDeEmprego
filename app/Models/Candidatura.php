<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidatura extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vaga_id',
        'status',
    ];

    public const STATUS_PENDENTE = 'pendente';

    public const STATUS_ACEITE = 'aceite';

    public const STATUS_REJEITADO = 'rejeitado';

    public static function statusValidos(): array
    {
        return [self::STATUS_PENDENTE, self::STATUS_ACEITE, self::STATUS_REJEITADO];
    }

    public function candidato()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vaga()
    {
        return $this->belongsTo(Vaga::class);
    }
}

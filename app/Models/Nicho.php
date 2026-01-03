<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nicho extends Model
{
    protected $fillable = [
        'sigla',
        'name',
        'description'
    ];

    /**
     * 🔒 Mapa oficial de siglas por nome de nicho
     */
    public const SIGLAS_OFICIAIS = [
        'Memória'        => 'MM',
        'Emagrecimento'  => 'WL',
        'E.D'            => 'ED',
        'Diabetes'       => 'DB',
        'Próstata'       => 'PR',
        'Visão'          => 'VS',
        'Neuropatia'     => 'NR',
        'Tinnitus'       => 'TN',
    ];

    /**
     * 🔁 Accessor: sempre retorna a sigla correta
     */
    public function getSiglaAttribute($value)
    {
        return self::SIGLAS_OFICIAIS[$this->name] ?? $value;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use DB;

class Personnel extends Model
{
    use HasFactory;

    protected $fillable = [
        'identifiant',
        'avatar',
        'nom',
        'prenom',
        'date_naissance',
        'sexe',
        'nb_retard',
        'nb_absent',
    ];

    protected static function boot(){
        parent::boot();
        static::creating(function($personnel){
          DB::transaction(function () use($personnel) {
              $dernier_personnel = Personnel::orderBy('id', 'desc')->first();
              $nouvelle_identifiant = $dernier_personnel ? $dernier_personnel->id + 1 : 1;
              $personnel->identifiant = "U-".$nouvelle_identifiant;
          });
        });
    }

    /**
     * Get the planning associated with the Personnel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function planning(): HasOne
    {
        return $this->hasOne(Planning::class, 'id_personnel', 'id');
    }

    /**
     * Get all of the reatrd for the Personnel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function retard(): HasMany
    {
        return $this->hasMany(Retard::class, 'id_personnel', 'id');
    }

    /**
     * Get all of the absent for the Personnel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function absent(): HasMany
    {
        return $this->hasMany(Absent::class, 'id_personnel', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Planning extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_personnel',
        'lundi_deb',
        'lundi_fin',
        'mardi_deb',
        'mardi_fin',
        'mercredi_deb',
        'mercredi_fin',
        'jeudi_deb',
        'jeudi_fin',
        'vendredi_deb',
        'vendedi_fin',
        'samedi_deb',
        'samedi_fin',
        'dimanche_deb',
        'dimanche_fin',
    ];


    /**
     * Get the personnel that owns the Planning
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'id_personnel', 'id');
    }
}

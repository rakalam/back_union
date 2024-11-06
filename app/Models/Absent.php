<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absent extends Model
{
    use HasFactory;
    
    protected $fillable = [

        'id_personnel',
        'date_absent',
        'jour',
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
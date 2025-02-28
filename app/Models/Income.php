<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_detail_id',
        'source',
        'amount',
        'month',
        'year',
    ];
    public function familyDetail()
    {
        return $this->belongsTo(FamilyDetail::class, 'family_detail_id');
    }

}

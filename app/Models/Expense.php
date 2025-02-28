<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_detail_id',
        'expense_name',
        'amount',
        'month',
        'year',
    ];
    public function familyDetail()
    {
        return $this->belongsTo(FamilyDetail::class, 'family_detail_id');
    }
}

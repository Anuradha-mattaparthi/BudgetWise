<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'family_name',
        'age',
        'relationship',
        'spouse_name',
        'children'
    ];
    public function expenses()
{
    return $this->hasMany(Expense::class, 'family_detail_id');
}


// Define the relationship with the Income model
public function incomes()
{
    return $this->hasMany(Income::class, 'family_detail_id');
}
}

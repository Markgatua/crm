<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solution extends Model
{
    use HasFactory;

    public function solutionType() {
        return $this->hasMany(SolutionType::class);
    }

    public function solutionSubType() {
        return $this->hasMany(SolutionSubType::class);
    }
}

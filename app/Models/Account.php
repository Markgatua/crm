<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_name', 'solution_name', 'contact_information', 'client_id', 'client_type_id', 'user_id'
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function client(){
        return $this->belongsTo('App\Models\Client');
    }

    public function stage(){
        return $this->belongsTo('App\Models\ProjectStageInformation');
    }

    public function latestStage()
    {
        return $this->hasOne(ProjectStageInformation::class, 'account_id', 'id')
                    ->latestOfMany('id');
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','email','phone','is_existing','location','industry_id','website_url','contact_information','comments','user_id'
    ];

    public function industry(){
		return $this->belongsTo('App\Models\Industry');
	}

    public function user(){
		return $this->belongsTo('App\Models\User');
	}
}

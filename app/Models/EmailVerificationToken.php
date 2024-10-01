<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerificationToken extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'email_verification_token';
    use HasFactory;
    protected $fillable = [
        'email',
        'token',
        'validuntil'
    ];
}

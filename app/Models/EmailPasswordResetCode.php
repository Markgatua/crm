<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailPasswordResetCode extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'email_password_reset_codes';
    use HasFactory;
    protected $fillable = [
        'email',
        'token',
        'validuntil'
    ];
}

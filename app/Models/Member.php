<?php

namespace App\Models;

use Database\Factories\MemberFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{
    /** @use HasFactory<MemberFactory> */
    use HasFactory;

    protected $table = 'member';

    protected $primaryKey = 'id_member';

    public $timestamps = false;

    protected $fillable = ['nama', 'alamat', 'telepon', 'jen_kelamin', 'username', 'password'];

    protected $hidden = ['password'];
}

<?php

namespace App\Models;

use Database\Factories\OperatorLoketFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class OperatorLoket extends Authenticatable
{
    /** @use HasFactory<OperatorLoketFactory> */
    use HasFactory;

    protected $table = 'operator_loket';

    protected $primaryKey = 'id_opr';

    public $timestamps = false;

    protected $hidden = ['password'];
}

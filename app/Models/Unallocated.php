<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unallocated extends Model
{
    use HasFactory;

    protected $table = 'unallocated';

    protected $primaryKey = null;

    public $incrementing = false;

    public $timestamps = false;
}

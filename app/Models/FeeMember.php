<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeMember extends Model
{
    use HasFactory;
    protected $table = 'feemembers';
    protected $guarded = ['id'];
}

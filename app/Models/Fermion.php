<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fermion extends Model
{
    use HasFactory;

    protected $table        = "fermion";
    protected $primaryKey   = "id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [

        'menu_name',
        'level',
        'parent_id',
    ];

}

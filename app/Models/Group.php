<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'capacidad',
    ];

    public function groupassignments()
    {
        return $this->hasMany(Groupassignment::class);
    }
}

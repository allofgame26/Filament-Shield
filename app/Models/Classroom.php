<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function classroom(){
        return $this->hasMany(homerooms::class);
    }

    public function subject(){
        return $this->belongsToMany(Subject::class)->withPivot('deskripsi');
    }
}

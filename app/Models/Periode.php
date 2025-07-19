<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function periode(){
        return $this->hasMany(homerooms::class,'periode_id','id');
    }

    public function periode_student(){
        return $this->hasMany(student_has_classrooms::class,'periode_id','id');
    }
}

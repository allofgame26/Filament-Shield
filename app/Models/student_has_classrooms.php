<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class student_has_classrooms extends Model
{
    use HasFactory;

    protected $guarded = [];

    

    public function students(){
        return $this->belongsTo(Student::class,'students_id','id'); 
    }

    public function homeroom(){
        return $this->belongsTo(homerooms::class,'homerooms_id','id');
    }

    public function periode(){
        return $this->belongsTo(Periode::class,'periode_id','id');
    }
}

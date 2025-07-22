<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'class_id','id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id','id');
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class, 'periode_id','id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id','id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id','id');
    }

    public function categoryNilai()
    {
        return $this->belongsTo(CategoryNilai::class, 'category_nilai_id','id');
    }
}

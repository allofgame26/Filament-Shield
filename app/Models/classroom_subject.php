<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class classroom_subject extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function subject(){
        return $this->belongsTo(Subject::class,'subject_id','id');
    }

    public function classroom(){
        return $this->belongsTo(classroom::class,'classroom_id','id');
    }
}

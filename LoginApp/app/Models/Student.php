<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = "student";
    protected $primaryKey = 'id'; 
    protected $fillable = ['name','teacher_id', 'class', 'phonenumber', 'state'];



    public function teacher()
    {
        // foreign key on students = teacher_id
        // owner key on users = teacher_id
        return $this->belongsTo(\App\Models\User::class, 'teacher_id', 'teacher_id');
    }

}
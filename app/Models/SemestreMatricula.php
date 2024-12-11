<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemestreMatricula extends Model
{
    use HasFactory;
    protected $table = 'semestre_matricula';

    protected $fillable  = [
        'matricula_id',
        'semestre_id',
        'nota',
        'trancado',
        'course_id',
        'status',
    ];
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}

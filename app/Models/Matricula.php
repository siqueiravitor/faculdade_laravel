<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;

    protected $table = 'matriculas'; 
    protected $fillable = [
        'user_id', 
        'codigo_matricula',
        // ... outros campos da matrícula, se houver ...
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'user_id');
    }
}
<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\SemestreMatricula;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeCurricularController extends Controller
{
    public function mostrarGrade()
    {
        $student = Student::where('user_id', auth()->id())->select('periodo')->first();
        
        $data['disciplinas'] = Enrollment::where('enrollments.user_id', auth()->id())
            ->join('courses', 'courses.id', '=', 'enrollments.course_id')
            ->join('students', 'students.user_id', '=', 'enrollments.user_id')
            ->where('courses.periodo', '=',$student['periodo'])
            ->paginate();
            
        return view('frontend.student.gradeDisciplina', $data);
    }
}

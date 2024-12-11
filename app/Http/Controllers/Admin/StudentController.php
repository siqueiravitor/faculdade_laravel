<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\Matricula;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Organization;
use App\Models\State;
use App\Models\Student;
use App\Models\User;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    use General, ImageSaveTrait;

    protected $studentModel;
    public function __construct(Student $student)
    {
        $this->studentModel = new Crud($student);
    }
    public function index()
    {
        $data['title'] = 'All Student';
        $users = User::where('role', 3)->pluck('id');
        // $data['students'] = $this->studentModel->getOrderById('DESC', 25)->whereIn('user_id', $users);
        $data['students'] = Student::select('students.*', 'matriculas.codigo_matricula')
        ->leftJoin('matriculas', 'students.user_id', '=', 'matriculas.user_id')
        ->whereIn('students.user_id', $users)
        ->orderBy('students.id', 'DESC')
        ->take(25)
        ->get();

        return view('admin.student.list', $data);
    }

    public function pending_list()
    {
        $data['title'] = 'Pending Student';
        $data['students'] = Student::where('status', STATUS_PENDING)->orderBy('id', 'DESC')->paginate(25);
        return view('admin.student.pending_list', $data);
    }

    public function create()
    {
        $data['title'] = 'Add Student';
        $data['countries'] = Country::orderBy('country_name', 'asc')->get();

        $data['organizations'] = Organization::orderBy('first_name', 'asc')->get();

        // Gere a matrícula
        $matricula = $this->generateMatricula();

        // Adicione a matrícula aos dados que serão enviados para a visão
        $data['matricula'] = $matricula;

        if (old('country_id')) {
            $data['states'] = State::where('country_id', old('country_id'))->orderBy('name', 'asc')->get();
        }

        if (old('state_id')) {
            $data['cities'] = City::where('state_id', old('state_id'))->orderBy('name', 'asc')->get();
        }
        return view('admin.student.add', $data);
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'first_name' => ['required', 'string', 'max:100'],
    //         'last_name' => ['required', 'string', 'max:100'],
    //         'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
    //         'password' => ['required', 'string', 'min:2'],
    //         'area_code' => 'required',
    //         'phone_number' => 'bail|numeric|unique:users,mobile_number',
    //         'address' => 'required',
    //         'gender' => 'required',
    //         'about_me' => 'required',
    //         'image' => 'mimes:jpeg,png,jpg|file|dimensions:min_width=300,min_height=300,max_width=300,max_height=300|max:1024'
    //     ]);

    //     $user = new User();
    //     $user->name = $request->first_name . ' '. $request->last_name;
    //     $user->email = $request->email;
    //     $user->area_code =  str_replace("+","",$request->area_code);
    //     $user->mobile_number = $request->phone_number;
    //     $user->phone_number = $request->phone_number;
    //     $user->email_verified_at = now();
    //     $user->password = Hash::make($request->password);
    //     $user->role = 3;
    //     $user->image =  $request->image ? $this->saveImage('user', $request->image, null, null) :   null;
    //     $user->save();

    //     $student_data = [
    //         'user_id' => $user->id,
    //         'first_name' => $request->first_name,
    //         'last_name' => $request->last_name,
    //         'address' => $request->address,
    //         'phone_number' => $user->phone_number,
    //         'country_id' => $request->country_id,
    //         'state_id' => $request->state_id,
    //         'city_id' => $request->city_id,
    //         'gender' => $request->gender,
    //         'about_me' => $request->about_me,
    //         'postal_code' => $request->postal_code,
    //     ];

    //     $this->studentModel->create($student_data);

    //     $this->showToastrMessage('success', __('Student created successfully'));
    //     return redirect()->route('student.index');
    // }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:2'],
            // 'area_code' => 'required',
            'phone_number' => 'bail|numeric|unique:users,mobile_number',
            // 'address' => 'required',
            // 'gender' => 'required',
            // 'about_me' => 'required',
            // 'matricula' => 'required|unique:users,matricula',
            // 'cpf' => 'required|cpf|unique:users,cpf',
            'image' => 'mimes:jpeg,png,jpg|file|dimensions:min_width=300,min_height=300,max_width=300,max_height=300|max:1024'
        ]);

        $matricula = $this->generateMatricula();

        $user = new User();
        $user->name = $request->first_name . ' ' . $request->last_name;
        $user->email = $request->email;
        $user->area_code = str_replace("+", "", $request->area_code);
        $user->mobile_number = $request->phone_number;
        $user->phone_number = $request->phone_number;
        $user->email_verified_at = now();
        $user->password = Hash::make($request->password);
        $user->role = 3;
        $user->matricula = $matricula;
        $user->cpf = $request->cpf;
        $user->created_at = auth()->user()->id;
        $user->image = $request->image ? $this->saveImage('user', $request->image, null, null) : null;
        $user->save();

        $student_data = [
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'phone_number' => $user->phone_number,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'gender' => $request->gender,
            'about_me' => $request->about_me,
            'postal_code' => $request->postal_code,
            'organization_id' => $request->organization_id,
            'created_at' => auth()->user()->id,
            'filiacao' => $request->filiacao,
            'rg' => $request->rg,
            'data_nascimento' => $request->data_nascimento,
            'estado_civil' => $request->estado_civil,
            'naturalidade' => $request->naturalidade,
            'titulo_eleitor' => $request->titulo_eleitor,
        ];

        $this->studentModel->create($student_data);

        Matricula::create([
            'user_id' => $user->id,
            'codigo_matricula' => $matricula,
        ]);

        $this->showToastrMessage('success', __('Student created successfully'));
        return redirect()->route('student.index');
    }

    public function generateMatricula()
    {
        // Lógica para gerar a matrícula com base no ano, semestre, e sequência numérica
        // Substitua isso com a lógica específica para o seu caso

        $ano = date('Y');
        $mes = date('n');

        // Determina o semestre com base no mês
        $semestre = ($mes <= 6) ? '01' : '02';

        $ultimaMatricula = Matricula::whereYear('created_at', $ano)
            ->max('codigo_matricula');

        $ultimaMatriculaNumero = $ultimaMatricula ? intval(substr($ultimaMatricula, 6)) : 0;

        // Gera o próximo número da matrícula
        $numeroMatricula = $ultimaMatriculaNumero + 1;

        // Formata o número da matrícula com zeros à esquerda (com no máximo 4 dígitos)
        $numeroMatriculaFormatado = sprintf('%04d', $numeroMatricula);

        // Cria o código completo da matrícula
        $codigoMatricula = $ano . $semestre . $numeroMatriculaFormatado;

        return $codigoMatricula;
    }


    public function view($uuid)
    {
        $data['title'] = 'Student Profile';
        $student = $this->studentModel->getRecordByUuid($uuid);

        if ($student) {
            // Carregue o usuário associado ao estudante
            $user = User::find($student->user_id);
            // Adicione os dados necessários ao array $data
            $polo = Organization::find($student->organization_id);
            $criador = User::find($student->criador);

            $data['student'] = $student;
            $data['user'] = [
                'polo' =>  $polo->first_name,
                'matricula' => $user->matricula,
                'cpf' => $this->formatarCPF($user->cpf),
                'rg' => $this->formatarRG($student->rg),
                'titulo' => $this->formatarTituloEleitor($student->titulo_eleitor),
                'criador' =>$criador ? $criador->name : null
            ];

            $data['student']['data_nascimento'] = \Carbon\Carbon::parse($student->data_nascimento)->format('d/m/Y');

            // Recupere as matrículas associadas ao usuário
            $data['enrollments'] = Enrollment::where('user_id', $user->id)
                ->whereNotNull('course_id')
                ->latest()
                ->paginate(15);
            return view('admin.student.view', $data);
        }
    }

    public function edit($uuid)
    {
        $data['title'] = 'Edit Student';
        $data['countries'] = Country::orderBy('country_name', 'asc')->get();
        $student = $this->studentModel->getRecordByUuid($uuid);
        // dd($student);
        if ($student) {
            // Carregue o usuário associado ao estudante
            $user = User::find($student->user_id);
            // Adicione os dados necessários ao array $data
            $polo = Organization::find($student->organization_id);
            $criador = User::find($student->criador);

            $data['student'] = $student;
            $data['user'] = [
                'polo' =>  $polo->first_name ?? '',
                'matricula' => $user->matricula ?? '',
                'cpf' => $this->formatarCPF($user->cpf) ?? '',
                'rg' => $this->formatarRG($student->rg) ?? '',
                'titulo' => $this->formatarTituloEleitor($student->titulo_eleitor) ?? '',
                'criador' => $criador->name ?? ''
            ];
        }

        return view('admin.student.edit', $data);
    }

    public function update(Request $request, $uuid)
    {
        $student = $this->studentModel->getRecordByUuid($uuid);

        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $student->user_id],
            // 'area_code' => 'required',
            'phone_number' => 'bail|numeric|unique:users,mobile_number,' . $student->user_id,
            // 'address' => 'required',
            // 'gender' => 'required',
            // 'about_me' => 'required',
            'image' => 'mimes:jpeg,png,jpg|file|dimensions:min_width=300,min_height=300,max_width=300,max_height=300|max:1024'
        ]);


        $user = User::findOrfail($student->user_id);
        if (User::where('id', '!=', $student->user_id)->where('email', $request->email)->count() > 0) {
            $this->showToastrMessage('warning', __('Email already exist'));
            return redirect()->back();
        }

        $user->name = $request->first_name . ' ' . $request->last_name;
        $user->email = $request->email;
        if ($request->password) {
            $request->validate([
                'password' => 'required|string|min:6'
            ]);
            $user->password = Hash::make($request->password);
        }
        $user->area_code =  str_replace("+", "", $request->area_code);
        $user->mobile_number = $request->phone_number;
        $user->phone_number = $request->phone_number;
        $user->cpf = preg_replace('/[^0-9]/', '', $request->cpf);
        $user->image =  $request->image ? $this->saveImage('user', $request->image, null, null) :   $user->image;
        $user->save();

        $student_data = [
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'phone_number' => $user->phone_number,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'gender' => $request->gender,
            'about_me' => $request->about_me,
            'postal_code' => $request->postal_code,
            'filiacao' => $request->filiacao,
            'rg' => preg_replace('/[^0-9]/', '', $request->rg),
            'data_nascimento' => $request->data_nascimento,
            'estado_civil' => $request->estado_civil,
            'naturalidade' => $request->naturalidade,
            'titulo_eleitor' => preg_replace('/[^0-9]/', '', $request->titulo_eleitor)
        ];

        $this->studentModel->updateByUuid($student_data, $uuid);

        $this->showToastrMessage('success', __('Updated Successfully'));
        return redirect()->route('student.index');
    }

    public function delete($uuid)
    {
        $student = $this->studentModel->getRecordByUuid($uuid);
        $instructor = Instructor::whereUserId($student->user_id)->first();
        if ($instructor) {
            $this->showToastrMessage('error', __('You can`t delete it. Because this user already an instructor. If you want to delete, at first you delete from instructor.'));
            return redirect()->back();
        }
        if ($student) {
            $this->deleteFile(@$student->user->image);
        }
        User::find($student->user_id)->delete();
        $this->studentModel->deleteByUuid($uuid);

        $this->showToastrMessage('success', __('Deleted Successfully'));
        return redirect()->back();
    }

    public function changeStudentStatus(Request $request)
    {
        $student = Student::findOrFail($request->id);
        $student->status = $request->status;
        $student->save();

        return response()->json([
            'data' => 'success',
        ]);
    }

    public function changeEnrollmentStatus(Request $request)
    {
        $enrollment = Enrollment::findOrFail($request->id);
        $enrollment->status = $request->status;
        $enrollment->save();

        return response()->json([
            'data' => 'success',
        ]);
    }

    function formatarCPF($cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9);
    }

    function formatarRG($rg)
    {
        $rg = preg_replace('/[^0-9]/', '', $rg);
        return substr($rg, 0, 2) . '.' . substr($rg, 2, 3) . '.' . substr($rg, 5, 3) . '-' . substr($rg, 8);
    }

    function formatarTituloEleitor($titulo)
    {
        // Remover caracteres não numéricos
        $titulo = preg_replace('/[^0-9]/', '', $titulo);

        // Verificar se o título tem 12 dígitos
        if (strlen($titulo) !== 12) {
            return 'Formato inválido';
        }

        // Formatar como XX.XXX.XXX/XXXX
        return substr($titulo, 0, 2) . '.' . substr($titulo, 2, 3) . '.' . substr($titulo, 5, 3) . '/' . substr($titulo, 8, 4);
    }
}

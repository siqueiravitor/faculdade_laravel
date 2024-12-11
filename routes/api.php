<?php

use App\Http\Controllers\Api\Admin\CourseController;
use App\Http\Controllers\Api\Admin\InstructorController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Organization\StudentController;
use App\Http\Controllers\Api\Admin\OrganizationControllerAPI;
use App\Http\Controllers\Api\Admin\UserControllerAPI;
use Illuminate\Support\Facades\Route;

Route::prefix('course')->group(function () {
    Route::get('enroll', [CourseController::class, 'courseEnroll'])->name('admin.course.enroll');
    Route::post('enroll', [CourseController::class, 'courseEnrollStore'])->name('admin.course.enroll.store');
});

Route::prefix('organization/student')->group(function () {
    // Route::get('/', [StudentController::class, 'index'])->name('student.index');
    // Route::get('create', [StudentController::class, 'create'])->name('student.create');
    Route::post('store', [StudentController::class, 'store'])->name('student.store');
    // Route::get('edit/{uuid}', [StudentController::class, 'edit'])->name('student.edit');
    // Route::post('update/{uuid}', [StudentController::class, 'update'])->name('student.update');
    // Route::delete('delete/{uuid}', [StudentController::class, 'delete'])->name('student.delete');
    // Route::get('view/{uuid}', [StudentController::class, 'view'])->name('student.view');
    // Route::post('status', [StudentController::class, 'status'])->name('student.status');
});
Route::prefix('organizations')->as('organizations.')->group(function () {
    Route::post('store', [OrganizationControllerAPI::class, 'store'])->name('store');
    Route::put('update/{uuid}', [OrganizationControllerAPI::class, 'update'])->name('update');
});

// Start:: user management
// Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
//     Route::get('/', [UserController::class, 'index'])->name('index');
//     Route::get('create', [UserController::class, 'create'])->name('create');
//     Route::post('store', [UserController::class, 'store'])->name('store');
//     Route::get('edit/{id}', [UserController::class, 'edit'])->name('edit');
//     Route::post('update/{id}', [UserController::class, 'update'])->name('update');
//     Route::get('delete/{id}', [UserController::class, 'delete'])->name('delete');
// });

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('create', [UserController::class, 'create'])->name('create');
    Route::post('store', [UserController::class, 'store'])->name('store');
    Route::get('edit/{id}', [UserController::class, 'edit'])->name('edit');
    Route::post('update/{id}', [UserController::class, 'update'])->name('update');
    Route::get('delete/{id}', [UserController::class, 'delete'])->name('delete');
});


Route::group(['prefix' => 'role', 'as' => 'role.'], function () {
    Route::get('/', [RoleController::class, 'index'])->name('index');
    Route::get('create', [RoleController::class, 'create'])->name('create');
    Route::post('store', [RoleController::class, 'store'])->name('store');
    Route::get('edit/{id}', [RoleController::class, 'edit'])->name('edit');
    Route::post('update/{id}', [RoleController::class, 'update'])->name('update');
    Route::get('delete/{id}', [RoleController::class, 'delete'])->name('delete');
});

// Route::prefix('instructor')->group(function () {
//     Route::get('/', [InstructorController::class, 'index'])->name('instructor.index');
//     Route::get('view/{uuid}', [InstructorController::class, 'view'])->name('instructor.view');
//     Route::get('edit/{uuid}', [InstructorController::class, 'edit'])->name('instructor.edit');
//     Route::post('update/{uuid}', [InstructorController::class, 'update'])->name('instructor.update');
//     Route::get('pending', [InstructorController::class, 'pending'])->name('instructor.pending');
//     Route::get('approved', [InstructorController::class, 'approved'])->name('instructor.approved');
//     Route::get('blocked', [InstructorController::class, 'blocked'])->name('instructor.blocked');
//     Route::get('change-status/{uuid}/{status}', [InstructorController::class, 'changeStatus'])->name('instructor.status-change');
//     Route::post('change-instructor-status', [InstructorController::class, 'changeInstructorStatus'])->name('admin.instructor.changeInstructorStatus');
//     Route::post('change-auto-content-status', [InstructorController::class, 'changeAutoContentStatus'])->name('admin.instructor.changeAutoContentStatus');
//     Route::get('delete/{uuid}', [InstructorController::class, 'delete'])->name('instructor.delete');

//     Route::get('get-state-by-country/{country_id}', [InstructorController::class, 'getStateByCountry']);
//     Route::get('get-city-by-state/{state_id}', [InstructorController::class, 'getCityByState']);

//     Route::get('create', [InstructorController::class, 'create'])->name('instructor.create');
//     Route::post('store', [InstructorController::class, 'store'])->name('instructor.store');
// });

Route::prefix('student')->group(function () {
    Route::get('/', [StudentController::class, 'index'])->name('student.index');
    Route::get('/allusers', [StudentController::class, 'allusers'])->name('student.allusers');
    Route::get('pending', [StudentController::class, 'pending_list'])->name('student.pending_list');
    Route::get('create', [StudentController::class, 'create'])->name('student.create');
    Route::post('store', [StudentController::class, 'store'])->name('student.store');
    Route::get('view/{uuid}', [StudentController::class, 'view'])->name('student.view');
    Route::get('edit/{uuid}', [StudentController::class, 'edit'])->name('student.edit');
    Route::post('update/{uuid}', [StudentController::class, 'update'])->name('student.update');
    Route::delete('delete/{uuid}', [StudentController::class, 'delete'])->name('student.delete');
    Route::post('change-student-status', [StudentController::class, 'changeStudentStatus'])->name('admin.student.changeStudentStatus');
    Route::post('change-enrollment-status', [StudentController::class, 'changeEnrollmentStatus'])->name('admin.student.changeEnrollmentStatus');
    Route::get('/aceite_contrato', [StudentController::class, 'contrato'])->name('student.contrato');
});

Route::prefix('instructor')->group(function () {
    Route::get('/', [InstructorController::class, 'index'])->name('instructor.index');
    Route::get('view/{uuid}', [InstructorController::class, 'view'])->name('instructor.view');
    Route::get('edit/{uuid}', [InstructorController::class, 'edit'])->name('instructor.edit');
    Route::post('update/{uuid}', [InstructorController::class, 'update'])->name('update');
    Route::get('pending', [InstructorController::class, 'pending'])->name('instructor.pending');
    Route::get('approved', [InstructorController::class, 'approved'])->name('instructor.approved');
    Route::get('blocked', [InstructorController::class, 'blocked'])->name('instructor.blocked');
    Route::get('change-status/{uuid}/{status}', [InstructorController::class, 'changeStatus'])->name('instructor.status-change');
    Route::post('change-instructor-status', [InstructorController::class, 'changeInstructorStatus'])->name('admin.instructor.changeInstructorStatus');
    Route::post('change-auto-content-status', [InstructorController::class, 'changeAutoContentStatus'])->name('admin.instructor.changeAutoContentStatus');
    Route::get('delete/{uuid}', [InstructorController::class, 'delete'])->name('instructor.delete');

    Route::get('get-state-by-country/{country_id}', [InstructorController::class, 'getStateByCountry']);
    Route::get('get-city-by-state/{state_id}', [InstructorController::class, 'getCityByState']);

    Route::get('create', [InstructorController::class, 'create'])->name('instructor.create');
    Route::post('store', [InstructorController::class, 'store'])->name('store');
});

// End:: user management

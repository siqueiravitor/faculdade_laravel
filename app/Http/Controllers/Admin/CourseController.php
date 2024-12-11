<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCourseRequest;
use App\Models\Category;
use App\Models\Course;
use App\Models\Course_language;
use App\Models\Course_lecture;
use App\Models\Course_lecture_views;
use App\Models\Course_lesson;
use App\Models\CourseInstructor;
use App\Models\CourseUploadRule;
use App\Models\Difficulty_level;
use App\Models\disciplinaPeriodo;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\LearnKeyPoint;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Subcategory;
use App\Models\Tag;
use App\Models\User;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use App\Traits\SendNotification;
use Hamcrest\Core\AllOf;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    use General, ImageSaveTrait, SendNotification;
    protected $model, $lectureModel, $lessonModel, $studentModel, $instructorModel;


    public function __construct(Course $course, Course_lesson $course_lesson, Course_lecture $course_lecture, Student $student, Instructor $instructor)
    {
        $this->model = new Crud($course);
        $this->lectureModel = new Crud($course_lecture);
        $this->lessonModel = new Crud($course_lesson);
        $this->studentModel = new Crud($student);
        $this->instructorModel = new Crud($instructor);
    }

    public function index()
    {
        if (!Auth::user()->can('all_course')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'All Courses';
        $data['courses'] = $this->model->getOrderById('DESC', 25);
        return view('admin.course.index', $data);
    }

    public function view($uuid)
    {
        $data['title'] = "Course Details";
        $data['course'] = $this->model->getRecordByUuid($uuid);
        $data['students'] = Enrollment::join('students', 'students.user_id', 'enrollments.user_id')->where('course_id', $data['course']->id)->select('enrollments.*', 'students.uuid', DB::raw('CONCAT(students.first_name," ", students.last_name) as name'))->with('user')->latest()->paginate(15);
        return view('admin.course.view', $data);
    }


    //     public function index()
    //     {
    //         $data['title'] = 'All Student';
    //         $users = User::where('role', 3)->pluck('id');
    //         $data['students'] = $this->studentModel->getOrderById('DESC', 25)->whereIn('user_id', $users);
    //         return view('admin.student.list', $data);
    //     }
    public function create()
    {
        $data['title'] = 'Upload Course';
        $users = User::where('role', 2)->pluck('id');
        $data['users'] = $this->instructorModel->getOrderById('DESC')->whereIn('user_id', $users);

        return view('admin.course.create', $data);
    }
    public function store(StoreCourseRequest $request)
    {
        if (Course::where('slug', Str::slug($request->title))->count() > 0) {
            $slug = Str::slug($request->title) . '-' . rand(100000, 999999);
        } else {
            $slug = Str::slug($request->title);
        }

        $data = [
            'title' => $request->title,
            'course_type' => $request->course_type,
            'instructor_id' => $request->instructor_id,
            'slug' => $slug,
            'status' => 3,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            // 'periodo' => $request->periodo
        ];

        if ($request->hasFile('og_image')) {
            $data['og_image'] = $this->saveImage('meta', $request->og_image, null, null);
        }

        $data['is_subscription_enable'] = 0;

        if (get_option('subscription_mode')) {
            $data['is_subscription_enable'] = $request->is_subscription_enable;
        }

        if ($data['is_subscription_enable']) {
            $count = Course::where('user_id', auth()->id())->count();
            if (!hasLimitSaaS(PACKAGE_RULE_SUBSCRIPTION_COURSE, PACKAGE_TYPE_SAAS_INSTRUCTOR, $count)) {
                $this->showToastrMessage('error', __('Your Subscription Enable Course Create limit has been finish.'));
                return redirect()->back();
            }
        }

        $course = $this->model->create($data);
        if ($course) {
            $periodos = explode(',',  $request->numeros_adicionados);
            $disciplinaPeriodo = $this->disciplinaPeriodo($periodos, $course->id);
            if ($disciplinaPeriodo != true) {
                $this->showToastrMessage('error', __('Erro na criação da disciplina.'));
                return redirect()->back();
            }
        }

        if ($request['key_points']) {
            if (count(@$request['key_points']) > 0) {
                foreach ($request['key_points'] as $item) {
                    if (@$item['name']) {
                        $key_point = new LearnKeyPoint();
                        $key_point->course_id = $course->id;
                        $key_point->name = @$item['name'];
                        $key_point->save();
                    }
                }
            }
        }
        CourseInstructor::whereNotIn('id', [$request->instructor_id])->where('course_id', $course->id)->delete();
        CourseInstructor::updateOrCreate([
            'instructor_id' => $request->instructor_id,
            'course_id' => $course->id,
        ], [
            'instructor_id' => $request->instructor_id,
            'course_id' => $course->id,
            'status' => STATUS_ACCEPTED
        ]);

        return redirect(route('admin.course.edit', [$course->uuid, 'step=category']));
    }
    public function disciplinaPeriodo($periodos, $curso)
    {
        try {
            foreach ($periodos as $periodo) {
                $disciplinaPeriodo = new disciplinaPeriodo();
                $disciplinaPeriodo->course_id = $curso;
                $disciplinaPeriodo->periodo = $periodo;
                $disciplinaPeriodo->save();
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    public function edit($uuid)
    {
        $data['navCourseUploadActiveClass'] = 'active';
        $data['title'] = 'Upload Course';
        $data['rules'] = CourseUploadRule::all();
        $data['course'] = Course::where('courses.uuid', $uuid)->whereNull('organization_id')->firstOrFail();
        $user_id = auth()->id();

        if (!Auth::user()->is_admin()) {
            $this->showToastrMessage('error', __('You don\'t have permission to edit this'));
            return redirect()->back();
        }

        $data['keyPoints'] = LearnKeyPoint::whereCourseId($data['course']->id)->get();
        if (\request('step') == 'category') {
            $data['periodo'] = disciplinaPeriodo::orderBy('periodo', 'asc')->select('id', 'periodo')->whereCourseId($data['course']->id)->get();
            $data['categories'] = Category::active()->orderBy('name', 'asc')->select('id', 'name')->get();
            $data['tags'] = Tag::orderBy('name', 'asc')->select('id', 'name')->get();
            $data['course_languages'] = Course_language::orderBy('name', 'asc')->select('id', 'name')->get();
            $data['difficulty_levels'] = Difficulty_level::orderBy('name', 'asc')->select('id', 'name')->get();
            if (old('category_id')) {
                $data['subcategories'] = Subcategory::where('category_id', old('category_id'))->select('id', 'name')->orderBy('name', 'asc')->get();
            } elseif ($data['course']->category_id) {
                $data['subcategories'] = Subcategory::where('category_id', $data['course']->category_id)->select('id', 'name')->orderBy('name', 'asc')->get();
            } else {
                $data['subcategories'] = [];
            }

            $selected_tags = [];

            if (old('tag')) {
                $selected_tags = old('tag');
            } elseif ($data['course']->tags->count() > 0) {
                foreach ($data['course']->tags as $tag) {
                    $selected_tags[] = $tag->id;
                }
            } else {
                $selected_tags = [];
            }

            $data['selected_tags'] = $selected_tags;

            return view('admin.course.edit-category', $data);
        } elseif (\request('step') == 'lesson') {
            return view('admin.course.lesson', $data);
        } elseif (\request('step') == 'instructors') {
            if ($data['course']->user_id != auth()->id()) {
                return view('admin.course.submit-lesson', $data);
            }

            $data['instructors'] = User::where('role', USER_ROLE_INSTRUCTOR)->where('id', '!=', $data['course']->user_id)->where('id', '!=', auth()->id())->select('id', 'name')->get();
            return view('admin.course.instructors', $data);
        } elseif (\request('step') == 'submit') {
            return view('admin.course.submit-lesson', $data);
        } else {
            $users = User::where('role', 2)->pluck('id');
            $data['users'] = $this->instructorModel->getOrderById('DESC')->whereIn('user_id', $users);
            return view('admin.course.edit', $data);
        }
    }
    public function updateOverview(StoreCourseRequest $request, $uuid)
    {
        $data['navCourseUploadActiveClass'] = 'active';
        $course = Course::where('courses.uuid', $uuid)->first();
        $user_id = auth()->id();

        if (!$course->user_id == $user_id) {

            $courseInstructor = $course->course_instructors()->where('instructor_id', $user_id)->where('status', STATUS_ACCEPTED)->first();
            if (!$courseInstructor) {
                $this->showToastrMessage('error', __('You don\'t have permission to edit this'));
                return redirect()->back();
            }
        }

        if (Course::where('slug', getSlug($request->title))->where('id', '!=', $course->id)->count() > 0) {
            $slug = getSlug($request->title) . '-' . rand(100000, 999999);
        } else {
            $slug = getSlug($request->title);
        }

        $data = [
            'title' => $request->title,
            'course_type' => $request->course_type,
            'subtitle' => $request->subtitle,
            'slug' => $slug,
            'description' => $request->description,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ];

        if ($request->hasFile('og_image')) {
            $data['og_image'] = $this->saveImage('meta', $request->og_image, null, null);
        }

        $data['is_subscription_enable'] = 0;

        if (get_option('subscription_mode')) {
            $data['is_subscription_enable'] = $request->is_subscription_enable;
        }

        if ($data['is_subscription_enable']) {
            if ($course->status == STATUS_APPROVED) {
                $count = CourseInstructor::join('courses', 'courses.id', '=', 'course_instructor.course_id')->where('is_subscription_enable', STATUS_ACCEPTED)->where('course_instructor.instructor_id', auth()->id())->groupBy('course_id')->count();
            } else {
                $count = Course::where('user_id', auth()->id())->count();
            }
            if (!hasLimitSaaS(PACKAGE_RULE_SUBSCRIPTION_COURSE, PACKAGE_TYPE_SAAS_INSTRUCTOR, $count)) {
                $this->showToastrMessage('error', __('Your Subscription Enable Course Create limit has been finish.'));
                return redirect()->back();
            }
        }

        $this->model->updateByUuid($data, $uuid); // update category

        $now = now();
        if ($request['key_points']) {
            if (count(@$request['key_points']) > 0) {
                foreach ($request['key_points'] as $item) {
                    if (@$item['name']) {
                        if (@$item['id']) {
                            $key_point = LearnKeyPoint::find($item['id']);
                        } else {
                            $key_point = new LearnKeyPoint();
                        }
                        $key_point->course_id = $course->id;
                        $key_point->name = @$item['name'];
                        $key_point->updated_at = $now;
                        $key_point->save();
                    }
                }
            }
        }

        LearnKeyPoint::where('course_id', $course->id)->where('updated_at', '!=', $now)->get()->map(function ($q) {
            $q->delete();
        });

        CourseInstructor::updateOrCreate([
            'instructor_id' => $request->instructor_id,
            'course_id' => $course->id,
        ], [
            'instructor_id' => $request->instructor_id,
            'course_id' => $course->id,
            'status' => STATUS_ACCEPTED
        ]);

        if ($course->status != 0) {
            $text = __("Course overview has been updated");
            $target_url = route('admin.course.index');
            $this->send($text, 1, $target_url, null);
        }

        return redirect(route('admin.course.edit', [$course->uuid, 'step=category']));
    }
    public function updateCategory(Request $request, $uuid)
    {
        $course = Course::where('courses.uuid', $uuid)->first();

        if ($request->image) {
            $request->validate([
                'image' => 'mimes:jpg,png,jpeg,gif,svg'
            ]);
            $this->deleteFile($course->image); // delete file from server
            $image = $this->saveImage('course', $request->image, null, null); // new file upload into server
        } else {
            $image = $course->image;
        }

        if ($request->video) {
            $this->deleteVideoFile($course->video); // delete file from server
            $file_details = $this->uploadFileWithDetails('course', $request->video);
            if (!$file_details['is_uploaded']) {
                $this->showToastrMessage('error', __('Something went wrong! Failed to upload file'));
                return redirect()->back();
            }
            $video = $file_details['path'];
        } else {
            $video = $course->video;
        }

        $data = [
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'price' => $request->price,
            'old_price' => $request->old_price,
            'drip_content' => $request->drip_content,
            'access_period' => (is_null($request->access_period) || $request->access_period < 0) ? NULL : $request->access_period,
            'course_language_id' => $request->course_language_id,
            'difficulty_level_id' => $request->difficulty_level_id,
            'learner_accessibility' => $request->learner_accessibility,
            'image' => $image ?? null,
            'video' => $video ?? null,
            'intro_video_check' => $request->intro_video_check,
            'youtube_video_id' => $request->youtube_video_id ?? null,
        ];

        $this->model->updateByUuid($data, $uuid); // update category

        if ($request->tag) {
            $course->tags()->sync($request->tag);
        }



        $disciplinaPeriodos = disciplinaPeriodo::where('course_id', $course->id)->get();
        foreach ($disciplinaPeriodos as $disciplinaPeriodo) {
            $alunos = Student::where('periodo', $disciplinaPeriodo->periodo)->where('status', 1)->where('curso', $request->category_id)->get();

            foreach ($alunos as $aluno) {
                $enrollmentResult = $this->vinculaCurso($aluno->user_id, $course->id, $request->expired_after_days);
                if ($enrollmentResult != true) {
                    $this->showToastrMessage('error', __('Não foi possível vincular o curso aos alunos.'));
                    return redirect()->back();
                }
            }
        }



        if ($request->status == STATUS_UPCOMING_REQUEST && $course->status != STATUS_UPCOMING_APPROVED) {
            try {
                $requestStatus = $request->status;
                if (auth()->user()->instructor->auto_content_approval == 1) {

                    $requestStatus = STATUS_UPCOMING_APPROVED;

                    try {
                        DB::beginTransaction();
                        /** ====== send notification to student ===== */
                        $students = Student::where('user_id', '!=', $course->user_id)->select('user_id')->get();
                        foreach ($students as $student) {
                            $text = __("New course has been added");
                            $target_url = route('course-details', $course->slug);
                            $this->send($text, 3, $target_url, $student->user_id);
                        }
                        /** ====== send notification to student ===== */

                        $text = __("Upcoming Course has been approved");
                        $target_url = route('course-details', $course->slug);
                        $this->send($text, 2, $target_url, $course->user_id);
                        $text = __("Upcoming course has been auto approved");
                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        $this->showToastrMessage('error', $e->getMessage());
                        return redirect()->back();
                    }
                } else {
                    $text = __("Upcoming course request.");
                }

                $target_url = route('admin.course.index');
                $this->send($text, 1, $target_url, null);

                $course->status = $requestStatus;
                $course->save();
            } catch (\Exception $e) {
                $this->showToastrMessage('error', $e->getMessage());
                return redirect()->back();
            }
        } elseif ($request->status == STATUS_APPROVED && in_array($course->status, [STATUS_UPCOMING_REQUEST, STATUS_UPCOMING_APPROVED])) {
            $course->status = STATUS_SUSPENDED;
            $course->save();
        }

        if ($course->status != 0) {
            $text = __("Course category has been updated");
            $target_url = route('admin.course.index');
            $this->send($text, 1, $target_url, null);
        }


        return redirect(route('admin.course.edit', [$course->uuid, 'step=lesson']));
    }

    public function vinculaCurso($user, $curso, $expired)
    {
        try {
            // $request->validate([
            //     'user_id' => 'required',
            //     'course_id' => 'required',
            //     'expired_after_days' => 'bail|nullable|integer|min:1',
            // ]);
            if (!$curso) {
                throw new \Exception('O ID do curso não foi fornecido.');
            }

            $courseOrderExits = Enrollment::where(['user_id' => $user, 'course_id' => $curso, 'status' => ACCESS_PERIOD_ACTIVE])->whereDate('end_date', '>=', now())->first();

            if ($courseOrderExits) {
                $order = Order::find($courseOrderExits->order_id);
                if ($order) {
                    if ($order->payment_status == 'due') {
                        Order_item::whereOrderId($courseOrderExits->order_id)->get()->map(function ($q) {
                            $q->delete();
                        });
                        $order->delete();
                    } else {
                        throw new \Exception('O curso já foi adquirido pelo aluno.');
                    }
                }
            }

            $ownCourseCheck = CourseInstructor::where('course_id', $curso)->where('instructor_id', $user)->delete();

            if ($ownCourseCheck) {
                throw new \Exception('O usuário é proprietário deste curso e não pode se inscrever nele.');
            }

            $course = Course::find($curso);

            if (!$course) {
                throw new \Exception('Curso não encontrado.');
            }

            $order = new Order();
            $order->user_id = $user;
            $order->order_number = rand(100000, 999999);
            $order->payment_status = 'free';
            $order->created_by_type = 2;
            $order->save();

            $order_item = new Order_item();
            $order_item->order_id = $order->id;
            $order_item->user_id = $user;
            $order_item->course_id = $curso;
            $order_item->owner_user_id = $course->user_id ?? null;
            $order_item->unit_price = 0;
            $order_item->admin_commission = 0;
            $order_item->owner_balance = 0;
            $order_item->sell_commission = 0;
            $order_item->save();

            set_instructor_ranking_level($course->user_id);

            foreach ($order->items as $item) {
                $expiredDays = !is_null($expired) && $expired > 0 ? $expired : NULL;
                setEnrollment($item, $expiredDays);
            }

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function approved()
    {
        if (!Auth::user()->can('approved_course')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Approved Courses';
        $data['courses'] = Course::where('status', 1)->paginate(25);
        return view('admin.course.approved', $data);
    }

    public function reviewPending()
    {
        if (!Auth::user()->can('pending_course')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Review Pending Courses';
        $data['courses'] = Course::where('status', 2)->paginate(25);
        return view('admin.course.review-pending', $data);
    }

    public function reviewUpcoming()
    {
        if (!Auth::user()->can('pending_course')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Upcoming Courses';
        $data['courses'] = Course::where('status', STATUS_UPCOMING_REQUEST)->orWhere('status', STATUS_UPCOMING_APPROVED)->paginate(25);
        return view('admin.course.review-upcoming', $data);
    }

    public function hold()
    {
        if (!Auth::user()->can('hold_course')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Hold Courses';
        $data['courses'] = Course::where('status', 3)->paginate(25);
        return view('admin.course.hold', $data);
    }

    public function statusChange($uuid, $status)
    {
        $course = $this->model->getRecordByUuid($uuid);
        $course->status = $status;
        $course->save();

        if ($status == 1) {
            setBadge($course->user_id);
            $text = __("Course has been approved");
            $target_url = route('course-details', $course->slug);
            $this->send($text, 2, $target_url, $course->user_id);

            /** ====== send notification to student ===== */
            $students = Student::where('user_id', '!=', $course->user_id)->select('user_id')->get();
            foreach ($students as $student) {
                $text = __("New course has been published");
                $target_url = route('course-details', $course->slug);
                $this->send($text, 3, $target_url, $student->user_id);
            }
            /** ====== send notification to student ===== */
        }

        if ($status == 3) {
            $text = __("Course has been hold");
            $target_url = route('admin.course.index');
            $this->send($text, 2, $target_url, $course->user_id);
        }


        $this->showToastrMessage('success', __('Status has been changed'));
        return redirect()->back();
    }

    public function featureChange(Request $request)
    {
        $course = $this->model->getRecordById($request->id);
        $course->is_featured = $request->status;
        $course->save();
    }

    public function delete($uuid)
    {
        $course = $this->model->getRecordByUuid($uuid);
        $order_item = Order_item::whereCourseId($course->id)->first();

        if ($order_item) {
            $this->showToastrMessage('error', __('You can not deleted. Because already student purchased this course!'));
            return redirect()->back();
        }
        //start:: Course lesson delete
        $lessons = Course_lesson::where('course_id', $course->id)->get();
        if (count($lessons) > 0) {
            foreach ($lessons as $lesson) {
                //start:: lecture delete
                $lectures = Course_lecture::where('lesson_id', $lesson->id)->get();
                if (count($lectures) > 0) {
                    foreach ($lectures as $lecture) {
                        $lecture = Course_lecture::find($lecture->id);
                        if ($lecture) {
                            $this->deleteFile($lecture->file_path); // delete file from server

                            if ($lecture->type == 'vimeo') {
                                if ($lecture->url_path) {
                                    $this->deleteVimeoVideoFile($lecture->url_path);
                                }
                            }

                            Course_lecture_views::where('course_lecture_id', $lecture->id)->get()->map(function ($q) {
                                $q->delete();
                            });

                            $this->lectureModel->delete($lecture->id); // delete record
                        }
                    }
                }
                //end:: lecture delete
                $this->lessonModel->delete($lesson->id);
            }
        }
        //end: lesson delete

        $this->deleteFile($course->image);
        $this->deleteVideoFile($course->video);
        $course->delete();
        $this->showToastrMessage('success', __('Course has been deleted.'));
        return redirect()->back();
    }

    public function courseUploadRuleIndex()
    {
        $data['title'] = 'Courses Upload Rules';
        $data['courseRules'] = CourseUploadRule::all();
        return view('admin.course.upload-rules', $data);
    }

    public function courseUploadRuleStore(Request $request)
    {
        $courseUploadRuleTitle = $request->courseUploadRuleTitle;
        if ($courseUploadRuleTitle) {
            $inputs = Arr::except($request->all(), ['_token']);
            $keys = [];

            foreach ($inputs as $k => $v) {
                $keys[$k] = $k;
            }

            foreach ($inputs as $key => $value) {
                $option = Setting::firstOrCreate(['option_key' => $key]);
                $option->option_value = $value;
                $option->save();
            }
        }


        $now = now();
        if ($request['course_upload_rules']) {

            if (count(@$request['course_upload_rules']) > 0) {
                foreach ($request['course_upload_rules'] as $course_upload_rules) {
                    if (@$course_upload_rules['description']) {
                        if (@$course_upload_rules['id']) {
                            $rule = CourseUploadRule::find($course_upload_rules['id']);
                        } else {
                            $rule = new CourseUploadRule();
                        }
                        $rule->description = @$course_upload_rules['description'];
                        $rule->updated_at = $now;
                        $rule->save();
                    }
                }
            }
        }

        CourseUploadRule::where('updated_at', '!=', $now)->get()->map(function ($q) {
            $q->delete();
        });

        $this->showToastrMessage('success', __('Updated Successful'));
        return redirect()->back();
    }

    public function courseEnroll()
    {
        $data['title'] = 'Course Enroll';
        $data['users'] = User::where('role', '!=', 1)->get();
        $data['courses'] = Course::all();
        $data['categories'] = Category::all();

        return view('admin.course.enroll-student', $data);
    }

    public function courseEnrollStore(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'course_id' => 'required',
            'expired_after_days' => 'bail|nullable|integer|min:1',
        ]);

        if ($request->course_id) {
            $courseOrderExits = Enrollment::where(['user_id' => $request->user_id, 'course_id' => $request->course_id, 'status' => ACCESS_PERIOD_ACTIVE])->whereDate('end_date', '>=', now())->first();

            if ($courseOrderExits) {
                $order = Order::find($courseOrderExits->order_id);
                if ($order) {
                    if ($order->payment_status == 'due') {
                        Order_item::whereOrderId($courseOrderExits->order_id)->get()->map(function ($q) {
                            $q->delete();
                        });
                        $order->delete();
                    } else {
                        $this->showToastrMessage('error', __("Student has already purchased the course!"));
                        return redirect()->back();
                    }
                }
            }
        }

        $ownCourseCheck = CourseInstructor::where('course_id', $request->course_id)->where('instructor_id', $request->user_id)->delete();

        if ($ownCourseCheck) {
            $this->showToastrMessage('error', __("He is a owner of the course. Can't purchase this course!"));
            return redirect()->back();
        }
        $course = Course::find($request->course_id);
        $order = new Order();
        $order->user_id = $request->user_id;
        $order->order_number = rand(100000, 999999);
        $order->payment_status = 'free';
        $order->created_by_type = 2;
        $order->save();

        $order_item = new Order_item();
        $order_item->order_id = $order->id;
        $order_item->user_id = $request->user_id;
        $order_item->course_id = $request->course_id;
        $order_item->owner_user_id = $course->user_id ?? null;
        $order_item->unit_price = 0;
        $order_item->admin_commission = 0;
        $order_item->owner_balance = 0;
        $order_item->sell_commission = 0;
        $order_item->save();


        set_instructor_ranking_level($course->user_id);

        /** ====== Send notification =========*/
        $text = __("New student enrolled");
        $target_url = route('instructor.all-student');
        foreach ($order->items as $item) {
            if ($item->course) {
                $this->send($text, 2, $target_url, $item->course->user_id);
            }

            $expiredDays = !is_null($request->expired_after_days) && $request->expired_after_days > 0 ? $request->expired_after_days : NULL;
            setEnrollment($item, $expiredDays);
        }

        $text = __("Course has been sold");
        $this->send($text, 1, null, null);

        $text = __("New course enrolled by Admin");
        $target_url = route('student.my-learning');
        $this->send($text, 3, $target_url, $request->user_id);

        /** ====== Send notification =========*/

        $this->showToastrMessage('success', __('Student enroll in course'));
        return redirect()->back();
    }
}

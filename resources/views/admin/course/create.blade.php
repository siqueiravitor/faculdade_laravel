@extends('layouts.admin')

@section('content')
 <!-- Page content area start -->
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb__content">
                        <div class="breadcrumb__content__left">
                            <div class="breadcrumb__title">
                                <h2>{{__('Courses')}}</h2>
                            </div>
                        </div>
                        <div class="breadcrumb__content__right">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('Dashboard')}}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{__('Add Course')}}</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="customers__area bg-style mb-30">
                        <div id="msform">
                        <div class='text-center'>
                            <h6><strong>{{ __('Course Overview') }}</strong></h6>
                        </div>

                        <!-- Upload Course Step-1 Item Start -->
                        <div class="upload-course-step-item upload-course-overview-step-item">

                            <!-- Upload Course Overview-1 start -->
                            <div id="upload-course-overview-1">
                                <form method="POST" action="{{route('admin.course.store')}}" enctype="multipart/form-data" id="step1"
                                    class="row g-3 needs-validation" novalidate>
                                    @csrf
                                    <div class="upload-course-item-block course-overview-step1 radius-8">
                                        <div class="row mb-30" hidden>
                                            <div class="col-md-12">
                                                <div class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                    __('Course Type') }}
                                                    <span class="text-danger">*</span>
                                                </div>

                                                <select name="course_type" id="course_type" class="form-select"
                                                    required>
                                                    <option value="">{{ __('Select Course Type') }}</option>
                                                    <option value="{{ COURSE_TYPE_GENERAL }}" selected>
                                                        {{ __('General') }}</option>
                                                    <!-- <option value="{{ COURSE_TYPE_SCORM }}"
                                                        {{old('course_type')==COURSE_TYPE_SCORM ? 'selected' : '' }}>
                                                        SCORM</option> -->
                                                </select>

                                                @if ($errors->has('course_type'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                                    $errors->first('course_type') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mb-30">
                                            <div class="col-md-12">
                                                <div class="label-text-title color-heading font-medium font-16 mb-3">
                                                    {{ __('Instructors') }}
                                                    <span class="text-danger">*</span>
                                                </div>
                                                <select name="instructor_id" id="instructor_id" class="form-select" required>
                                                    <option value="">{{ __('Select User') }}</option>
                                                    @foreach($users as $user)
                                                    <option value="{{$user->user_id}}" > {{$user->name}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-30">
                                            <div class="col-md-12">
                                                <div class="label-text-title color-heading font-medium font-16 mb-3">
                                                    {{ __('Course Title') }}
                                                    <span class="text-danger">*</span>
                                                </div>

                                                <input type="text" name="title" value="{{old('title')}}"
                                                    class="form-control" placeholder="Digite o título do seu curso" required>
                                                @if ($errors->has('title'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                                    $errors->first('title') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mb-30">
                                            <div class="col-md-12">
                                                <div class="label-text-title color-heading font-medium font-16 mb-3">
                                                    {{ __('Course Subtitle') }}
                                                
                                                </div>
                                                <textarea class="form-control" name="subtitle" cols="30" rows="10"
                                                    
                                                    placeholder="Descreva sobre em até 1000 caracteres">{{old('subtitle')}}</textarea>
                                                @if ($errors->has('subtitle'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                                    $errors->first('subtitle') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        @if(get_option('subscription_mode'))
                                        <div class="row mb-30">
                                            <div class="col-md-12">
                                                <div class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                    __('Enable for subscription') }}
                                                    <span class="text-danger">*</span>
                                                </div>

                                                <select name="is_subscription_enable" id="is_subscription_enable" class="form-select"
                                                    required>
                                                    <option value="{{ PACKAGE_STATUS_ACTIVE }}"
                                                        {{old('is_subscription_enable')==PACKAGE_STATUS_ACTIVE ? 'selected' : '' }}>
                                                        {{ __("Enable") }}</option>
                                                    <option value="{{ PACKAGE_STATUS_DISABLED }}"
                                                        {{old('is_subscription_enable')==PACKAGE_STATUS_DISABLED ? 'selected' : '' }}>
                                                        {{ __("Disabled") }}</option>
                                                </select>

                                                @if ($errors->has('is_subscription_enable'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                                    $errors->first('is_subscription_enable') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        @endif

                                        <div class="row mb-30">
                                            <div class="col-md-12">
                                                <div class="label-text-title color-heading font-medium font-16 mb-3">
                                                    {{ __('Course Description') }}
                                                  
                                                </div>
                                                <textarea class="form-control" name="description" cols="30" rows="10"
                                                    
                                                    placeholder="Course description">{{old('description')}}</textarea>
                                                @if ($errors->has('description'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                                    $errors->first('description') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-30">
                                                <label class="font-medium font-15 color-heading">{{__('Meta Title')}}</label>
                                                <input type="text" name="meta_title" class="form-control" placeholder="{{ __('Meta Title') }}">
                                                @if ($errors->has('meta_title'))
                                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('meta_title') }}</span>
                                                @endif
    
                                            </div>
                                        </div>
    
                                        <div class="row">
                                            <div class="col-md-12 mb-30">
                                                <label class="font-medium font-15 color-heading">{{__('Meta Description')}}</label>
                                                <textarea class="form-control" name="meta_description" id="exampleFormControlTextarea1" rows="3" placeholder="{{ __('Type Meta Description') }}"></textarea>
                                                @if ($errors->has('meta_description'))
                                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('meta_description') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-30">
                                                <label class="font-medium font-15 color-heading">{{__('Meta Keywords')}}</label>
                                                <input type="text" name="meta_keywords" class="form-control" placeholder="{{ __('Type meta keywords (comma separated)') }}">
                                                @if ($errors->has('meta_keywords'))
                                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('meta_keywords') }}</span>
                                                @endif
    
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12 mb-30">
                                                <div class="input__group mb-25">
                                                    <label>{{ __('OG Image') }}</label>
                                                    <div class="upload-img-box">
                                                        <img src="">
                                                        <input type="file" name="og_image" id="og_image" accept="image/*" onchange="previewFile(this)">
                                                        <div class="upload-img-box-icon">
                                                            <i class="fa fa-camera"></i>
                                                            <p class="m-0">{{__('OG Image')}}</p>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('og_image'))
                                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('og_image') }}</span>
                                                    @endif
                                                    <p><span class="text-black">{{ __('Accepted Files') }}:</span> PNG, JPG <br> <span class="text-black">{{ __('Recommend Size') }}:</span> 1200 x 627</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-12 text-right">
                                                <button type="submit" class="btn btn-primary">{{ __('Save and continue') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- Upload Course Overview-1 end -->

                        </div>

                    </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Page content area end -->

@endsection

@push('script')
<script src="{{asset('frontend/assets/js/custom/upload-course.js')}}"></script>
<script src="{{asset('common/js/jquery.repeater.min.js') }}"></script>
<script src="{{asset('common/js/add-repeater.js') }}"></script>
<script src="{{asset('admin/js/custom/image-preview.js')}}"></script>
<script src="{{asset('admin/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin/js/custom/data-table-page.js')}}"></script>
@endpush
@push('style')
    <link rel="stylesheet" href="{{asset('admin/css/custom/image-preview.css')}}">
@endpush

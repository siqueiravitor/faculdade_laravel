@extends('layouts.admin')

@section('content')
 <!-- Page content area start -->
    <div class="page-content">
        <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="customers__area bg-style mb-30">
                    <div id="msform">
                        <div class='text-center'>
                            <h6><strong>{{ __('Course Overview') }}</strong></h6>
                        </div>
                        <!-- Upload Course Step-1 Item Start -->
                        <div class="upload-course-step-item upload-course-overview-step-item">

                            <!-- Upload Course Overview-1 start -->
                            <div id="upload-course-overview-1">
                                <form method="POST" action="{{route('admin.course.update.overview', [$course->uuid])}}"
                                    id="step1" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                                    @csrf
                                    <div class="upload-course-item-block course-overview-step1 radius-8">
                                        <div class="row mb-30">
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
                                                <div class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                    __('Course Title') }}
                                                    <span class="text-danger">*</span>
                                                </div>

                                                <input type="text" name="title" value="{{$course->title}}"
                                                    class="form-control"
                                                    placeholder="Eg: Figma Essential Training - 2021" required>
                                                @if ($errors->has('title'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                                    $errors->first('title') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <!-- <div class="row mb-30">
                                            <div class="col-md-12">
                                                <div class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                    __('Course Subtitle') }}
                                                </div>

                                                <input type="text" name="subtitle" value="{{$course->subtitle}}"
                                                    class="form-control"
                                                    placeholder="Eg: Figma Essential Training - 2021" >
                                                @if ($errors->has('subtitle'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                                    $errors->first('subtitle') }}</span>
                                                @endif
                                            </div>
                                        </div> -->
                                       
                                        <div class="row mb-30">
                                            <div class="col-md-12">
                                                <div class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                    __('Course Description') }}
                                                </div>
                                                <textarea class="form-control" name="description" cols="30" rows="10"
                                                    
                                                    placeholder="Course description in 250 characters">{{$course->description}}</textarea>
                                                @if ($errors->has('description'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                                    $errors->first('description') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-30">
                                                <label class="font-medium font-15 color-heading">{{__('Meta Title')}}</label>
                                                <input type="text" name="meta_title" value="{{$course->meta_title}}" class="form-control" placeholder="{{ __('Meta Title') }}">
                                                @if ($errors->has('meta_title'))
                                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('meta_title') }}</span>
                                                @endif

                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 mb-30">
                                                <label class="font-medium font-15 color-heading">{{__('Meta Description')}}</label>
                                                <textarea class="form-control" name="meta_description" id="exampleFormControlTextarea1" rows="3" placeholder="{{ __('Type Meta Description') }}">{{$course->meta_description}}</textarea>
                                                @if ($errors->has('meta_description'))
                                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('meta_description') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-30">
                                                <label class="font-medium font-15 color-heading">{{__('Meta Keywords')}}</label>
                                                <input type="text" name="meta_keywords" value="{{$course->meta_keywords}}" class="form-control" placeholder="{{ __('Type meta keywords (comma separated)') }}">
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
                                                        @if($course->og_image != NULL && $course->og_image != '')
                                                            <img src="{{getImageFile($course->og_image)}}">
                                                        @else
                                                            <img src="">
                                                        @endif
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
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-12 text-right">
                                            <button type="submit" class="btn btn-primary">{{ __('Save and continue') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- Upload Course Overview-1 end -->

                        </div>

                        <!-- Upload Course Step-1 Item End -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<link rel="stylesheet" href="{{asset('common/css/select2.css')}}">
@endpush

@push('script')
<script src="{{asset('common/js/select2.min.js')}}"></script>
<script src="{{asset('frontend/assets/js/custom/upload-course.js')}}"></script>
<script src="{{ asset('common/js/jquery.repeater.min.js') }}"></script>
<script src="{{ asset('common/js/add-repeater.js') }}"></script>
<script src="{{asset('admin/js/custom/image-preview.js')}}"></script>
@endpush

@push('style')
    <link rel="stylesheet" href="{{asset('admin/css/custom/image-preview.css')}}">
@endpush

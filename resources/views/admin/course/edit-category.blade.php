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
            <div class="col-12">
                <div class="customers__area bg-style mb-30">
                    <div id="msform">
                        <div class='text-center'>
                            <h6><strong>{{ __('Upload Video') }}</strong></h6>
                        </div>

                        <div class="upload-course-step-item upload-course-overview-step-item">
                            <!-- Upload Course Overview-2 start -->
                            <form method="POST" action="{{route('admin.course.update.category', [$course->uuid])}}"
                                enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                                @csrf
                                <div id="upload-course-overview-2">

                                    <div class="upload-course-item-block course-overview-step1 radius-8">
                                        <div class="row">
                                            <div class="col-md-12 mb-30">
                                                <label
                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                    __('Course Category') }}
                                                </label>
                                                <select name="category_id" id="category_id" class="form-select"
                                                    required>
                                                    <option value="">{{ __('Select Category') }}</option>
                                                    @foreach($categories as $category)
                                                    <option value="{{$category->id}}" @if(old('category_id'))
                                                        {{old('category_id')==$category->id ? 'selected' : '' }} @else
                                                        {{ $course->category_id == $category->id ? 'selected' : '' }}
                                                        @endif >{{$category->name}}</option>
                                                    @endforeach
                                                </select>

                                                @if ($errors->has('category_id'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                                    $errors->first('category_id') }}</span>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                    <div class="upload-course-item-block course-overview-step1 radius-8">
                                        <div class="row" hidden>
                                            <div class="col-md-12 mb-30">
                                                <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Request course as') }}
                                                </label>
                                                <select name="status" class="form-select " required>
                                                    @php
                                                        $status = old('status', $course->status);
                                                    @endphp
                                                    <!-- <option value="{{ STATUS_UPCOMING_REQUEST }}" {{ (in_array($status, [STATUS_UPCOMING_REQUEST, STATUS_UPCOMING_APPROVED])) ? 'selected' : '' }}>{{ __('Upcoming') }}</option>
                                                    <option value="{{ STATUS_APPROVED }}" {{ (in_array($status, [STATUS_APPROVED,STATUS_REJECTED,STATUS_HOLD,STATUS_SUSPENDED,STATUS_DELETED])) ? 'selected' : '' }}>{{ __('Publish') }}</option> -->
                                                    <option selected value="{{ STATUS_APPROVED }}">{{ __('Publish') }}</option>
                                                </select>
                                                <div class="form-text">
                                                    {{ __('If you select as upcoming then it will be show as upcoming in frontend after approval.') }}
                                                </div>
                                            </div>
                                        </div>

                                        @if($course->course_type == COURSE_TYPE_GENERAL)
                                        <div class="row">
                                            <div class="col-md-12 mb-30">
                                                <label
                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                    __('Drip Content') }}
                                                </label>
                                                <select name="drip_content" class="form-select drip_content" required>
                                                    <option value="{{ DRIP_SHOW_ALL }}" {{ (old('drip_content',
                                                        $course->drip_content) == DRIP_SHOW_ALL ) ? 'selected' : ''
                                                        }}>{{ dripType(DRIP_SHOW_ALL) }}</option>
                                                    <option value="{{ DRIP_SEQUENCE }}" {{ (old('drip_content',
                                                        $course->drip_content) == DRIP_SEQUENCE ) ? 'selected' : ''
                                                        }}>{{ dripType(DRIP_SEQUENCE) }}</option>
                                                    <option value="{{ DRIP_AFTER_DAY }}" {{ (old('drip_content',
                                                        $course->drip_content) == DRIP_AFTER_DAY ) ? 'selected' : ''
                                                        }}>{{ dripType(DRIP_AFTER_DAY) }}</option>
                                                    <option value="{{ DRIP_UNLOCK_DATE }}" {{ (old('drip_content',
                                                        $course->drip_content) == DRIP_UNLOCK_DATE ) ? 'selected' : ''
                                                        }}>{{ dripType(DRIP_UNLOCK_DATE) }}</option>
                                                    <option value="{{ DRIP_PRE_IDS }}" {{ (old('drip_content', $course->
                                                        drip_content) == DRIP_PRE_IDS ) ? 'selected' : ''
                                                        }}>{{ dripType(DRIP_PRE_IDS) }}</option>
                                                </select>
                                                <!-- <div id="drip-help-text-{{ DRIP_SHOW_ALL }}" class="d-none drip-help-text form-text">
                                                    {{ dripTypeHelpText(DRIP_SHOW_ALL) }}
                                                </div> -->
                                                <div id="drip-help-text-{{ DRIP_SEQUENCE }}" class="d-none drip-help-text form-text">
                                                    {{ dripTypeHelpText(DRIP_SEQUENCE) }}
                                                </div>
                                                <div id="drip-help-text-{{ DRIP_AFTER_DAY }}" class="d-none drip-help-text form-text">
                                                    {{ dripTypeHelpText(DRIP_AFTER_DAY) }}
                                                </div>
                                                <div id="drip-help-text-{{ DRIP_UNLOCK_DATE }}" class="d-none drip-help-text form-text">
                                                    {{ dripTypeHelpText(DRIP_UNLOCK_DATE) }}
                                                </div>
                                                <div id="drip-help-text-{{ DRIP_PRE_IDS }}" class="d-none drip-help-text form-text">
                                                    {{ dripTypeHelpText(DRIP_PRE_IDS) }}
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="row" hidden>
                                            <div class="col-md-12 mb-30">
                                                <label
                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                    __('Learners Accessibility') }}
                                                    <span
                                                        class="cursor tooltip-show-btn share-referral-big-btn primary-btn get-referral-btn border-0"
                                                        data-toggle="popover" data-bs-placement="bottom"
                                                        data-bs-content="">
                                                        !
                                                    </span>
                                                </label>
                                                <select name="learner_accessibility"
                                                    class="form-select learner_accessibility" required>
                                                    <option value="">{{ __('Select Option') }}</option>
                                                    <option value="free" selected>{{__('Free')}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-30">
                                                <label
                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                    __('Language') }}
                                                </label>
                                                <select name="course_language_id" id="course_language_id"
                                                    class="form-select" required>
                                                    <option value="">{{ __('Language') }}</option>
                                                    @foreach($course_languages as $course_language)
                                                    <option value="{{$course_language->id}}" selected>
                                                        {{$course_language->name}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('course_language_id'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                                    $errors->first('course_language_id') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-30">
                                                <label
                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                    __('Difficulty Level') }}
                                                    <span
                                                        class="cursor tooltip-show-btn share-referral-big-btn primary-btn get-referral-btn border-0"
                                                        data-toggle="popover" data-bs-placement="bottom"
                                                        data-bs-content="">
                                                        !
                                                    </span>
                                                </label>
                                                <select name="difficulty_level_id" id="difficulty_level_id"
                                                    class="form-select" required>
                                                    <option value="">{{ __('Select Difficulty Level') }}</option>
                                                    @foreach($difficulty_levels as $difficulty_level)
                                                    <option value="{{$difficulty_level->id}}" selected>{{$difficulty_level->name}}</option>
                                                    @endforeach
                                                </select>

                                                @if ($errors->has('difficulty_level_id'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                                    $errors->first('difficulty_level_id') }}</span>
                                                @endif

                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-12">
                                                <label
                                                    class="label-text-title color-heading font-medium font-16 mb-3">{{
                                                    __('Course Thumbnail') }}
                                                    <span
                                                        class="cursor tooltip-show-btn share-referral-big-btn primary-btn get-referral-btn border-0"
                                                        data-toggle="popover" data-bs-placement="bottom"
                                                        data-bs-content="">
                                                        !
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="col-md-6 mb-30">
                                                <div class="upload-img-box mt-3 height-200">
                                                    @if($course->image)
                                                    <img src="{{getImageFile($course->image)}}">
                                                    @else
                                                    <img src="">
                                                    @endif
                                                    <input type="file" name="image" id="image" accept="image/*"
                                                        onchange="previewFile(this)" @if(!$course->image) 
                                                    @endif>
                                                    <div class="upload-img-box-icon">
                                                        <i class="fa fa-camera"></i>
                                                        <p class="m-0">{{__('Image')}}</p>
                                                    </div>
                                                </div>
                                                @if ($errors->has('image'))
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                                    $errors->first('image') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-md-6 mb-30">
                                                <p class="font-14 color-gray">{{ __('Recomended image format & size') }}:
                                                    575px X 450px (1MB)</p>
                                                <p class="font-14 color-gray">{{ __('Accepted image filetype') }}: jpg,
                                                    jpeg, png</p>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-12">
                                                <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Course Introduction Video') }} ({{ __('Optional') }})</label>
                                            </div>
                                            <div class="col-md-12 mb-30">
                                                <input type="radio" {{ $course->intro_video_check == 1 ? 'checked' : ''}} id="video_check" class="intro_video_check" name="intro_video_check" value="1">
                                                <label for="video_check">{{ __('Video Upload') }}</label><br>
                                                <input type="radio" {{ $course->intro_video_check == 2 ? 'checked' : ''}} id="youtube_check" class="intro_video_check" name="intro_video_check" value="2">
                                                <label for="youtube_check">{{ __('Youtube Video') }} ({{ __('write only video Id') }})</label><br>
                                            </div>
                                            <div class="col-md-12 mb-30">
                                                <input type="file" name="video" id="video" accept="video/mp4" class="form-control d-none">
                                                <input type="text" name="youtube_video_id" id="youtube_video_id" placeholder="{{ __('Type your youtube video ID') }}" value="{{ $course->youtube_video_id }}" class="form-control d-none">
                                            </div>
                                            @if($course->video)
                                            <div class="col-md-12 mb-30 d-none videoSource">
                                                <div class="video-player-area ">
                                                    <video id="player" playsinline controls
                                                        data-poster="{{ getImageFile(@$course->image) }}"
                                                        controlsList="nodownload">
                                                        <source src="{{ getVideoFile(@$course->video) }}"
                                                            type="video/mp4" >
                                                    </video>
                                                </div>
                                            </div>
                                            @endif
                                            @if($course->youtube_video_id)
                                            <div class="col-md-12 mb-30 d-none videoSourceYoutube">
                                                <div class="video-player-area ">
                                                    <div class="plyr__video-embed" id="playerVideoYoutube">
                                                        <iframe
                                                            src="https://www.youtube.com/embed/{{ @$course->youtube_video_id }}"
                                                            allowfullscreen allowtransparency allow="autoplay">
                                                        </iframe>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            @if ($errors->has('video'))
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{
                                                $errors->first('video') }}</span>
                                            @endif
                                        </div>

                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-12 text-right">
                                            <button type="submit" class="btn btn-primary">{{ __('Save and continue') }}</button>
                                        </div>
                                    </div>

                                    <!-- <a href="{{route('instructor.course.edit', [$course->uuid, 'step=overview'])}}"
                                        class="theme-btn theme-button3 show-last-phase-back-btn">{{__('Back')}}</a>
                                    <button type="submit" class="theme-btn default-hover-btn theme-button1">{{__('Save
                                        and continue')}}</button> -->

                                </div>
                            </form>
                            <!-- Upload Course Overview-2 end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<link rel="stylesheet" href="{{asset('common/css/select2.css')}}">
<link rel="stylesheet" href="{{asset('frontend/assets/css/custom/img-view.css')}}">
<!-- Video Player css -->
<link rel="stylesheet" href="{{ asset('frontend/assets/vendor/video-player/plyr.css') }}">
@endpush

@push('script')

<script src="{{asset('common/js/select2.min.js')}}"></script>
<script src="{{asset('frontend/assets/js/custom/img-view.js')}}"></script>
<script src="{{asset('frontend/assets/js/custom/upload-course.js')}}"></script>

<!-- Video Player js -->
<script src="{{ asset('frontend/assets/vendor/video-player/plyr.js') }}"></script>
<script>
    const zai_player1 = new Plyr('#playerVideoYoutube');
</script>
<!-- Video Player js -->

<script>
    "use strict"
        $(function (){
            var intro_video_check = "{{ $course->intro_video_check }}";
            console.log(intro_video_check)
            introVideoCheck(intro_video_check);
        })
        $(".intro_video_check").click(function(){
            var intro_video_check = $("input[name='intro_video_check']:checked").val();
            introVideoCheck(intro_video_check);
        });

        function introVideoCheck(intro_video_check){
            if(intro_video_check == 1){
                $('#video').removeClass('d-none');
                $('.videoSource').removeClass('d-none');
                $('.videoSourceYoutube').addClass('d-none');
                $('#youtube_video_id').addClass('d-none');
            }

            if(intro_video_check == 2){
                $('#video').addClass('d-none');
                $('.videoSource').addClass('d-none');
                $('.videoSourceYoutube').removeClass('d-none');
                $('#youtube_video_id').removeClass('d-none');
            }
        }


        $(document).on('change', ':input[name=drip_content]', function(){
            let dripValue = $(':input[name=drip_content]').val();
            $('.drip-help-text').addClass('d-none');
            $('#drip-help-text-'+dripValue).removeClass('d-none');
        });

        $(':input[name=drip_content]').trigger('change');
</script>

<!-- Video Player js -->
<script src="{{ asset('frontend/assets/vendor/video-player/plyr.js') }}"></script>
<script>
    const aysha_player = new Plyr('#player');
        const aysha_player2 = new Plyr('#youtubePlayer');
</script>
<!-- Video Player js -->
@endpush

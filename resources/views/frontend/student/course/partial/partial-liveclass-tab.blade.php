<div class="tab-pane fade" id="LiveClass" role="tabpanel" aria-labelledby="LiveClass-tab">
    <div class="row">
        <div class="col-12">
            <div class="after-purchase-course-watch-tab bg-white p-30">
                <!-- If there is no data Show Empty Design Start -->
                <div class="empty-data d-none">
                    <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
                    <h5 class="my-3">{{ __('Empty Live Class') }} </h5>
                </div>
                <!-- If there is no data Show Empty Design End -->

                <div class="course-watch-live-class-wrap instructor-quiz-list-page">
                    <ul class="nav nav-tabs assignment-nav-tabs live-class-list-nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab"
                                    aria-controls="upcoming" aria-selected="true">{{ __('Upcoming') }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="current-tab" data-bs-toggle="tab" data-bs-target="#current" type="button" role="tab"
                                    aria-controls="current" aria-selected="false">{{ __('Current') }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="past-tab" data-bs-toggle="tab" data-bs-target="#past" type="button" role="tab"
                                    aria-controls="past" aria-selected="false">{{ __('Past') }}
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content live-class-list" id="myTabContent">
                        <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
                            @if(count($upcoming_live_classes))
                            <div class="table-responsive table-responsive-xl">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">{{ __('Date & Time') }}</th>
                                        <th scope="col">{{ __('Time Duration') }}</th>
                                        <th scope="col">{{ __('Topic') }}</th>
                                        <th scope="col">{{ __('Meeting Host Name') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($upcoming_live_classes as $upcoming_live_class)
                                    <tr>
                                        <td>{{ $upcoming_live_class->date }}</td>
                                        <td>{{ $upcoming_live_class->duration }} {{ __('minutes') }}</td>
                                        <td><div class="course-watch-live-class-topic">{{ Str::limit($upcoming_live_class->class_topic, 50) }}</div></td>
                                        <td>
                                            @if($upcoming_live_class->meeting_host_name == 'zoom')
                                                Zoom
                                            @elseif($upcoming_live_class->meeting_host_name == 'bbb')
                                                BigBlueButton
                                            @elseif($upcoming_live_class->meeting_host_name == 'jitsi')
                                                Jitsi
                                            @elseif($upcoming_live_class->meeting_host_name == 'gmeet')
                                                Gmeet
                                            @elseif($upcoming_live_class->meeting_host_name == 'agora')
                                                Agora In App Video
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @else
                                <!-- If there is no data Show Empty Design Start -->
                                <div class="empty-data">
                                    <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
                                    <h5 class="my-3">{{ __('Empty Upcoming Class') }}</h5>
                                </div>
                                <!-- If there is no data Show Empty Design End -->
                            @endif
                        </div>
                        <div class="tab-pane fade" id="current" role="tabpanel" aria-labelledby="current-tab">
                            @if(count($current_live_classes))
                            <div class="table-responsive table-responsive-xl">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">{{ __('Date & Time') }}</th>
                                        <th scope="col">{{ __('Time Duration') }}</th>
                                        <th scope="col">{{ __('Topic') }}</th>
                                        <th scope="col">{{ __('Meeting Host Name') }}</th>
                                        <th scope="col">{{ __('Meeting Link') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($current_live_classes as $current_live_class)
                                    <tr>
                                        <td>{{ $current_live_class->date }}</td>
                                        <td>{{ $current_live_class->duration }} {{ __('minutes') }}</td>
                                        <td><div class="course-watch-live-class-topic">{{ Str::limit($current_live_class->class_topic, 50) }}</div></td>
                                        <td>
                                            @if($current_live_class->meeting_host_name == 'zoom')
                                                Zoom
                                            @elseif($current_live_class->meeting_host_name == 'bbb')
                                                BigBlueButton
                                            @elseif($current_live_class->meeting_host_name == 'jitsi')
                                                Jitsi
                                            @elseif($current_live_class->meeting_host_name == 'gmeet')
                                                Gmeet
                                            @elseif($current_live_class->meeting_host_name == 'agora')
                                                Agora In App Video
                                            @endif
                                        </td>
                                        <td>
                                            <div class="course-watch-meeting-link ">
                                                @if($current_live_class->meeting_host_name == 'zoom')
                                                    <a href="{{ $current_live_class->join_url }}" target="_blank" class="color-hover">{{ __('Go To Meeting') }}</a>
                                                    <span class="iconify copyZoomUrl" data-url="{{ $current_live_class->join_url }}" data-icon="akar-icons:copy"></span>
                                                @elseif($current_live_class->meeting_host_name == 'bbb')
                                                    <a href="{{ route('student.join-bbb-meeting', $current_live_class->id) }}" target="_blank" class="color-hover">{{ __('Go To Meeting') }}</a>
                                                @elseif($current_live_class->meeting_host_name == 'jitsi')
                                                    <a href="{{ route('join-jitsi-meeting', $current_live_class->uuid) }}" target="_blank"  class="color-hover">{{ __('Go To Meeting') }}</a>
                                                    Jitsi
                                                @elseif($current_live_class->meeting_host_name == 'gmeet')
                                                    <a href="{{ $current_live_class->join_url }}" target="_blank"  class="color-hover">{{ __('Go To Meeting') }}</a>
                                                    Gmeet
                                                @elseif($current_live_class->meeting_host_name == 'agora')
                                                    <a href="{{ $current_live_class->join_url }}" target="_blank"  class="color-hover">{{ __('Go To Meeting') }}</a>
                                                    Agora In App Video
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                                <!-- If there is no data Show Empty Design Start -->
                                <div class="empty-data">
                                    <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
                                    <h5 class="my-3">{{ __('Empty Past Class') }}</h5>
                                </div>
                                <!-- If there is no data Show Empty Design End -->
                            @endif
                        </div>
                        <div class="tab-pane fade" id="past" role="tabpanel" aria-labelledby="upcoming-tab">
                            @if(count($past_live_classes))
                            <div class="table-responsive table-responsive-xl">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">{{ __('Date & Time') }}</th>
                                        <th scope="col">{{ __('Time Duration') }}</th>
                                        <th scope="col">{{ __('Topic') }}</th>
                                        <th scope="col">{{ __('Meeting Host Name') }}</th>
                                        <th scope="col">{{ __('Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($past_live_classes as $past_live_class)
                                    <tr>
                                        <td>{{ $past_live_class->date }}</td>
                                        <td>{{ $past_live_class->duration }} {{ __('minutes') }}</td>
                                        <td><div class="course-watch-live-class-topic">{{ Str::limit($past_live_class->class_topic, 50) }}</div></td>
                                        <td>
                                            @if($past_live_class->meeting_host_name == 'zoom')
                                                Zoom
                                            @elseif($past_live_class->meeting_host_name == 'bbb')
                                                BigBlueButton
                                            @elseif($past_live_class->meeting_host_name == 'jitsi')
                                                Jitsi
                                            @elseif($past_live_class->meeting_host_name == 'gmeet')
                                                Gmeet
                                            @elseif($past_live_class->meeting_host_name == 'agora')
                                                Agora In App Video
                                            @endif
                                        </td>
                                        <td>
<!--                                            <a href="#" class="theme-btn theme-button1 default-hover-btn" data-toggle="modal" data-target="#videoModal">
                                                <span class="iconify" data-icon="gg:eye"></span>{{ __('View') }}
                                            </a>-->
                                            
                                            <button class="theme-btn theme-button1 green-theme-btn default-hover-btn viewGmeetMeetingLink" >
                                                {{ __('View') }}
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                                <!-- If there is no data Show Empty Design Start -->
                                <div class="empty-data">
                                    <img src="{{ asset('frontend/assets/img/empty-data-img.png') }}" alt="img" class="img-fluid">
                                    <h5 class="my-3">{{ __('Empty Past Class') }}</h5>
                                </div>
                                <!-- If there is no data Show Empty Design End -->
                            @endif
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

@section('modal')

<div class="modal fade viewMeetingLinkModal" id="viewMeetingModal" tabindex="-1" aria-labelledby="viewMeetingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="viewMeetingModalLabel">{{ __('View Meeting') }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="d-none bbbMeetingDiv">
                    <div class="row mb-30">
                        <div class="col-md-12">
                            <div class="join-url-wrap position-relative">
                                <label class="font-medium font-15 color-heading">{{ __('Meeting ID') }}</label>
                                <input type="text" name="meeting_id" class="form-control" disabled readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-30">
                        <div class="col-md-12">
                            <div class="join-url-wrap position-relative">
                                <label class="font-medium font-15 color-heading">{{ __('Moderator Password') }}</label>
                                <input type="text" name="moderator_pw" class="form-control" disabled readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-30">
                        <div class="col-md-12">
                            <div class="join-url-wrap position-relative">
                                <label class="font-medium font-15 color-heading">{{ __('Attendee Password') }}</label>
                                <input type="" name="attendee_pw" class="form-control" disabled readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-30 d-none zoomMeetingDiv">
                    <div class="col-md-12">
                        <div class="join-url-wrap position-relative">
                            <label class="font-medium font-15 color-heading">{{ __('Start URL') }}</label>
                            <textarea name="start_url" class="start_url join-url-text form-control" id="start_url" disabled readonly rows="3">
                            </textarea>
                            <button class="copy-text-btn position-absolute copyZoomStartUrl"><span class="iconify" data-icon="akar-icons:copy"></span></button>
                        </div>
                    </div>
                </div>
                <div class="row mb-30 d-none jitsiMeetingDiv">
                    <div class="col-md-12">
                        <div class="join-url-wrap position-relative">
                            <label class="font-medium font-15 color-heading">{{ __('Jitsi Meeting ID/Room') }}</label>
                            <input type="text" name="jitsi_meeting_id" class="form-control jitsi_meeting_id" disabled readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between align-items-center">
                <a href="" target="_blank" class="theme-btn theme-button1 default-hover-btn green-theme-btn joinNow">{{
                    __('Start Now') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
$('.viewGmeetMeetingLink').on('click', function(e){
        e.preventDefault();
        $('.zoomMeetingDiv').removeClass('d-none');
        $('.bbbMeetingDiv').addClass('d-none');
        $('.jitsiMeetingDiv').addClass('d-none');
        const modal = $('.viewMeetingLinkModal');
        modal.find('textarea[name=start_url]').val($(this).data('url'))
        modal.find('input[name=start_url_copy]').val($(this).data('url'))
        let start_url = $(this).data('url');
        $('.joinNow').attr("href", start_url)

        modal.find('textarea[name=join_url]').val($(this).data('url'))
        modal.find('input[name=join_url_copy]').val($(this).data('url'))
        let join_url = $(this).data('url');
        $('.joinNow').attr("href", join_url)
        modal.modal('show')
    })
</script>
@endpush
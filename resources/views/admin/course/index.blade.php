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
                                <li class="breadcrumb-item active" aria-current="page">{{__('Todas As Disciplinas')}}</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="customers__area bg-style mb-30">
                    <div class="item-title d-flex justify-content-between">
                        <h2>{{ __('All Courses') }}</h2>
                    </div>
                    <div class="customers__table all-course">
                        <table id="customers-table" class="row-border data-table-filter table-style">
                            <thead>
                                <tr>
                                    <th>{{__('Image')}}</th>
                                    <th>{{__('Title')}}</th>
                                    <th>{{__('Instructor')}}</th>
                                    <th>{{__('Category')}}</th>
                                    <th>{{__('Subcategory')}}</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courses as $course)
                                <tr class="removable-item">
                                    <td>
                                        <a href="#"> <img src="{{getImageFile($course->image_path)}}" width="80"> </a>
                                    </td>
                                    <td>
                                        {{$course->title}}
                                    </td>


                                    <td>
                                        {{$course->instructor ? $course->instructor->name : '' }}
                                    </td>
                                    <td>
                                        {{$course->category ? $course->category->name : '' }}
                                    </td>
                                    <td>
                                        {{$course->subcategory ? $course->subcategory->name : '' }}
                                    </td>
                                    <td>
                                        @if($course->status == 1)
                                        <span class="status active">{{ __('Published') }}</span>
                                        @elseif($course->status == 2)
                                        <span class="status active">{{ __('Waiting for Review') }}</span>
                                        @elseif($course->status == 3)
                                        <span class="status blocked">{{ __('Hold') }}</span>
                                        @elseif($course->status == 4)
                                        <span class="status ">{{ __('Draft') }}</span>
                                        @elseif($course->status == 6)
                                        <span class="status pending">{{ __('Upcoming Pending') }}</span>
                                        @elseif($course->status == 7)
                                        <span class="status upcoming">{{ __('Upcoming') }}</span>
                                        @else
                                        <span class="status ">{{ __('Pending') }}</span>
                                        @endif
                                    </td>
                                    <td>

                                        <div class="action__buttons float-end">
                                            @if($course->status == 1)
                                            <a href="{{route('admin.course.status-change', [$course->uuid, 3])}}" class="btn-action hold-btn mr-30" title="Make as Hold">
                                                {{__('Hold')}}
                                            </a>
                                            @elseif($course->status == 3)
                                            <a href="{{route('admin.course.status-change', [$course->uuid, 1])}}" class="btn-action approve-btn mr-30" title="Make as Active">
                                                {{__('Approve')}}
                                            </a>
                                            @endif

                                            <a href="{{route('admin.course.view', [$course->uuid])}}" class="btn-action mr-30" title="{{ __('View Details')}}">
                                                <img src="{{asset('admin/images/icons/eye-2.svg')}}" alt="eye">
                                            </a>
                                            <a href="{{route('admin.course.edit', [$course->uuid], 'step=category')}}" class="btn-action mr-30" title="{{ __('Edit')}}">
                                                <img src="{{asset('admin/images/icons/edit-2.svg')}}" alt="edit">
                                            </a>
                                            <button class="btn-action ms-2 deleteItem" data-formid="delete_row_form_{{$course->uuid}}" title="{{ __('Delete')}}">
                                                <img src="{{asset('admin/images/icons/trash-2.svg')}}" alt="trash">
                                            </button>

                                            <form action="{{route('admin.course.delete', [$course->uuid])}}" method="get" id="delete_row_form_{{ $course->uuid }}">

                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{$courses->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- Page content area end -->
@endsection

@push('style')
<link rel="stylesheet" href="{{asset('admin/css/jquery.dataTables.min.css')}}">
@endpush

@push('script')
<script src="{{asset('admin/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin/js/custom/data-table-page.js')}}"></script>
<script>
    $(".change-feature").change(function() {
        var id = $(this).closest('tr').find('#hidden_id').html();
        var status_value = $(this).closest('tr').find('.status option:selected').val();
        Swal.fire({
            title: "{{ __('Are you sure to change?') }}",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "{{__('Yes, Change it!')}}",
            cancelButtonText: "{{__('No, cancel!')}}",
            reverseButtons: true
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "{{route('admin.course.feature-change')}}",
                    data: {
                        "status": status_value,
                        "id": id,
                        "_token": "{{ csrf_token() }}",
                    },
                    datatype: "json",
                    success: function(data) {
                        toastr.options.positionClass = 'toast-bottom-right';
                        toastr.success('', "{{ __('Feature has been updated') }}");
                        location.reload();
                    },
                    error: function() {
                        alert("Error!");
                    },
                });
            } else if (result.dismiss === "cancel") {
                location.reload();
            }
        });
    });
</script>
@endpush
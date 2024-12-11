@extends('frontend.layouts.app')

@section('content')
<div class="bg-page">

    <!-- Page Header Start -->
    <header class="page-banner-header blank-page-banner-header gradient-bg position-relative">
        <div class="section-overlay">
            <div class="blank-page-banner-wrap">

                <div class="container mt-10">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="card" style="background-color: #fafafa;">
                                <div class="card-body">
                                    <div class="text-center">
                                        <div class="customers__area bg-style mb-30">
                                            <div class="item-title d-flex justify-content-between">
                                                <h2>{{('Grade Curricular')}}</h2>
                                            </div>
                                            <div class="customers__table mt-5">
                                                <table id="customers-table" class="row-border data-table-filter table-style">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" class="color-gray font-15 font-medium">{{ __('Name') }}</th>
                                                            <th scope="col" class="color-gray font-15 font-medium">{{ __('Período') }}</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($disciplinas as $disciplina)
                                                        <tr>
                                                            <td>{{ $disciplina->course->title }}</td>
                                                            <td>{{ $disciplina->course->periodo }}</td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="2">Nenhum curso encontrado.</td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </header>
    @endsection

    @push('style')
    <link rel="stylesheet" href="{{asset('admin/css/jquery.dataTables.min.css')}}">
    @endpush

    @push('script')
    <script src="{{asset('admin/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('admin/js/custom/data-table-page.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('#customers-table').DataTable();
        });
    </script>
    @endpush
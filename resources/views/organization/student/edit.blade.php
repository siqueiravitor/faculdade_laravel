@extends('layouts.organization')

@section('breadcrumb')
<div class="page-banner-content text-center">
    <h3 class="page-banner-heading text-white pb-15"> {{ __('Edit Student') }} </h3>

    <!-- Breadcrumb Start-->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb justify-content-center">
            <li class="breadcrumb-item font-14"><a href="{{ route('organization.dashboard') }}">{{ __('Dashboard') }}</a>
            </li>
            <li class="breadcrumb-item font-14 active" aria-current="page">{{ __('Edit Student') }}</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="instructor-profile-right-part">
    <form method="POST" action="{{ route('organization.student.update', $student->uuid) }}" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div class="instructor-profile-info-box">
            <div class="instructor-my-courses-title d-flex justify-content-between align-items-center">
                <h6>{{ __('Edit Student') }}</h6>
            </div>
            <div class="row">
                <div class="col-md-4 mb-30">
                    <div class="upload-img-box mt-3 height-200">
                        <img src="{{ asset(@$student->user->image_path) }}">
                        <input type="file" name="image" id="image" accept="image/*" onchange="previewFile(this)">
                        <div class="upload-img-box-icon">
                            <i class="fa fa-camera"></i>
                            <p class="m-0">{{ __('Image') }}</p>
                        </div>
                    </div>
                    @if ($errors->has('image'))
                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                        {{ $errors->first('image') }}</span>
                    @endif
                    <div class="author-info">
                        <p class="font-14">{{ __('Accepted Image Files') }}: JPEG, JPG, PNG <br>
                            {{ __('Accepted Size') }}: 300 x 300 (1MB)
                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-30">
                    <label class="font-medium font-15 color-heading">{{ __('Matrícula') }}<span class="text-danger">*</span></label>
                    <input type="text" name="matricula" disabled value="{{ $user['matricula'] }}" placeholder="{{ __('Matrícula') }}" class="form-control" readonly>
                    @if ($errors->has('matricula'))
                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>{{ $errors->first('matricula') }}</span>
                    @endif
                </div>
                <div class="col-md-6 mb-30">
                    <label class="font-medium font-15 color-heading">{{ __('First Name') }} <span class="text-danger">*</span></label>
                    <input type="text" name="first_name" value="{{ $student->first_name }}" placeholder="{{ __('First Name') }}" class="form-control" required>
                    @if ($errors->has('first_name'))
                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                        {{ $errors->first('first_name') }}</span>
                    @endif
                </div>
                <div class="col-md-6 mb-30">
                    <label class="font-medium font-15 color-heading">{{ __('Last Name') }} <span class="text-danger">*</span></label>
                    <input type="text" name="last_name" value="{{ $student->last_name }}" placeholder="{{ __('Last Name') }}" class="form-control" required>
                    @if ($errors->has('last_name'))
                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                        {{ $errors->first('last_name') }}</span>
                    @endif
                </div>
                <div class="col-md-6 mb-30">
                    <label class="font-medium font-15 color-heading">{{ __('Email') }} <span class="text-danger">*</span></label>
                    <input type="email" name="email" value="{{ $student->user->email }}" placeholder="{{ __('Email') }}" class="form-control" required>
                    @if ($errors->has('email'))
                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                        {{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="col-md-6 mb-30">
                    <label class="font-medium font-15 color-heading">{{ __('Password') }}</label>
                    <input type="password" name="password" value="" placeholder="{{ __('Password') }}" class="form-control">
                    @if ($errors->has('password'))
                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                        {{ $errors->first('password') }}</span>
                    @endif
                </div>
                <div class="col-md-6 mb-30">
                    <label class="font-medium font-15 color-heading">{{ __('Estado Civil') }}<span class="text-danger">*</span></label>
                    <select name="estado_civil" id="estado_civil" class="form-select" required>
                        <option value="Casado" {{ $student->estado_civil == 'Casado' ? 'selected' : '' }}>
                            {{ __('Casado(a)') }}
                        </option>
                        <option value="Solteiro" {{ $student->estado_civil == 'Solteiro' ? 'selected' : '' }}>
                            {{ __('Solteiro(a)') }}
                        </option>
                        <option value="Divorciado" {{ $student->estado_civil == 'Divorciado' ? 'selected' : '' }}>
                            {{ __('Divorciado(a)') }}
                        </option>
                        <option value="Viúvo" {{ $student->estado_civil == 'Viúvo' ? 'selected' : '' }}>
                            {{ __('Viúvo(a)') }}
                        </option>
                    </select>
                </div>
                <div class="col-md-6 mb-30">
                    <label class="font-medium font-15 color-heading">{{__('Cpf')}} <span class="text-danger">*</span></label>
                    <input type="text" name="cpf" value="{{ $user['cpf'] }}" placeholder="{{ __('Cpf') }}" class="form-control" required>
                    @if ($errors->has('cpf'))
                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('cpf') }}</span>
                    @endif
                </div>
                <div class="col-md-6 mb-30">
                    <label class="font-medium font-15 color-heading">{{__('RG')}} <span class="text-danger">*</span></label>
                    <input type="text" name="rg" value="{{ $user['rg'] }}" placeholder="{{ __('RG') }}" class="form-control" required>
                    @if ($errors->has('rg'))
                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('rg') }}</span>
                    @endif
                </div>
                <div class="col-md-6 mb-30">
                    <label class="font-medium font-15 color-heading">{{__('Data de nascimento')}} <span class="text-danger">*</span></label>
                    <input type="date" name="data_nascimento" value="{{ $student->data_nascimento }}" placeholder="{{ __('Data de nascimento') }}" class="form-control" required>
                    @if ($errors->has('data_nascimento'))
                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('data_nascimento') }}</span>
                    @endif
                </div>
                <div class="col-md-6 mb-30">
                    <label class="font-medium font-15 color-heading">{{__('Filiação')}} <span class="text-danger">*</span></label>
                    <input type="text" name="filiacao" value="{{ $student->filiacao }}" placeholder="{{ __('Filiação') }}" class="form-control" required>
                    @if ($errors->has('filiacao'))
                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('filiacao') }}</span>
                    @endif
                </div>
                <div class="col-md-6 mb-30">
                    <label class="font-medium font-15 color-heading">{{__('Naturalidade')}} <span class="text-danger">*</span></label>
                    <input type="text" name="naturalidade" value="{{ $student->naturalidade }}" placeholder="{{ __('Naturalidade') }}" class="form-control" required>
                    @if ($errors->has('naturalidade'))
                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('naturalidade') }}</span>
                    @endif
                </div>
                <div class="col-md-6 mb-30">
                    <label class="font-medium font-15 color-heading">{{__('Título de eleitor')}} <span class="text-danger">*</span></label>
                    <input type="text" name="titulo_eleitor" value="{{ $user['titulo'] }}" placeholder="{{ __('Título de eleitor') }}" class="form-control" required>
                    @if ($errors->has('titulo_eleitor'))
                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('titulo_eleitor') }}</span>
                    @endif
                </div>
                <div class="col-md-6 mb-30">
                    <label class="font-medium font-15 color-heading">{{ __('Phone Number') }}<span class="text-danger">*</span></label>
                    <input type="text" name="phone_number" value="{{ $student->user->mobile_number }}" placeholder="{{ __('Phone Number') }}" class="form-control">
                    @if ($errors->has('phone_number'))
                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                        {{ $errors->first('phone_number') }}</span>
                    @endif
                </div>
                <div class="col-md-6 mb-30">
                    <label class="font-medium font-15 color-heading">{{ __('Country') }}</label>
                    <select name="country_id" id="country_id" class="form-select">
                        <option value="">{{ __('Select Country') }}</option>
                        @foreach ($countries as $country)
                        <option value="{{ $country->id }}" @if ($student->country_id) {{ $student->country_id == $country->id ? 'selected' : '' }} @endif>
                            {{ $country->country_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <!-- <div class="col-md-6 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('State') }}</label>
                        <select name="state_id" id="state_id" class="form-select">
                            <option value="">{{ __('Select State') }}</option>
                            {{-- @if ($student->country_id)
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}"
                                        {{ $student->state_id == $state->id ? 'selected' : '' }}>{{ $state->name }}
                                    </option>
                                @endforeach
                            @endif --}}

                            @if (old('country_id'))
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}"
                                        {{ old('state_id') == $state->id ? 'selected' : '' }}
                                        data-value="{{ $state->name }}">{{ $state->name }}</option>
                                @endforeach
                            @else
                                @if ($student->country)
                                    @foreach ($student->country->states as $selected_state)
                                        <option value="{{ $selected_state->id }}"
                                            {{ $student->state_id == $selected_state->id ? 'selected' : '' }}
                                            data-value="{{ $selected_state->name }}">
                                            {{ $selected_state->name }}</option>
                                    @endforeach
                                @endif
                            @endif
                        </select>
                    </div>
                    <div class="col-md-6 mb-30">
                        <label class="font-medium font-15 color-heading">{{ __('City') }}</label>
                        <select name="city_id" id="city_id" class="form-select">
                            <option value="">{{ __('Select City') }}</option>
                            @if (old('state_id'))
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}"
                                        {{ old('city_id') == $city->id ? 'selected' : '' }}
                                        data-value="{{ $city->name }}">{{ $city->name }}</option>
                                @endforeach
                            @else
                                @if ($student->state)
                                    @foreach ($student->state->cities as $selected_city)
                                        <option value="{{ $selected_city->id }}"
                                            {{ $student->city_id == $selected_city->id ? 'selected' : '' }}
                                            data-value="{{ $selected_city->name }}">
                                            {{ $selected_city->name }}</option>
                                    @endforeach
                                @endif
                            @endif
                        </select>
                    </div> -->
                <div class="col-md-6 mb-30">
                    <label class="font-medium font-15 color-heading">{{ __('Address') }} <span class="text-danger">*</span></label>
                    <input type="text" name="address" value="{{ $student->address }}" placeholder="{{ __('Address') }}" class="form-control" required>
                    @if ($errors->has('address'))
                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                        {{ $errors->first('address') }}</span>
                    @endif
                </div>
                <div class="col-md-6 mb-30">
                    <label class="font-medium font-15 color-heading">{{ __('Postal Code') }}</label>
                    <input type="text" name="postal_code" value="{{ $student->postal_code }}" placeholder="{{ __('Postal Code') }}" class="form-control">
                    @if ($errors->has('postal_code'))
                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                        {{ $errors->first('postal_code') }}</span>
                    @endif
                </div>
                <div class="col-md-6 mb-30">
                    <label class="font-medium font-15 color-heading">{{ __('Gender') }}<span class="text-danger">*</span></label>
                    <select name="gender" id="gender" class="form-select" required>
                        <option value="Male" {{ $student->gender == 'Male' ? 'selected' : '' }}>
                            {{ __('Male') }}
                        </option>
                        <option value="Female" {{ $student->gender == 'Female' ? 'selected' : '' }}>
                            {{ __('Female') }}
                        </option>
                        <option value="Others" {{ $student->gender == 'Others' ? 'selected' : '' }}>
                            {{ __('Others') }}
                        </option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="font-medium font-15 color-heading">{{ ('Sobre o aluno') }} </label>
                    <textarea name="about_me" id="" cols="15" rows="5" class="form-control">{{ $student->about_me }}</textarea>
                    @if ($errors->has('about_me'))
                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                        {{ $errors->first('about_me') }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-12">
            <button type="submit" class="theme-btn theme-button1 theme-button3 font-15 fw-bold">{{ __('Upadate') }}</button>
        </div>
    </form>
</div>
@endsection

@push('style')
<link rel="stylesheet" href="{{ asset('frontend/assets/css/custom/img-view.css') }}">
@endpush

@push('script')
<script src="{{ asset('frontend/assets/js/custom/organization.js') }}"></script>
<script src="{{ asset('frontend/assets/js/custom/img-view.js') }}"></script>
@endpush
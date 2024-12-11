@extends('frontend.layouts.app')

@section('content')
<div class="bg-page">

    <!-- Page Header Start -->
    <header class="page-banner-header blank-page-banner-header gradient-bg position-relative">
        <div class="section-overlay">
            <div class="blank-page-banner-wrap">

                <div class="container mt-5">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-center">
                                        <img src="{{ asset('frontend/assets/img/contrato_header.png') }}" alt="Cabeçalho do Contrato"><br>
                                        <small> Portaria nº 583 de 03 de agosto de 2021 – D.O.U 05/08/2021.</small><br>
                                        <small> Autorização: Portaria nº 843 de 13 de agosto de 2021. – D.O.U
                                            16/08/2021.</small>
                                    </div>
                                    <p class="text-center mb-4 mt-3"><b> CONTRATO DE PRESTAÇÃO DE SERVIÇOS
                                            EDUCACIONAIS</b></p>

                                    <!-- Informações sobre a Contratada -->
                                    <p><strong>CONTRATADA:</strong> <small><b>FAMA – Faculdade da Amazônia Legal,
                                                mantida pela UNIDADE DE ENSINO SUPERIOR, TECNOLÓGICO E TÉCNICO
                                                PROFISSIONALIZANTE CAIVS IVLIVS CAESARLTDA – UNIETEC</b>, pessoa
                                            jurídica de Direito Privado inscrita no CNPJ 11.341.649/0001-92 com sede na
                                            Avenida Colonizador Roque Guedes, n° 163, Bairro Centro, CEP: 78500-000,
                                            Município de Colíder – Estado do Mato Grosso.</small></p>
                                    <br>
                                    <!-- Informações sobre o Aluno -->
                                    <p><strong>ALUNO (A) / CONTRATANTE:</strong></p>
                                    <ul>
                                        <li><strong>Nome:</strong> {{ $dados->name }}</li>
                                        <li><strong>Filiação:</strong> {{ $dados->filiacao }}</li>
                                        <li><strong>CPF:</strong> {{ $dados->cpf }}</li>
                                        <li><strong>RG:</strong> {{ $dados->rg }}</li>
                                        @php
                                        $dataNascimento = new DateTime($dados->data_nascimento);
                                        @endphp
                                        <li><strong>Data de Nascimento:</strong> {{ $dataNascimento->format('d/m/Y') }}</li>
                                        <li><strong>Estado Civil:</strong> {{ $dados->estado_civil }}</li>
                                        <li><strong>Naturalidade:</strong> {{ $dados->naturalidade }}</li>
                                        <li><strong>Celular:</strong> {{ $dados->phone_number }}</li>
                                        <li><strong>Endereço:</strong> {{ $dados->address }}</li>
                                    </ul>
                                    <br>
                                    <!-- Informações sobre o Representante Legal/Fiador -->
                                    <!-- <p><strong>REPRESENTANTE LEGAL/FIADOR:</strong></p>
                                <ul>
                                    <li><strong>Nome:</strong></li>
                                    <li><strong>Filiação:</strong></li>
                                    <li><strong>CPF:</strong></li>
                                    <li><strong>RG:</strong></li>
                                    <li><strong>Data de Nascimento:</strong></li>
                                    <li><strong>Estado Civil:</strong></li>
                                    <li><strong>Naturalidade:</strong></li>
                                    <li><strong>Celular:</strong></li>
                                    <li><strong>Endereço:</strong></li>
                                </ul> -->

                                    <!-- Informações sobre o Curso -->
                                    <p><strong>Curso:</strong> {{ $dados->title  }}</p>
                                    <!-- <p><strong>Período:</strong> NOTURNO</p>
                                <p><strong>Turma:</strong> I (2023/2)</p> -->
                                    <br>
                                    <div class="form-group reandolyn">
                                        @include('frontend.student.contrato')
                                    </div>
                                    <form action="{{ route('student.aceitacontrato') }}" method="post">
                                        @csrf
                                        <!-- Checkbox para Aceitar o Contrato -->
                                        <div class="form-group form-check mt-3">
                                            <input class="form-check-input" type="checkbox" id="aceitarContrato" name="aceitarContrato" onchange="handleCheckboxChange()" value="2">
                                            <label class="form-check-label" for="aceitarContrato" id="labelAceitarContrato">
                                                Eu li e concordo com os termos do contrato.
                                            </label>
                                        </div>

                                        <!-- Botão de Envio -->
                                        <div class="text-center mt-3">
                                            <button type="submit" class="btn btn-primary" id="aceitarButton" onclick="handleAceitarClick()">Aceitar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    @endsection

    @push('script')
    <script src="{{ asset('frontend/assets/js/course/student-my-learning-filter.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/course/course-review-create.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/course/course-refund-request.js') }}"></script>

    <script>
        function handleCheckboxChange() {
            var checkbox = document.getElementById('aceitarContrato');
            var label = document.getElementById('labelAceitarContrato');
            var aceitarButton = document.getElementById('aceitarButton');

            if (checkbox.checked) {
                label.style.color = ''; // Reset label color
                checkbox.style.borderColor = ''; // Reset checkbox border color
                aceitarButton.style.display = 'block'; // Mostrar o botão
            } else {
                label.style.color = 'red'; // Set label color to red
                checkbox.style.borderColor = 'red'; // Set checkbox border color to red
                aceitarButton.style.display = 'none'; // Ocultar o botão
            }
        }

        // Remova a função handleAceitarClick() pois não é mais necessária

        // Adicione a chamada da função handleCheckboxChange() ao carregar a página
        document.addEventListener("DOMContentLoaded", function() {
            handleCheckboxChange();
        });
    </script>
    @endpush
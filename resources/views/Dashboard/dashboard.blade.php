@extends('layouts.Navigation')

@section('title', 'Dashboard')

@section('content')
    <div>
        <div class="row">
            <!-- Productos -->
            <div class="col-md-3" style="margin-bottom: 15px;">
                <div class="card customShadow blue1 hoverCardLeft" style="height: auto; display: flex; justify-content: center;">
                    <div class="card-body row" style="text-align: center">
                        <div class="col-6"
                            style="display: flex; align-items: center; justify-content: center; flex-direction: column;">
                            <i class="fas fa-box" style="font-size: 40px;"></i>
                            <h5>Productos</h6>
                        </div>
                        <div class="col-4" style="display: flex; align-items: center; justify-content: center;">
                            <h1> {{ $productos->count() }} </h1>
                        </div>
                        <div class="col-2" style="display: flex; align-items: center; justify-content: center;">
                            <button type="button" class="btn" onclick="window.location.href='{{ route('productos') }}'"
                                style="color: white;">
                                <i class="fa-solid fa-arrow-right"></i>
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Categorias -->
            <div class="col-md-3" style="margin-bottom: 15px;">
                <div class="card customShadow green1 hoverCardCenter" style="height: auto; display: flex; justify-content: center;">
                    <div class="card-body row" style="text-align: center">
                        <div class="col-6"
                            style="display: flex; align-items: center; justify-content: center; flex-direction: column;">
                            <i class="fas fa-list" style="font-size: 40px; color: white;"></i>
                            <h5>Categorias</h6>
                        </div>
                        <div class="col-4" style="display: flex; align-items: center; justify-content: center;">
                            <h1> {{ $categorias->count() }} </h1>
                        </div>
                        <div class="col-2" style="display: flex; align-items: center; justify-content: center;">
                            <button type="button" class="btn" onclick="window.location.href='{{ route('categorias') }}'"
                                style="color: white;">
                                <i class="fa-solid fa-arrow-right"></i>
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Clientes -->
            <div class="col-md-3" style="margin-bottom: 15px;">
                <div class="card customShadow orange2 hoverCardCenter" style="height: auto; display: flex; justify-content: center;">
                    <div class="card-body row" style="text-align: center">
                        <div class="col-6"
                            style="display: flex; align-items: center; justify-content: center; flex-direction: column;">
                            <i class="fas fa-users" style="font-size: 40px; color: white;"></i>
                            <h5>Clientes</h6>
                        </div>
                        <div class="col-4" style="display: flex; align-items: center; justify-content: center;">
                            <h1> {{ $clientes->count() }} </h1>
                        </div>
                        <div class="col-2" style="display: flex; align-items: center; justify-content: center;">
                            <button type="button" class="btn" onclick="window.location.href='{{ route('clientes') }}'"
                                style="color: white;">
                                <i class="fa-solid fa-arrow-right"></i>
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Proveedores -->
            <div class="col-md-3" style="margin-bottom: 15px;" >
                <div class="card customShadow purple2 hoverCardRight" style="height: auto; display: flex; justify-content: center;">
                    <div class="card-body row" style="text-align: center">
                        <div class="col-6"
                            style="display: flex; align-items: center; justify-content: center; flex-direction: column;">
                            <i class="fas fa-truck" style="font-size: 40px; color: white;"></i>
                            <h5>Proveedores</h6>
                        </div>
                        <div class="col-4" style="display: flex; align-items: center; justify-content: center;">
                            <h1> {{ $proveedores->count() }} </h1>
                        </div>
                        <div class="col-2" style="display: flex; align-items: center; justify-content: center;">
                            <button type="button" class="btn" onclick="window.location.href='{{ route('proveedores') }}'"
                                style="color: white;">
                                <i class="fa-solid fa-arrow-right"></i>
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br>

        <div class="row">
            <div class="col-md-4">

                <!-- Ultimos productos vendidos -->
                <div class="card customShadow">
                    <div class="card-body" style="text-align: center">
                        <h1>Bienvenido {{ Auth::user()->nombre }}</h1>
                    </div>
                </div>

            </div>

            <div class="col-md-4">

                <div class="card customShadow">
                    <div class="card-body" style="text-align: center">
                        <h1>Bienvenido {{ Auth::user()->nombre }}</h1>
                    </div>
                </div>

            </div>

            <div class="col-md-4">

                <div class="card customShadow">
                    <div class="card-body" style="text-align: center">
                        <h1>Bienvenido {{ Auth::user()->nombre }}</h1>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

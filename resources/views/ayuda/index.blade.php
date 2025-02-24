@extends('Layouts.Navigation')

@section('title', 'Cajas')

@section('content')
    <div id="app">

        <div class="card" style="margin-bottom: 10px;">

            <div class="card-header">
                <h4>Inicio</h4>
            </div>


            <div class="card-body ">
                <!-- Empresa -->
                <div class="card hoverCard" style="margin-bottom: 10px;">

                    <div class="card-title" data-bs-toggle="collapse" data-bs-target="#Empresa" aria-expanded="false"
                        aria-controls="Empresa"
                        style="display: flex; justify-content: space-between; padding-left: 15px; padding-right: 15px; padding-top: 10px; cursor: pointer;">

                        <h4>Empresa</h4>

                        <button class="btn btn-outline-secondary " type="button" data-bs-toggle="collapse"
                            data-bs-target="#Empresa" aria-expanded="false" aria-controls="Empresa">
                            <i class="fas fa-chevron-down"></i>
                        </button>

                    </div>

                    <div class="collapse" id="Empresa">
                        <hr>
                        <div class="card-body">
                            <p>En esta sección se podrá visualizar la información de la empresa, así como también
                                modificarla.</p>

                            <p>Para modificar la información de la empresa, se deberá hacer clic en el botón "Editar" con un
                                icono
                                de un lapiz y un cuadro que se
                                encuentra en la parte superior derecha de la pantalla.</p>

                            <p>Una vez hecho clic en el botón "Editar", se mostrará un formulario con los campos necesarios
                                para
                                modificar la información de la empresa.</p>

                            <p> Las imagenes permitidas son de tipo .jpg, .jpeg y .png y deben de ser menores a 2MB.</p>

                            <p>Para guardar los cambios realizados, se deberá hacer clic en el botón "Guardar" y para
                                cancelar los
                                cambios se deberá hacer clic en el botón "Cancelar".</p>

                            <p> Los cambios seran aplicados al dar clic en "Guardar" y se mostrara un mensaje de
                                confirmación.</p>
                        </div>
                    </div>

                </div>

                <!-- Usuarios -->
                <div class="card hoverCard" style="margin-bottom: 10px;">

                    <div class="card-title" data-bs-toggle="collapse" data-bs-target="#Usuarios" aria-expanded="false"
                        aria-controls="Usuarios"
                        style="display: flex; justify-content: space-between; padding-left: 15px; padding-right: 15px; padding-top: 10px; cursor: pointer;">
                        <h4>Usuarios</h4>
                        <button class="btn btn-outline-secondary " type="button" data-bs-toggle="collapse"
                            data-bs-target="#Usuarios" aria-expanded="false" aria-controls="Usuarios">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>

                    <div class="collapse" id="Usuarios">
                        <hr>
                        <div class="card-body">
                            <h4>Inicio</h4>
                            <p>En esta sección se podrá visualizar la información de los usuarios, modificar, registrar,
                                eliminar y reestablecer contraseñas de usuarios.</p>

                            <h4>Registrar Usuario</h4>
                            <span>Para registrar un nuevo usuario, se deberá hacer clic en el botón "Registrar Usuario" con
                                un
                                icono
                                de un signo de más que se encuentra en la parte superior derecha de la pantalla.</span>

                            <span>Una vez hecho clic en el botón "Registrar Usuario", se mostrará un formulario con los
                                campos
                                necesarios para registrar un nuevo usuario.</span><br><br>


                            <h4>Modificar Usuario</h4>
                            <span>Para modificar la información de los usuarios, se deberá hacer clic en el botón "Editar"
                                con
                                un icono
                                de un lapiz en el registro del usuario que desee modificar.</span>

                            <span>Una vez hecho clic en el botón "Editar", se mostrará un formulario con los campos
                                necesarios
                                para
                                modificar la información del usuario.</span>

                            <span>Para guardar los cambios realizados, se deberá hacer clic en el botón "Guardar" y para
                                cancelar
                                los
                                cambios se deberá hacer clic en el botón "Cancelar".</span>

                            <span>Los cambios serán aplicados al dar clic en "Guardar" y se mostrará un mensaje de
                                confirmación.</span><br><br>

                            <h4>Eliminar Usuario</h4>
                            <span>Para eliminar un usuario, se deberá hacer clic en el botón "Eliminar" con un icono de una
                                papelera en el registro del usuario que desee eliminar.</span>

                            <span>Una vez hecho clic en el botón "Eliminar", se mostrará un mensaje de confirmación para
                                eliminar
                                el usuario.</span>

                            <span>Al eliminar un usuario, solo se elmininara el acceso al sistema a ese usuario, todos los
                                registros
                                que haya realizado seguiran en el sistema.</span><br><br>

                            <h4>Reestablecer Contraseña</h4>
                            <span>Para reestablecer la contraseña de un usuario, se deberá hacer clic en el botón
                                "Reestablecer
                                Contraseña" con un icono de un candado en el registro del usuario que desee reestablecer la
                                contraseña.</span>

                            <span>Una vez hecho clic en el botón "Reestablecer Contraseña", se mostrará un mensaje de
                                confirmación
                                para reestablecer la contraseña del usuario.</span>

                            <span>Al reestablecer la contraseña de un usuario, su contraseña será establecida a "0000"
                                al intentar
                                ingresar nuevamente al sistema, se mostrara un formulario para cambiar la contraseña. Para
                                luego poder
                                ingresar de nuevo al sistema.</span>
                        </div>
                    </div>

                </div>

                <!-- Cajas -->
            </div>

        </div>
        <script>
            new Vue({

            });
        </script>
    @endsection

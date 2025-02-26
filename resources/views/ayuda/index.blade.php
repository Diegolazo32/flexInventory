@extends('Layouts.Navigation')

@section('title', 'Información')

@section('content')
<div id="app">

    <div class="card" style="margin-bottom: 15000px;">

        <div class="card-header">
            <h4>Información</h4>
        </div>


        <div class="card-body" style="text-align: justify;">

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
                    <div class="card-body" style="padding-top: 1px;">
                        <h4>Inicio</h4>
                        <span>En esta sección se podrá visualizar la información de la empresa, así como también
                            modificarla.</span>


                        <span> La información de la empresa es la información que se mostrará en los reportes y
                            documentos
                            generados por el sistema.</span>


                        <br><br>
                        <h4>Modificar Empresa</h4>
                        <span>Para modificar la información de la empresa, se deberá hacer clic en el botón "Editar"
                            con
                            un icono
                            de un lapiz en la parte superior derecha de la pantalla.</span>
                        <span>
                            Una vez hecho clic en el botón "Editar", se mostrará un formulario con los campos
                            necesarios
                            para
                            modificar la información de la empresa.</span>

                        <span>Al dar clic en guardar, se mostrará un mensaje de confirmación y los cambios realizados
                            serán
                            aplicados.</span>

                        <br>
                        <br><h5 class="text-danger">Nota importante: Se recargara la pagina para aplicar los cambios
                            globalmente de la empresa. Esto puede afectar o eliminar su progreso
                            en otras secciones
                            del sistema.</h4>

                        <br>
                        <h4>¿Porque no puedo subir una imagen?</h4>
                        <span>Para subir una imagen, se deberá hacer clic en el botón "Seleccionar Archivo" y
                            seleccionar
                            la
                            imagen que se desea subir.</span>
                        <span> Debe de tener en cuenta que su imagen debe de ser de tipo .jpg, .jpeg o .png y no debe de
                            pesar
                            más de 2MB.</span>


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
                    <div class="card-body" style="padding-top: 1px;">
                        <h4>Inicio</h4>
                        <span>En esta sección se podrá visualizar la información de los usuarios, modificar, registrar,
                            eliminar y reestablecer contraseñas de usuarios.</span>

                        <span> Los usuarios son las personas que tendrán acceso al sistema, cada usuario tendrá un rol
                            asignado que determinará las acciones que podrá realizar en el sistema.</span>


                        <br><br>
                        <h4>Registrar Usuario</h4>
                        <span>Para registrar un nuevo usuario, se deberá hacer clic en el botón "Registrar Usuario" con
                            un
                            icono
                            de un signo de más que se encuentra en la parte superior derecha de la pantalla.</span>

                        <span>Una vez hecho clic en el botón "Registrar Usuario", se mostrará un formulario con los
                            campos
                            necesarios para registrar un nuevo usuario.</span>


                        <br><br>
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
                            confirmación.</span>

                        <span> Al modificar la informacion del usuario, estos cambios se veran reflejados en sus
                            acciones
                            futuras que se registren con ese usuario</span>

                        <br><br>
                        <h4>Eliminar Usuario</h4>
                        <span>Para eliminar un usuario, se deberá hacer clic en el botón "Eliminar" con un icono de una
                            papelera en el registro del usuario que desee eliminar.</span>

                        <span>Una vez hecho clic en el botón "Eliminar", se mostrará un mensaje de confirmación para
                            eliminar
                            el usuario.</span>

                        <span class="text-danger">Al eliminar un usuario, solo se elmininara el acceso al sistema a ese
                            usuario, todos los
                            registros
                            que haya realizado seguiran en el sistema.</span>

                        <br><br>
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

            <!-- Roles -->
            <div class="card hoverCard" style="margin-bottom: 10px;">

                <div class="card-title" data-bs-toggle="collapse" data-bs-target="#Roles" aria-expanded="false"
                    aria-controls="Roles"
                    style="display: flex; justify-content: space-between; padding-left: 15px; padding-right: 15px; padding-top: 10px; cursor: pointer;">

                    <h4>Roles</h4>

                    <button class="btn btn-outline-secondary " type="button" data-bs-toggle="collapse"
                        data-bs-target="#Roles" aria-expanded="false" aria-controls="Roles">
                        <i class="fas fa-chevron-down"></i>
                    </button>

                </div>

                <div class="collapse" id="Roles">
                    <hr>
                    <div class="card-body" style="padding-top: 1px;">
                        <h4>Inicio</h4>
                        <span>En esta sección se podrá visualizar la información de los roles, modificar, registrar y
                            eliminar
                            roles.</span>


                        <span> Los roles son los permisos que se le asignan a los usuarios, estos permisos determinan
                            las acciones que podrá realizar el usuario en el sistema. Cada usuario tiene un rol
                            especifico
                            con sus limites asignados.</span>

                        <br><br>
                        <h4>Registrar Rol</h4>
                        <span>Para registrar un nuevo rol, se deberá hacer clic en el botón "Registrar Rol" con un
                            icono
                            de un signo de más que se encuentra en la parte superior derecha de la pantalla.</span>
                        <span>Una vez hecho clic en el botón "Registrar Rol", se mostrará un formulario con los campos
                            necesarios para registrar un nuevo rol.</span>

                        <br><br>
                        <h4>Modificar Rol</h4>
                        <span>Para modificar la información de los roles, se deberá hacer clic en el botón "Editar" con
                            un icono de un lapiz en el registro del rol que desee modificar.</span>
                        <span>Una vez hecho clic en el botón "Editar", se mostrará un formulario con los campos
                            necesarios para modificar la información del rol.</span>
                        <span>Para guardar los cambios realizados, se deberá hacer clic en el botón "Guardar" y para
                            cancelar los cambios se deberá hacer clic en el botón "Cancelar".</span>
                        <span>Los cambios serán aplicados al dar clic en "Guardar" y se mostrará un mensaje de
                            confirmación.</span>

                        <br><br>
                        <h4>Eliminar Rol</h4>
                        <span>Para eliminar un rol, se deberá hacer clic en el botón "Eliminar" con un icono de una
                            papelera en el registro del rol que desee eliminar.</span>
                        <span>Una vez hecho clic en el botón "Eliminar", se mostrará un mensaje de confirmación para
                            eliminar el rol.</span>

                        <span>Al eliminar un rol, se eliminaran todos los permisos asignados a los usuarios con ese rol,
                            los usuarios seguiran existiendo pero sin permisos asignados.</span>

                        <br><br>
                        <h4>Permisos</h4>
                        <span>Para asignar permisos a un rol, se deberá hacer clic en el botón "Permisos" con un icono
                            de una llave en el registro del rol que desee asignar permisos.</span>
                        <span>Una vez hecho clic en el botón "Permisos", se mostrará un formulario con los permisos
                            disponibles para asignar al rol.</span>
                        <span>Para asignar o quitar un permiso, debe dar clic en el cuadro de selección del permiso que
                            desee asignar o quitar.</span>
                        <span>Para guardar los cambios realizados, se deberá hacer clic en el botón "Guardar" y para
                            cancelar los cambios se deberá hacer clic en el botón "Cancelar".</span>
                        <span>Los cambios serán aplicados al dar clic en "Guardar" y se mostrará un mensaje de
                            confirmación.</span>
                        <br>
                        <h5 class="text-danger">Nota importante: Se recargara la pagina para aplicar los cambios
                            globalmente de permisos. Esto puede afectar o eliminar su progreso
                            en otras secciones
                            del sistema.</h4>
                    </div>
                </div>

            </div>

            <!-- Permisos -->
            <div class="card hoverCard" style="margin-bottom: 10px;">

                <div class="card-title" data-bs-toggle="collapse" data-bs-target="#Permisos" aria-expanded="false"
                    aria-controls="Permisos"
                    style="display: flex; justify-content: space-between; padding-left: 15px; padding-right: 15px; padding-top: 10px; cursor: pointer;">

                    <h4>Permisos</h4>

                    <button class="btn btn-outline-secondary " type="button" data-bs-toggle="collapse"
                        data-bs-target="#Permisos" aria-expanded="false" aria-controls="Permisos">
                        <i class="fas fa-chevron-down"></i>
                    </button>

                </div>

                <div class="collapse" id="Permisos">
                    <hr>
                    <div class="card-body" style="padding-top: 1px;">
                        <h4>Inicio</h4>
                        <span>En esta sección se podrá visualizar la información de los Permisos del sistema y que
                            acciones limitan.</span>


                        <span> Los permisos son las acciones que se le permiten a un determinado rol, llevar a cabo, asi
                            como tambien
                            los limites a donde puede acceder y que informacion obtener.</span>

                        <br><br>
                        <h4>¿Porque no puedo realizar una accion?</h4>
                        <span>Para realizar una accion, se debera tener un rol asignado que tenga los permisos
                            necesarios para realizar
                            dicha accion.</span>
                        <span>Si no puede realizar una accion, debera de contactar con el administrador del sistema para
                            que le asigne
                            los permisos necesarios.</span>

                    </div>
                </div>

            </div>

            <!-- Parametros -->
            <div class="card hoverCard" style="margin-bottom: 10px;">

                <div class="card-title" data-bs-toggle="collapse" data-bs-target="#Parametros" aria-expanded="false"
                    aria-controls="Parametros"
                    style="display: flex; justify-content: space-between; padding-left: 15px; padding-right: 15px; padding-top: 10px; cursor: pointer;">

                    <h4>Parametros</h4>

                    <button class="btn btn-outline-secondary " type="button" data-bs-toggle="collapse"
                        data-bs-target="#Parametros" aria-expanded="false" aria-controls="Parametros">
                        <i class="fas fa-chevron-down"></i>
                    </button>

                </div>

                <div class="collapse" id="Parametros">
                    <hr>
                    <div class="card-body" style="padding-top: 1px;">
                        <h4>Inicio</h4>
                        <span>En esta sección se podrá visualizar la información de los parametros del sistema y
                            modificarlos.</span>

                        <span> Los parametros son las configuraciones que se le pueden realizar al sistema para adaptarlo a
                            las necesidades
                            de la empresa.</span>

                        <br><br>
                        <h4>¿Para que sirven los parametros del sistema?</h4>
                        <span>Los parametros del sistema sirven para configurar el sistema a las necesidades de la
                            empresa, como por ejemplo
                            las categorias de los productos, las unidades de medida, entre otros.</span>
                        <span>Al modificar los parametros del sistema, estos cambios se veran reflejados en los reportes
                            y documentos
                            generados por el sistema.</span>

                    </div>

                </div>

            </div>

            <!-- Unidades -->
            <div class="card hoverCard" style="margin-bottom: 10px;">

                <div class="card-title" data-bs-toggle="collapse" data-bs-target="#Unidades" aria-expanded="false"
                    aria-controls="Unidades"
                    style="display: flex; justify-content: space-between; padding-left: 15px; padding-right: 15px; padding-top: 10px; cursor: pointer;">

                    <h4>Unidades</h4>

                    <button class="btn btn-outline-secondary " type="button" data-bs-toggle="collapse"
                        data-bs-target="#Unidades" aria-expanded="false" aria-controls="Unidades">
                        <i class="fas fa-chevron-down"></i>
                    </button>

                </div>

                <div class="collapse" id="Unidades">
                    <hr>
                    <div class="card-body" style="padding-top: 1px;">

                        <h4>Inicio</h4>
                        <span>En esta sección se podrá visualizar la información de las unidades de medida, modificar,
                            registrar y eliminar unidades de medida.</span>


                        <span> Las unidades de medida son las medidas que se le asignan a los productos, estas medidas
                            determinan la cantidad de producto que se tiene en inventario.</span>

                        <br><br>
                        <h4>Registrar Unidad de Medida</h4>
                        <span>Para registrar una nueva unidad de medida, se deberá hacer clic en el botón "Registrar
                            Unidad de Medida" con un icono de un signo de más que se encuentra en la parte superior
                            derecha de la pantalla.</span>
                        <span>Una vez hecho clic en el botón "Registrar Unidad de Medida", se mostrará un formulario con
                            los campos necesarios para registrar una nueva unidad de medida.</span>

                        <br><br>
                        <h4>Modificar Unidad de Medida</h4>
                        <span>Para modificar la información de las unidades de medida, se deberá hacer clic en el botón
                            "Editar" con un icono de un lapiz en el registro de la unidad de medida que desee
                            modificar.</span>
                        <span>Una vez hecho clic en el botón "Editar", se mostrará un formulario con los campos
                            necesarios
                            para modificar la información de la unidad de medida.</span>
                        <span>Para guardar los cambios realizados, se deberá hacer clic en el botón "Guardar" y para
                            cancelar
                            los cambios se deberá hacer clic en el botón "Cancelar".</span>
                        <span>Los cambios serán aplicados al dar clic en "Guardar" y se mostrará un mensaje de
                            confirmación.</span>
                        <span>Al hacer una modificacion se reflejara globalmente, por ejemplo, si cambia el nombre de
                            una unidad por otro, todos
                            los productos que estan registrados con esa unidad, se veran modificados</span>

                        <br><br>
                        <h4>Eliminar Unidad de Medida</h4>
                        <span>Para eliminar una unidad de medida, se deberá hacer clic en el botón "Eliminar" con un
                            icono de una
                            papelera en el registro de la unidad de medida que desee eliminar.</span>
                        <span>Una vez hecho clic en el botón "Eliminar", se mostrará un mensaje de confirmación para
                            eliminar la
                            unidad de medida.</span>

                        <span>Si hay productos registrados con esa unidad de medida no podra ser eliminada.</span>

                        <br><br>
                        <h4>Quiero eliminar una unidad de medida, pero tiene productos asociados, ¿Que puedo hacer?</h4>
                        <span>Si desea eliminar una unidad de medida, pero tiene productos asociados, debera de cambiar
                            la unidad de
                            medida de los productos
                            a otra unidad de medida, para poder eliminar la unidad de medida.</span>
                        <span>Para cambiar la unidad de medida de un producto, se debera de modificar el producto y
                            cambiar la unidad
                            de medida por otra.</span>

                    </div>
                </div>

            </div>

            <!-- Categorias -->
            <div class="card hoverCard" style="margin-bottom: 10px;">

                <div class="card-title" data-bs-toggle="collapse" data-bs-target="#Categorias" aria-expanded="false"
                    aria-controls="Categorias"
                    style="display: flex; justify-content: space-between; padding-left: 15px; padding-right: 15px; padding-top: 10px; cursor: pointer;">

                    <h4>Categorias</h4>

                    <button class="btn btn-outline-secondary " type="button" data-bs-toggle="collapse"
                        data-bs-target="#Categorias" aria-expanded="false" aria-controls="Categorias">
                        <i class="fas fa-chevron-down"></i>
                    </button>

                </div>

                <div class="collapse" id="Categorias">
                    <hr>
                    <div class="card-body" style="padding-top: 1px;">
                        <h4>Inicio</h4>

                        <span>En esta sección se podrá visualizar la información de las categorias de los productos,
                            modificar, registrar y eliminar categorias de productos.</span>


                        <span> Las categorias de los productos son las clasificaciones que se le asignan a los productos,
                            estas clasificaciones determinan el tipo de producto que se tiene en inventario. Lo que
                            ayuda
                            a llevar un mejor control de los productos.</span>

                        <br><br>
                        <h4>Registrar Categoria</h4>
                        <span>Para registrar una nueva categoria, se deberá hacer clic en el botón "Registrar Categoria"
                            con un icono de un signo de más que se encuentra en la parte superior derecha de la
                            pantalla.</span>
                        <span>Una vez hecho clic en el botón "Registrar Categoria", se mostrará un formulario con los
                            campos necesarios para registrar una nueva categoria.</span>

                        <br><br>
                        <h4>Modificar Categoria</h4>
                        <span>Para modificar la información de las categorias, se deberá hacer clic en el botón "Editar"
                            con un icono de un lapiz en el registro de la categoria que desee modificar.</span>
                        <span>Una vez hecho clic en el botón "Editar", se mostrará un formulario con los campos
                            necesarios
                            para modificar la información de la categoria.</span>
                        <span>Para guardar los cambios realizados, se deberá hacer clic en el botón "Guardar" y para
                            cancelar
                            los cambios se deberá hacer clic en el botón "Cancelar".</span>
                        <span>Los cambios serán aplicados al dar clic en "Guardar" y se mostrará un mensaje de
                            confirmación.</span>
                        <span>Al hacer una modificacion se reflejara globalmente, por ejemplo, si cambia el nombre de
                            una categoria por otro, todos
                            los productos que estan registrados con esa categoria, se veran modificados</span>

                        <br><br>
                        <h4>Eliminar Categoria</h4>
                        <span>Para eliminar una categoria, se deberá hacer clic en el botón "Eliminar" con un icono de
                            una
                            papelera en el registro de la categoria que desee eliminar.</span>
                        <span>Una vez hecho clic en el botón "Eliminar", se mostrará un mensaje de confirmación para
                            eliminar la
                            categoria.</span>

                        <span>Si hay productos registrados con esa categoria no podra ser
                            eliminada.</span>

                        <br><br>
                        <h4>Quiero eliminar una categoria, pero tiene productos asociados, ¿Que puedo hacer?</h4>
                        <span>Si desea eliminar una categoria, pero tiene productos asociados, debera de cambiar la
                            categoria de
                            los productos
                            a otra categoria, para poder eliminar la categoria.</span>
                        <span>Para cambiar la categoria de un producto, se debera de modificar el producto y cambiar la
                            categoria
                            por otra.</span>


                    </div>
                </div>

            </div>

            <!-- Cajas -->
            <div class="card hoverCard" style="margin-bottom: 10px;">

                <div class="card-title" data-bs-toggle="collapse" data-bs-target="#Cajas" aria-expanded="false"
                    aria-controls="Cajas"
                    style="display: flex; justify-content: space-between; padding-left: 15px; padding-right: 15px; padding-top: 10px; cursor: pointer;">

                    <h4>Cajas</h4>

                    <button class="btn btn-outline-secondary " type="button" data-bs-toggle="collapse"
                        data-bs-target="#Cajas" aria-expanded="false" aria-controls="Cajas">
                        <i class="fas fa-chevron-down"></i>
                    </button>

                </div>

                <div class="collapse" id="Cajas">
                    <hr>
                    <div class="card-body" style="padding-top: 1px;">
                        <h4>Inicio</h4>
                        <span>En esta sección se podrá visualizar la información de las cajas, modificar, registrar y
                            eliminar cajas.</span>

                        <span> Las cajas son los lugares donde se registran las compras y ventas de productos, estas
                            determinan
                            en donde se registran las transacciones de los productos y el dinero que se maneja en el
                            sistema.</span>

                        <br><br>
                        <h4>Registrar Caja</h4>
                        <span>Para registrar una nueva caja, se deberá hacer clic en el botón "Registrar Caja" con un
                            icono de un signo de más que se encuentra en la parte superior derecha de la
                            pantalla.</span>
                        <span>Una vez hecho clic en el botón "Registrar Caja", se mostrará un formulario con los campos
                            necesarios para registrar una nueva caja.</span>

                        <br><br>
                        <h4>Modificar Caja</h4>
                        <span>Para modificar la información de las cajas, se deberá hacer clic en el botón "Editar" con
                            un icono de un lapiz en el registro de la caja que desee modificar.</span>
                        <span>Una vez hecho clic en el botón "Editar", se mostrará un formulario con los campos
                            necesarios
                            para modificar la información de la caja.</span>

                        <span>Para guardar los cambios realizados, se deberá hacer clic en el botón "Guardar" y para
                            cancelar
                            los cambios se deberá hacer clic en el botón "Cancelar".</span>

                        <span>Al modificar una caja, las transacciones anteriores no se veran afectadas, solo las
                            transacciones
                            futuras.</span>

                        <br><br>
                        <h4>Eliminar Caja</h4>
                        <span>Para eliminar una caja, se deberá hacer clic en el botón "Eliminar" con un icono de una
                            papelera en el registro de la caja que desee eliminar.</span>
                        <span>Una vez hecho clic en el botón "Eliminar", se mostrará un mensaje de confirmación para
                            eliminar la
                            caja.</span>

                        <span class="text-danger">Al eliminar una caja, todas las transacciones registradas por esa
                            caja, se mantendran en el sistema por razones de registro historico.</span>
                        </span>


                    </div>
                </div>

            </div>

            <!-- Estados -->
            <div class="card hoverCard" style="margin-bottom: 10px;">

                <div class="card-title" data-bs-toggle="collapse" data-bs-target="#Estados" aria-expanded="false"
                    aria-controls="Estados"
                    style="display: flex; justify-content: space-between; padding-left: 15px; padding-right: 15px; padding-top: 10px; cursor: pointer;">

                    <h4>Estados</h4>

                    <button class="btn btn-outline-secondary " type="button" data-bs-toggle="collapse"
                        data-bs-target="#Estados" aria-expanded="false" aria-controls="Estados">
                        <i class="fas fa-chevron-down"></i>
                    </button>

                </div>

                <div class="collapse" id="Estados">
                    <hr>
                    <div class="card-body" style="padding-top: 1px;">
                        <h4>Inicio</h4>
                        <span>En esta sección se podrá visualizar la información de los estados de los productos,
                            modificar, registrar y eliminar estados de productos.</span>

                        <span> Los estados de los productos son las condiciones en las que se encuentran los productos,
                            estos estados determinan si un producto esta disponible para la venta o no. Asi como las
                            demas condiciones
                            en las que se puedan encontrar diferentes elementos en el sistema.</span>

                        <br><br>
                        <h4> ¿Para que funciona los estados?</h4>
                        <span>Los estados de los productos sirven para determinar si un producto esta disponible para la
                            venta o no, asi como tambien
                            para determinar si un producto esta agotado, un usuario bloqueado, restaurado o una compra
                            este pendiente de pago o no.</span>
                    </div>
                </div>

            </div>

            <!-- Inventario -->
            <div class="card hoverCard" style="margin-bottom: 10px;">

                <div class="card-title" data-bs-toggle="collapse" data-bs-target="#Inventario" aria-expanded="false"
                    aria-controls="Inventario"
                    style="display: flex; justify-content: space-between; padding-left: 15px; padding-right: 15px; padding-top: 10px; cursor: pointer;">

                    <h4>Inventario</h4>

                    <button class="btn btn-outline-secondary " type="button" data-bs-toggle="collapse"
                        data-bs-target="#Inventario" aria-expanded="false" aria-controls="Inventario">
                        <i class="fas fa-chevron-down"></i>
                    </button>

                </div>

                <div class="collapse" id="Inventario">
                    <hr>
                    <div class="card-body" style="padding-top: 1px;">

                        <h4>Inicio</h4>
                        <span>En esta sección se podrá visualizar la información del inventario activo y del inventario
                            cerrado anteriormente</span>



                        <span> El inventario es el registro de los productos que se tienen en existencia, asi como tambien
                            los productos que se han vendido y comprado en un periodo de tiempo determinado. Esta
                            informacion es vital para llevar un control
                            de los productos y las transacciones realizadas en el sistema.</span>

                        <span> Se recomienda aperturar un inventario al inicio de cada mes, para llevar un control de
                            los productos y transacciones realizadas en el mes.</span>

                        <br><br>
                        <h4>Primera apertura</h4>
                        <span>Para aperturar un inventario, se deberá hacer clic en el botón "Aperturar
                            Inventario"</span>
                        <span>Una vez hecho clic en el botón "Aperturar Inventario", se mostrará un mensaje de
                            confirmación para aperturar el inventario.</span>
                        <span>La primera apertura de inventario, iniciara con todos sus valores a 0, y se registrara
                            como el inventario inicial.</span>

                        <span class="text-danger"> Se debe de tener un inventario abierto para poder realizar
                            movimientos o transacciones con los productos </span>

                        <span> Al aperturar un inventario nuevo, se realizan las siguientes acciones:</span>
                        <ul>
                            <li>Se calcula el valor en apertura del inventario</li>
                            <li>Se registra la catidad de stock en apertura </li>
                            <li>Se registra el total de productos en apertura </li>
                        </ul>

                        <br><br>
                        <h4>Cierre de Inventario</h4>
                        <span>Para cerrar un inventario, se deberá hacer clic en el botón "Cerrar Inventario"</span>
                        <span>Una vez hecho clic en el botón "Cerrar Inventario", se mostrará un mensaje de confirmación
                            para cerrar el inventario.</span>
                        <span>Al cerrar un inventario, se registrara el inventario como cerrado y se realizan las
                            siguientes
                            acciones:</span>
                        <ul>
                            <li>Se calcula el valor final del inventario</li>
                            <li>Se registra la catidad de stock final </li>
                            <li>Se registra el total final de productos </li>
                        </ul>
                        <span>Al cerrar un inventario, se bloquearan las transacciones de productos, hasta que se
                            aperture
                            un nuevo inventario.</span>
                        <span>Se recomienda cerrar un inventario al final de cada mes, para llevar un control de los
                            productos y transacciones realizadas en el mes.</span>

                        <br><br>
                        <h4>Aperturas siguientes</h4>
                        <span>Para aperturar un inventario, se deberá hacer clic en el botón "Abrir Inventario"</span>
                        <span>Una vez hecho clic en el botón "Abrir Inventario", se mostrará un mensaje de confirmación
                            para aperturar el inventario.</span>

                        <span>Al aperturar un inventario nuevo, se realizan las siguientes acciones:</span>
                        <ul>
                            <li>Se calcula el valor en apertura del inventario</li>
                            <li>Se registra la catidad de stock en apertura </li>
                            <li>Se registra el total de productos en apertura </li>
                            <li>El stock inicial sera el stock final del inventario anterior [1]</li>
                        </ul>

                        <br><br>
                        <h4>Cierres siguientes</h4>
                        <span>Los siguientes cierres seguiran los mismo pasos que el cierre inicial, solo que se
                            registraran los valores finales del inventario.</span>
                        <span>Al cerrar un inventario, se bloquearan las transacciones de productos, hasta que se
                            aperture
                            un nuevo inventario.</span>

                        <br><br>
                        <h4>¿Que pasa si no cierro un inventario?</h4>
                        <span>Si no cierra un inventario, las transacciones de productos seguiran abiertas y se
                            registraran en el inventario activo. Esto puede generar confusion en los reportes y
                            documentos generados por el sistema.</span>
                        <span>Se recomienda cerrar un inventario al final de cada mes, para llevar un control de los
                            productos y transacciones realizadas en el mes.</span>

                        <br><br>
                        <h4>¿Que pasa si no aperturo un inventario?</h4>
                        <span>Si no apertura un inventario, no podra realizar transacciones de productos, hasta que
                            aperturare un nuevo inventario. Esto puede generar confusion en los reportes y documentos
                            generados por el sistema.</span>
                        <span>Se recomienda aperturar un inventario al inicio de cada mes, para llevar un control de los
                            productos y transacciones realizadas en el mes.</span>

                        <span class="text-muted">[1] El stock inicial sera el stock final del inventario anterior:
                            </h4>
                            <span> Por ejemplo: Si el stock final del inventario anterior de un producto fue de 100, el
                                stock
                                inicial del nuevo inventario sera de 100 para ese producto.</span>
                            <span> Esto afecta a los movimientos de Kardex ya que se usa el stock inicial para comparar
                                los movimientos de
                                inventario.</span>
                    </div>
                </div>

            </div>

            <!-- Clientes -->
            <div class="card hoverCard" style="margin-bottom: 10px;">

                <div class="card-title" data-bs-toggle="collapse" data-bs-target="#Clientes" aria-expanded="false"
                    aria-controls="Clientes"
                    style="display: flex; justify-content: space-between; padding-left: 15px; padding-right: 15px; padding-top: 10px; cursor: pointer;">

                    <h4>Clientes</h4>

                    <button class="btn btn-outline-secondary " type="button" data-bs-toggle="collapse"
                        data-bs-target="#Clientes" aria-expanded="false" aria-controls="Clientes">
                        <i class="fas fa-chevron-down"></i>
                    </button>

                </div>

                <div class="collapse" id="Clientes">
                    <hr>
                    <div class="card-body" style="padding-top: 1px;">
                        <h4>Inicio</h4>
                        <span>En esta sección se podrá visualizar la información de los clientes, modificar, registrar y
                            eliminar clientes.</span>

                        <span> Los clientes son las personas a las que se les vende productos, estos clientes pueden ser
                            personas fisicas o juridicas, que realizan compras de productos en el sistema.</span>

                        <br><br>
                        <h4>Registrar Cliente</h4>
                        <span>Para registrar un nuevo cliente, se deberá hacer clic en el botón "Registrar Cliente" con
                            un icono de un signo de más que se encuentra en la parte superior derecha de la
                            pantalla.</span>
                        <span>Una vez hecho clic en el botón "Registrar Cliente", se mostrará un formulario con los
                            campos necesarios para registrar un nuevo cliente.</span>

                        <br><br>
                        <h4>Modificar Cliente</h4>
                        <span>Para modificar la información de los clientes, se deberá hacer clic en el botón "Editar"
                            con un icono de un lapiz en el registro del cliente que desee modificar.</span>
                        <span>Una vez hecho clic en el botón "Editar", se mostrará un formulario con los campos
                            necesarios
                            para modificar la información del cliente.</span>

                        <span>Para guardar los cambios realizados, se deberá hacer clic en el botón "Guardar" y para
                            cancelar
                            los cambios se deberá hacer clic en el botón "Cancelar".</span>

                        <span>Al modificar un cliente, los cambios se veran reflejados en las transacciones futuras al cliente</span>

                        <br><br>
                        <h4>Eliminar Cliente</h4>
                        <span>Para eliminar un cliente, se deberá hacer clic en el botón "Eliminar" con un icono de una
                            papelera en el registro del cliente que desee eliminar.</span>
                        <span>Una vez hecho clic en el botón "Eliminar", se mostrará un mensaje de confirmación para
                            eliminar el cliente.</span>


                        <p class="text-danger">Al eliminar un cliente, las ventas asociadas no se perderan, pero se perdera la informacion
                            del cliente.</span>

                            <br><br>
                        <h4>¿Para que sirve registrar un cliente?</h4>
                        <span>Registrar un cliente le permite llevar un control de quienes son compradores frecuentes, o
                            personas con las que tiene mas afinidad y les ofrece un trato preferencial.</span>
                        <span>Registrar un cliente le permite llevar un control de las ventas realizadas a ese cliente y
                            llevar un historial de las transacciones realizadas por ese cliente en especifico.</span>

                        <br><br>
                        <h4>¿Que pasa si no registro un cliente?</h4>
                        <span>No pasa nada, sin embargo se le incluye un "Cliente general", que es el cliente por defecto
                            para las ventas, si no se registra un cliente.</span>

                        <br><br>
                        <h4>¿Para que sirve el campo de descuento al crear un cliente?</h4>
                        <span>El campo de descuento le permite asignar un descuento fijo a un cliente, este descuento se
                            aplicara a todas las ventas realizadas a ese cliente.</span>
                        <span>El descuento se aplicara a todas las ventas realizadas a ese cliente, sin importar el
                            producto o la cantidad de productos comprados.</span>
                        <span class="text-danger">El descuento se aplica al total de la venta, esto puede afectar el valor final de la transaccione
                            y es acumulativo con otros descuentos.</span>




                    </div>
                </div>

            </div>

            <!-- Proveedores -->
            <div class="card hoverCard" style="margin-bottom: 10px;">

                <div class="card-title" data-bs-toggle="collapse" data-bs-target="#Proveedores"
                    aria-expanded="false" aria-controls="Proveedores"
                    style="display: flex; justify-content: space-between; padding-left: 15px; padding-right: 15px; padding-top: 10px; cursor: pointer;">

                    <h4>Proveedores</h4>

                    <button class="btn btn-outline-secondary " type="button" data-bs-toggle="collapse"
                        data-bs-target="#Proveedores" aria-expanded="false" aria-controls="Proveedores">
                        <i class="fas fa-chevron-down"></i>
                    </button>

                </div>

                <div class="collapse" id="Proveedores">
                    <hr>
                    <div class="card-body" style="padding-top: 1px;">
                        <h4>Inicio</h4>
                        <span>En esta sección se podrá visualizar la información de los proveedores, modificar, registrar
                            y eliminar proveedores.</span>



                        <span> Los proveedores son las personas o empresas que suministran los productos al sistema, estos se asocian a los productos que ofrecen
                            y permiten tener un control sobre los precios y productos que le ofrecen cada proveedor</span>

                        <br><br>
                        <h4>Registrar Proveedor</h4>
                        <span>Para registrar un nuevo proveedor, se deberá hacer clic en el botón "Registrar Proveedor"
                            con un icono de un signo de más que se encuentra en la parte superior derecha de la
                            pantalla.</span>
                        <span>Una vez hecho clic en el botón "Registrar Proveedor", se mostrará un formulario con los
                            campos necesarios para registrar un nuevo proveedor.</span>

                        <br><br>
                        <h4>Modificar Proveedor</h4>
                        <span>Para modificar la información de los proveedores, se deberá hacer clic en el botón "Editar"
                            con un icono de un lapiz en el registro del proveedor que desee modificar.</span>
                        <span>Una vez hecho clic en el botón "Editar", se mostrará un formulario con los campos
                            necesarios
                            para modificar la información del proveedor.</span>

                        <span>Para guardar los cambios realizados, se deberá hacer clic en el botón "Guardar" y para
                            cancelar
                            los cambios se deberá hacer clic en el botón "Cancelar".</span>

                        <span>Al modificar un proveedor, los cambios se veran reflejados en las transacciones futuras al proveedor</span>

                        <br><br>
                        <h4>Eliminar Proveedor</h4>
                        <span>Para eliminar un proveedor, se deberá hacer clic en el botón "Eliminar" con un icono de una
                            papelera en el registro del proveedor que desee eliminar.</span>

                        <span>Una vez hecho clic en el botón "Eliminar", se mostrará un mensaje de confirmación para
                            eliminar el proveedor.</span>



                        <p class="text-danger">Al eliminar un proveedor, las compras asociadas o productos no se perderan, pero se perdera la informacion
                            del proveedor.</span>
                        <p class="text-danger">Al desactivar un proveedor, este no se mostrara en la lista de opciones al comprar o crear productos, pero
                            las compras y productos asociados no se perderan.</span>

                    </div>
                </div>

            </div>

            <!-- Productos -->
            <div class="card hoverCard" style="margin-bottom: 10px;">

                <div class="card-title" data-bs-toggle="collapse" data-bs-target="#Productos" aria-expanded="false"
                    aria-controls="Productos"
                    style="display: flex; justify-content: space-between; padding-left: 15px; padding-right: 15px; padding-top: 10px; cursor: pointer;">

                    <h4>Productos</h4>

                    <button class="btn btn-outline-secondary " type="button" data-bs-toggle="collapse"
                        data-bs-target="#Productos" aria-expanded="false" aria-controls="Productos">
                        <i class="fas fa-chevron-down"></i>
                    </button>

                </div>

                <div class="collapse" id="Productos">
                    <hr>
                    <div class="card-body" style="padding-top: 1px;">

                        <h4>Inicio</h4>
                        <span>En esta sección se podrá visualizar la información de los productos, modificar, registrar y
                            eliminar productos.</span>



                        <span> Los productos son los elementos que se venden en el sistema, estos productos se asocian a
                            categorias, unidades de medida, estados y proveedores, para llevar un control de los productos
                            y transacciones realizadas en el sistema.</span>

                        <br><br>
                        <h4>Registrar Producto</h4>
                        <span>Para registrar un nuevo producto, se deberá hacer clic en el botón "Registrar Producto" con
                            un icono de un signo de más que se encuentra en la parte superior derecha de la pantalla.</span>
                        <span>Una vez hecho clic en el botón "Registrar Producto", se mostrará un formulario con los campos
                            necesarios para registrar un nuevo producto.</span>

                        <br><br>
                        <h4>Modificar Producto</h4>
                        <span>Para modificar la información de los productos, se deberá hacer clic en el botón "Editar" con
                            un icono de un lapiz en el registro del producto que desee modificar.</span>
                        <span>Una vez hecho clic en el botón "Editar", se mostrará un formulario con los campos necesarios
                            para modificar la información del producto.</span>

                        <span>Para guardar los cambios realizados, se deberá hacer clic en el botón "Guardar" y para
                            cancelar
                            los cambios se deberá hacer clic en el botón "Cancelar".</span>

                        <span>Al modificar un producto, los cambios se veran reflejados en las transacciones futuras al producto</span>

                        <br><br>
                        <h4>Eliminar Producto</h4>

                        <span class="text-danger">Un producto no puede ser eliminado directamente, pero si puede ser inactivado. Esto es debido a politicas de auditoria e historial legal</span>


                        <br><br>
                        <h4>¿Tengo demasiados productos inactivos y me gustaria eliminarlos?</h4>
                        <span>Si tiene demasiados productos inactivos y desea eliminarnos, lastimosamente no es posible eliminarlos por completo, debido a politicas de auditoria e historial legal.</span>
                        <span>Sin embargo el sistema se encarga de enlistar de ultimo los productos inactivos, para que no interfieran con los productos activos.</span>
                        <span>Si desea eliminar un producto inactivo, se recomienda cambiar el estado del producto a "Inactivo" para que no se muestre en las listas de productos activos.</span>

                        <br><br>
                        <h4>¿Que pasa al inactivar un producto?</h4>
                        <span>Al inactivar un producto, este no se mostrara en la lista de opciones al comprar o vender productos, pero las ventas y compras asociadas no se perderan.</span>
                        <span>Tampoco se tomara en cuenta en los reportes de inventario, conteo de productos, calculos de stock, calculos de valor de inventario, kardex o lotes.</span>

                    </div>
                </div>

            </div>

            <!-- Lotes -->
            <div class="card hoverCard" style="margin-bottom: 10px;">

                <div class="card-title" data-bs-toggle="collapse" data-bs-target="#Lotes" aria-expanded="false"
                    aria-controls="Lotes"
                    style="display: flex; justify-content: space-between; padding-left: 15px; padding-right: 15px; padding-top: 10px; cursor: pointer;">

                    <h4>Lotes</h4>

                    <button class="btn btn-outline-secondary " type="button" data-bs-toggle="collapse"
                        data-bs-target="#Lotes" aria-expanded="false" aria-controls="Lotes">
                        <i class="fas fa-chevron-down"></i>
                    </button>

                </div>

                <div class="collapse" id="Lotes">
                    <hr>
                    <div class="card-body" style="padding-top: 1px;">
                        <h4>Inicio</h4>
                        <span>En esta sección se podrá visualizar la información de los lotes de productos.</span>



                        <span> Los lotes son los grupos de productos que se venden en el sistema, estos lotes se asocian a
                            productos, y son considerados en conjunto como su stock. Esto permite llevar un control de los
                            productos mas detallado y separar el mismo por diferente fechas de vencimiento.</span>

                        <br><br>
                        <h4>Registrar Lote</h4>
                        <span>Para registrar un nuevo lote, debe de realizar un compra del producto que desee registrar
                            como lote, y al momento de registrar la compra, se le pedira que ingrese la fecha de vencimiento
                            y la cantidad de productos que desea registrar como lote.</span>
                        <span>Al registrar la compra se creara un nuevo lote del producto, con la cantidad y fecha de
                            vencimiento ingresada.</span>

                        <span> Para poder ingresar multiples lotes de un producto, se debera de realizar multiples compras
                            del mismo producto, con diferentes fechas de vencimiento y cantidad de productos.</span>

                        <br><br>
                        <h4>Modificar Lote</h4>
                        <span>Para modificar la información de los lotes, se deberá hacer desde la pantalla de productos,
                            podra ver un boton verde con un icono de cuadrados, al hacer clic en ese boton, se mostrara
                            una lista de los lotes del producto.</span>

                        <span>Una vez hecho clic en el boton de lotes, se mostrará una lista de los lotes del producto,
                            con la cantidad, fecha de vencimiento y opciones para modificar la fecha de vencimiento de un producto.</span>

                        <span>Podra modificar uno o varios lotes a la vez.</span>

                        <br><br>
                        <h4>Eliminar Lote</h4>
                        <span>Para eliminar un lote, se deberá hacer desde la pantalla de Kardex, un lote no se elimina
                            directamente, se marca como inactivo al llegar a 0 productos.</span>

                        <span>Un lote inactivo no puede ser modificado, ni vendido, ni comprado, ni movido, ni eliminado,
                            pero se mantiene en el sistema por razones de historial y auditoria.</span>

                        <span> Sin embargo, los lotes estan asociados a un inventario, por lo que al cerrar un inventario,
                            los lotes inactivos no se tomaran en cuenta en los calculos de stock, valor de inventario, kardex o reportes.</span>

                        <span> Simplemente, pasaran a ser parte del nuevo inventario activo y no se mostraran en la pantalla de lotes de productos, pero
                            se mostraran en el historial de lotes completos.</span>
                    </div>
                </div>

            </div>

            <!-- Compras -->
            <div class="card hoverCard" style="margin-bottom: 10px;">

                <div class="card-title" data-bs-toggle="collapse" data-bs-target="#Compras" aria-expanded="false"
                    aria-controls="Compras"
                    style="display: flex; justify-content: space-between; padding-left: 15px; padding-right: 15px; padding-top: 10px; cursor: pointer;">

                    <h4>Compras</h4>

                    <button class="btn btn-outline-secondary " type="button" data-bs-toggle="collapse"
                        data-bs-target="#Compras" aria-expanded="false" aria-controls="Compras">
                        <i class="fas fa-chevron-down"></i>
                    </button>

                </div>

                <div class="collapse" id="Compras">
                    <hr>
                    <div class="card-body" style="padding-top: 1px;">
                        <h4>Inicio</h4>
                        <span>En esta sección se podrá visualizar la información de las compras realizadas y registrar
                            nuevas compras.</span>



                        <span> Las compras son las transacciones de productos que se realizan en el sistema, estas compras permiten ingresar producto
                            al sistema, se diferencian de los movimientos de entrada de Kardex, ya que las compras son transacciones de productos y
                            los movimientos de Kardex son movimientos de stock.</span>

                        <br><br>
                        <h4>Registrar Compra</h4>
                        <span>Para registrar una nueva compra, se deberá hacer clic en el botón "Registrar Compra" con
                            un icono de un signo de más que se encuentra en la parte superior derecha de la pantalla.</span>
                        <span>Una vez hecho clic en el botón "Registrar Compra", se mostrará un formulario donde podrá seleccionar
                            los productos que desea registrarles una compra.</span>

                        <span>
                            En este apartado se le presenta la opcion de modificar el precio de compra y el proveedor en caliente, es decir, al momento de
                            registrar la compra, sin embargo, debe tener en cuenta que si modifica estos valores, seran modificados y se veran reflejados en el producto y en el proveedor
                            en las transacciones futuras.
                        </span>

                        <br><br>
                        <h4>Modificar Compra</h4>
                        <span>Pronto se agregara la funcion de establecer una compra como pendiente de pago, lo que permitira
                            modificar la compra despues de haberla registrado.</span>
                        <span class="text-danger">Por el momento, no es posible modificar una compra despues de haberla registrado, por lo que se recomienda
                            revisar bien los datos antes de registrar la compra.</span>

                        <span> Tip: Puede escribir en la barra de busqueda de productos la cantidad seguido de 'x' o '*' y el codigo del producto,
                            para agregar multiples productos a la compra.

                            Por ejemplo: 5x00001 o 5*00001, agregara 5 productos del producto con codigo 00001 a la compra.
                        </span>

                    </div>
                </div>

            </div>

            <!-- Kardex -->
            <div class="card hoverCard" style="margin-bottom: 10px;">

                <div class="card-title" data-bs-toggle="collapse" data-bs-target="#Kardex" aria-expanded="false"
                    aria-controls="Kardex"
                    style="display: flex; justify-content: space-between; padding-left: 15px; padding-right: 15px; padding-top: 10px; cursor: pointer;">

                    <h4>Kardex</h4>

                    <button class="btn btn-outline-secondary " type="button" data-bs-toggle="collapse"
                        data-bs-target="#Kardex" aria-expanded="false" aria-controls="Kardex">
                        <i class="fas fa-chevron-down"></i>
                    </button>

                </div>

                <div class="collapse" id="Kardex">
                    <hr>
                    <div class="card-body" style="padding-top: 1px;">

                        <h4>Inicio</h4>
                        <span>En esta sección se podrá visualizar la información de los movimientos de stock de los
                            productos.</span>



                        <span> El Kardex es el registro de los movimientos de stock de los productos, estos movimientos
                            permiten llevar un control de las entradas y salidas de los productos, asi como tambien los
                            movimientos de stock y valor de inventario.</span>

                        <br><br>
                        <h4>¿Que es el Kardex?</h4>
                        <span>El Kardex es el registro de los movimientos de stock de los productos, estos movimientos
                            permiten llevar un control de las entradas y salidas de los productos, asi como tambien los
                            movimientos de stock y valor de inventario.</span>

                        <br><br>
                        <h4>Registrar Movimiento</h4>
                        <span>Para registrar un nuevo movimiento de stock, se deberá hacer clic en el botón "Registrar
                            Movimiento" con un icono de un signo de más que se encuentra en la parte superior derecha de la
                            pantalla.</span>

                        <span>Una vez hecho clic en el botón "Registrar Movimiento", se mostrará un formulario con los
                            campos necesarios para registrar un nuevo movimiento de stock.
                            Puede realizar multiples movimientos de un mismo producto o varios a la vez.</span>

                        <span> Tip: Puede escribir en la barra de busqueda de productos la cantidad seguido de 'x' o '*' y el codigo del producto,
                            para agregar multiples productos al movimiento.

                            Por ejemplo: 5x00001 o 5*00001, agregara 5 productos del producto con codigo 00001 al movimiento.
                        </span>

                        <br><br>
                        <h4>Tipos de Movimientos</h4>
                        <span>Existen dos tipos de movimientos de stock, entradas y salidas.</span>
                        <span>Las entradas de stock son los movimientos de productos que se compran o ingresan al sistema,
                            y las salidas de stock son los movimientos de productos que se venden o salen del sistema.</span>



                        <span> Los movimientos de entrada se registraran al lote mas reciente registrado y activo que este asociado al sistema.</span>
                        <span> Los movimientos de salida se registraran al lote mas antiguo registrado y activo que este asociado al sistema.</span>

                        <br>
                        <h5 class="text-danger"> Si no hay lotes registrados o activos, no se podra realizar movimientos de stock.</h4>

                        <br><br>
                        <h4>¿Para que sirve el Kardex?</h4>
                        <span>El Kardex sirve para llevar un control de los movimientos de stock de los productos, asi como
                            tambien los movimientos de valor de inventario, entradas y salidas de productos, y el stock
                            disponible de los productos.</span>

                        <br><br>
                        <h4>¿Que pasa si no registro un movimiento de stock?</h4>
                        <span>Si no registra un movimiento de stock, el sistema no podra llevar un control de los productos
                            y transacciones realizadas en el sistema, lo que puede generar confusion en los reportes y
                            documentos generados por el sistema.</span>

                        <br><br>
                        <h4>¿Que pasa si registro un movimiento de stock incorrecto?</h4>
                        <span>Si registra un movimiento de stock incorrecto, se recomienda corregir el movimiento de stock
                            para llevar un control adecuado de los mismos, y evitar confusion en los reportes y documentos
                            generados por el sistema.</span>

                        <span> Si ingresa o da salida a demasiados productos, puede realizar un movimiento de stock contrario para corregir el error.</span>



                        <span class="text-danger"> Nota: Los movimientos de stock no se pueden eliminar, pero si se pueden modificar para corregir errores.</span>


                    </div>
                </div>

            </div>

            <!-- Enviar correo con asunto 'Ayuda' -->
            <div class="card hoverCard" style="margin-bottom: 10px;">

                <div class="card-title" data-bs-toggle="collapse" data-bs-target="#EnviarCorreo" aria-expanded="false"
                    aria-controls="EnviarCorreo"
                    style="display: flex; justify-content: space-between; padding-left: 15px; padding-right: 15px; padding-top: 10px; cursor: pointer;">

                    <h4>¿Necesita mas ayuda o tiene sugerencias?</h4>

                    <button class="btn btn-outline-secondary " type="button" data-bs-toggle="collapse"
                        data-bs-target="#EnviarCorreo" aria-expanded="false" aria-controls="EnviarCorreo">
                        <i class="fas fa-chevron-down"></i>
                    </button>

                </div>

                <div class="collapse" id="EnviarCorreo">
                    <hr>
                    <div class="card-body" style="padding-top: 1px;">
                        <h4>Envienos un correo con sus dudas</h4>
                        <span>Si necesita ayuda con alguna sección del sistema, puede enviar un correo con el asunto
                            'Error en *Apartado*': <a href="mailto: angelhidalgoc32@gmail.com"> Soporte.</a>
                        </span>
                        <span>En el correo deberá de especificar la sección del sistema con la que necesita ayuda, y
                            detallar el problema o duda que tenga con esa sección. Si es posible incluya capturas de pantalla
                            para una mejor comprensión del problema.
                        </span>

                        <span>Una vez enviado el correo, se le respondera a la brevedad posible con la solución o
                            respuesta a su problema o duda.</span>

                        <br><br>
                        <h4>Envienos un correo con sus sugerencias</h4>
                        <span>Si tiene alguna sugerencia que desee que se pueda implementar al sistema, escuchamos sus sugerencias al correo:
                             <a href="mailto:angelhidalgoc32@gmail.com"> Sugerencias</a>
                        </span>

                        <span>En el correo deberá de especificar la sugerencia que desea que se implemente al sistema, y
                            detallar la sugerencia o mejora que desea que se realice. Si es posible incluya capturas de pantalla
                            o ejemplos para una mejor comprensión de la sugerencia.
                        </span>

                        <span>Una vez enviado el correo, se le respondera a la brevedad posible con la respuesta a su
                            sugerencia o mejora.</span>

                    </div>
                </div>


            </div>

        </div>

    </div>

    <h5 style="display: flex; justify-content:center"> ¡Gracias por usar el sistema!</h5>
    <span>Esperamos que esta guia le haya sido de ayuda, si tiene alguna duda o problema, no dude en contactarnos
        al correo de soporte.</span>
    <span>Si tiene alguna sugerencia o mejora que desee que se implemente al sistema, no dude en contactarnos al
        correo de sugerencias.</span>
    <span>Recuerde que su opinion es importante para nosotros, y nos ayuda a mejorar el sistema para usted.</span>

    <span style="color: magenta;">-Magenta</span>
    <br><br>
    <small class="text-muted"> Ultima fecha de actualizacion: 25/02/2024</small>
</div>
<script>
    new Vue({

    });
</script>
@endsection

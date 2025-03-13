@extends('Layouts.Navigation')

@section('title', 'Menu Cajero')

@section('content')

    <div id="app">

        <div v-if="loading" role="alert" style="display: flex; justify-content: center; align-items: center;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <!-- Cajas -->
        <div class="card hoverCard" v-if="!loading">
            <div class="card-header" style="display: flex; justify-content: space-between;">
                <h3 class="card-title">Menu cajero</h3>
                <button class="btn btn-danger float-right" v-if="turnos.length > 0">Cerrar dia (Corte Z)</button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4" v-for="caja in cajeros" style="margin-bottom: 10px">
                        <div class="card hoverCard">
                            <div class="card-header"
                                style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
                                <h3 class="card-title
                                    text-center">
                                    @{{ caja.nombre }}</h3>
                                <small class="text-center">Ubicacion: @{{ caja.ubicacion }}</small>
                            </div>
                            <div class="card-body">
                                <div class="row" style="display: flex; justify-content: center; align-items: center;">
                                    <div class="col-lg-12"
                                        style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
                                        <button class="btn btn-success btn-block" data-bs-toggle="modal"
                                            data-bs-target="#turnoModal" @click="readyForTurno(caja)">
                                            Iniciar turno
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br>
        <!-- Turnos -->
        <div class="card hoverCard" v-if="!loading">
            <div class="card-header" style="display: flex; justify-content: space-between;">
                <h3 class="card-title">Turnos activos</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4" v-for="turno in turnos" style="margin-bottom: 10px">
                        <div class="card hoverCard">
                            <div class="card-header"
                                style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
                                <h3 class="card-title
                                    text-center">
                                    @{{ turno.caja }}</h3>

                            </div>
                            <div class="card-body">
                                <div class="row" style="display: flex; justify-content: center; align-items: center;">
                                    <div class="col-lg-12"
                                        style="display: flex; justify-content: center; align-items: center; flex-direction: column;">

                                        <span>Inicio: @{{ parseDate(turno.apertura) }}</span>
                                        <span>@{{ turno.vendedor }}</span>
                                        <span>Monto inicial: @{{ turno.montoInicial }}</span>
                                        <span>Entradas: $@{{ turno.totalEntradas ?? 0.00 }}</span>
                                        <span>Salidas: $@{{ turno.totalSalidas ?? 0.00 }}</span>
                                        <span>Total: $@{{ turno.montoCierre ?? 0.00 }}</span>
                                        <span class="badge bg-success mb-2">@{{ turno.estado }}</span>
                                        <button class="btn btn-warning btn-block mb-1" data-bs-toggle="modal"
                                            data-bs-target="#movimientoCajaModal" @click="newMovimiento(turno)">
                                            Realizar movimiento
                                        </button>
                                        <button class="btn btn-danger btn-block">
                                            Cerrar turno (Corte X)
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Movimiento modal-->
        <div class="modal fade" id="movimientoCajaModal" tabindex="-1" aria-labelledby="movimientoCajaModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down"
                style="min-height: 200px;">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="movimientoCajaModalLabel">Realizar movimiento</h1>
                    </div>
                    <div class="modal-body" style="text-align: justify; height: 150px;">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-floating mb-12">
                                    <h5>@{{ turnoMovimiento.caja }}</h5>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-floating mb-12">
                                    <h5>@{{ turnoMovimiento.vendedor }}</h5>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-floating mb-12">
                                    <h5>{{ Auth::user()->nombre . ' ' . Auth::user()->apellido }}</h5>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-floating mb-12">
                                    <h5>@{{  new Date().toLocaleString() }}</h5>
                                </div>
                            </div>
                            <!-- Tipo de movimiento -->
                            <div class="col-lg-4">
                                <div class="form-floating col-12" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-12">
                                        <select class="form-select" id="tipo" @change="validateMovimiento"
                                            name="tipo" aria-label="Floating label select example"
                                            v-model="movimiento.tipo">
                                            <option value="1">Entrada</option>
                                            <option value="2">Salida</option>
                                        </select>
                                        <label for="tipo">Tipo de movimiento<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip">
                                            @{{ movimientoErrors.tipo }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Monto -->
                            <div class="col-lg-4">
                                <div class="form-floating col-12" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-12">
                                        <input type="number" class="form-control" id="monton" name="monton"
                                            @change="validateMovimiento" placeholder="Monto" step="0.01"
                                            min="0" max="999999.99" @keyup="validateMovimiento" maxlength="6"
                                            v-model="movimiento.monto">
                                        <label for="floatingInput">Monto<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip">
                                            @{{ movimientoErrors.monto }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Descripcion -->
                            <div class="col-lg-4">
                                <div class="form-floating col-12" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-12">
                                        <input type="text" class="form-control" id="descripcion" name="descripcion"
                                            @keyup="validateMovimiento" placeholder="Descripcion"
                                            v-model="movimiento.descripcion">
                                        <label for="floatingInput">Descripcion<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip">
                                            @{{ movimientoErrors.descripcion }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            id="cancelMovimientoButton" @click="cleanMovimiento">Cerrar</button>
                        <button type="button" class="btn btn-success" id="EnviarMovimientoButton"
                            @click="sendMovimiento">Guardar movimiento</button>
                    </div>
                </div>

            </div>
        </div>

        <!--Turno modal-->
        <div class="modal fade" id="turnoModal" tabindex="-1" aria-labelledby="turnoModalLabel" aria-hidden="inert"
            data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down"
                style="min-height: 200px;">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="turnoModalLabel">Iniciar turno</h1>
                    </div>
                    <div class="modal-body" style="text-align: justify; height: 150px;">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-floating mb-12">
                                    <h5>@{{ cajaATurno.nombre }}</h5>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-floating mb-12">
                                    <h5>@{{ cajaATurno.ubicacion }}</h5>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-floating mb-12">
                                    <h5>{{ Auth::user()->nombre . ' ' . Auth::user()->apellido }}</h5>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-floating mb-12">
                                    <h5>@{{ now }}</h5>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-floating col-12" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-12">
                                        <input type="number" class="form-control" id="montoInicial" name="montoInicial"
                                            placeholder="Monto inicial" step="0.01" min="0" max="999999"
                                            @change="ValidateMonto" @keyup="ValidateMonto" maxlength="6"
                                            @submit="sendForm" v-model="montoInicial">
                                        <label for="floatingInput">Monto Inicial<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip">
                                            @{{ errors.montoInicial }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            id="cancelTurnoButton">Cerrar</button>
                        <button type="button" class="btn btn-success" id="startTurnButton" @click="sendForm">Iniciar
                            turno</button>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <script>
        new Vue({
            el: '#app',
            data: {
                cajeros: [],
                cajaATurno: {},
                now: new Date().toLocaleString(),
                montoInicial: null,
                errors: {},
                turnos: [],
                turnoMovimiento: {},
                movimiento: {
                    tipo: 1,
                    monto: null,
                    descripcion: null
                },
                loading: true,
                movimientoErrors: {}

            },
            methods: {
                async getCajeros() {
                    axios({
                            method: 'get',
                            url: '/allCajas',
                            params: {
                                onlyActive: true
                            }
                        })
                        .then(response => {
                            this.cajeros = response.data
                        })
                        .catch(error => {
                            swal.fire({
                                toast: true,
                                showCloseButton: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                title: 'Error',
                                text: 'Ha ocurrido un error al cargar las cajas',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            })
                        })
                },
                readyForTurno(caja) {
                    this.cajaATurno = caja
                },
                ValidateMonto() {
                    this.errors = {}

                    if (this.montoInicial < 0) {
                        document.getElementById('montoInicial').setAttribute('class', 'form-control is-invalid')
                        this.errors.montoInicial = 'El monto inicial no puede ser negativo'
                        return;
                    }

                    if (this.montoInicial == 0) {
                        document.getElementById('montoInicial').setAttribute('class', 'form-control is-invalid')
                        this.errors.montoInicial = 'El monto inicial no puede ser 0'
                        return;
                    }

                    if (this.montoInicial > 999999) {
                        document.getElementById('montoInicial').setAttribute('class', 'form-control is-invalid')
                        this.errors.montoInicial = 'El monto inicial no puede ser mayor a 999999'
                        return;
                    }

                    document.getElementById('montoInicial').setAttribute('class', 'form-control is-valid')

                },
                sendForm() {
                    this.ValidateMonto()

                    if (Object.keys(this.errors).length === 0) {

                        //Disable buttons
                        document.getElementById('startTurnButton').disable = true;
                        document.getElementById('cancelTurnoButton').disable = true;

                        //Animate loading
                        document.getElementById('startTurnButton').innerHTML =
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Iniciando turno...'

                        axios({
                            method: 'post',
                            url: '/turnos/start',
                            data: {
                                caja: this.cajaATurno.id,
                                montoInicial: this.montoInicial,
                                apertura: new Date().toISOString().slice(0, 19).replace('T', ' ')
                            }
                        }).then(response => {

                            if (response.data.success) {
                                swal.fire({
                                    toast: true,
                                    showCloseButton: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    title: 'Turno iniciado',
                                    text: response.data.success,
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar',
                                });
                            } else {
                                swal.fire({
                                    toast: true,
                                    showCloseButton: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    title: 'Error',
                                    text: response.data.error,
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }

                        }).catch(error => {
                            swal.fire({
                                toast: true,
                                showCloseButton: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                title: 'Error',
                                text: 'Ha ocurrido un error al iniciar el turno',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            })
                        }).finally(() => {

                            document.getElementById('startTurnButton').disable = false;
                            document.getElementById('cancelTurnoButton').disable = false;
                            document.getElementById('startTurnButton').innerHTML =
                                'Iniciar turno'

                            this.getActiveTurnos()
                            this.getCajeros()
                            this.montoInicial = 0.00
                            this.errors = {}
                            document.getElementById('montoInicial').setAttribute('class', 'form-control')
                            document.getElementById('cancelTurnoButton').click()
                        })
                    } else {
                        swal.fire({
                            toast: true,
                            showCloseButton: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            title: 'Error',
                            text: 'Por favor, corrija los errores en el formulario',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        })
                    }
                },
                async getActiveTurnos() {
                    axios({
                            method: 'get',
                            url: 'allActiveTurnos'
                        })
                        .then(response => {
                            this.turnos = response.data
                        })
                        .catch(error => {
                            console.log(error)
                        }).finally(() => {
                            this.loading = false;
                        })
                },
                parseDate(date) {

                    if (date == null) {
                        return '-';
                    }

                    let dateObj = new Date(date);
                    let month = dateObj.getUTCMonth() + 1;
                    let day = dateObj.getUTCDate();
                    let year = dateObj.getUTCFullYear();
                    let hour = dateObj.getHours();
                    let minutes = dateObj.getMinutes();


                    return day + "/" + month + "/" + year + " " + hour + ":" + minutes;

                },
                newMovimiento(turno) {
                    this.turnoMovimiento = turno
                },
                sendMovimiento() {

                    this.validateMovimiento()

                    if (Object.keys(this.movimientoErrors).length === 0) {

                        //deshabilitar botones
                        document.getElementById('EnviarMovimientoButton').disable = true;
                        document.getElementById('cancelMovimientoButton').disable = true;

                        //animar loading
                        document.getElementById('EnviarMovimientoButton').innerHTML =
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando movimiento...'

                        axios({
                            method: 'post',
                            url: '/movimientos/store',
                            data: {
                                turno: this.turnoMovimiento.id,
                                tipo: this.movimiento.tipo,
                                monto: this.movimiento.monto,
                                descripcion: this.movimiento.descripcion,
                                caja: this.turnoMovimiento.caja
                            }
                        }).then(response => {
                            if (response.data.success) {
                                swal.fire({
                                    toast: true,
                                    showCloseButton: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    title: 'Movimiento guardado',
                                    text: response.data.success,
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar',
                                });
                            } else {
                                swal.fire({
                                    toast: true,
                                    showCloseButton: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    title: 'Error',
                                    text: response.data.error,
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                        }).catch(error => {
                            swal.fire({
                                toast: true,
                                showCloseButton: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                title: 'Error',
                                text: 'Ha ocurrido un error al guardar el movimiento',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            })
                        }).finally(() => {
                            document.getElementById('EnviarMovimientoButton').disable = false;
                            document.getElementById('cancelMovimientoButton').disable = false;
                            document.getElementById('EnviarMovimientoButton').innerHTML =
                                'Guardar movimiento'

                            this.getActiveTurnos()
                            this.getCajeros()
                            this.cleanMovimiento()
                            document.getElementById('cancelMovimientoButton').click()
                        });

                    } else {
                        swal.fire({
                            toast: true,
                            showCloseButton: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            title: 'Error',
                            text: 'Por favor, corrija los errores en el formulario',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        })
                    }

                },
                cleanMovimiento() {
                    this.movimiento = {
                        tipo: 1,
                        monto: null,
                        descripcion: null
                    }
                    this.movimientoErrors = {}

                    document.getElementById('monton').setAttribute('class', 'form-control')
                    document.getElementById('descripcion').setAttribute('class', 'form-control')
                    document.getElementById('tipo').setAttribute('class', 'form-select')
                },
                validateMovimiento() {
                    this.movimientoErrors = {}

                    if (this.movimiento.monto == null) {
                        document.getElementById('monton').setAttribute('class', 'form-control is-invalid')
                        this.movimientoErrors.monto = 'El monto no puede estar vacio'
                    } else if (this.movimiento.monto < 0) {
                        document.getElementById('monton').setAttribute('class', 'form-control is-invalid')
                        this.movimientoErrors.monto = 'El monto no puede ser negativo'
                    } else {
                        document.getElementById('monton').setAttribute('class', 'form-control is-valid')
                    }

                    if (this.movimiento.descripcion == null) {
                        document.getElementById('descripcion').setAttribute('class', 'form-control is-invalid')
                        this.movimientoErrors.descripcion = 'La descripcion no puede estar vacia'
                    } else {
                        document.getElementById('descripcion').setAttribute('class', 'form-control is-valid')
                    }

                    document.getElementById('tipo').setAttribute('class', 'form-select is-valid')

                }

            },
            mounted() {
                this.getCajeros()
                this.getActiveTurnos()
            }
        })
    </script>
@endsection

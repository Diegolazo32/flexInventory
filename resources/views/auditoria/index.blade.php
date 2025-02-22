@extends('Layouts.Navigation')

@section('title', 'Auditoria')

@section('content')
    <div id="app">
        <div class="card hoverCard">
            <div class="card-header">
                <div class="row" style="display: flex; align-items: center;">
                    <div class="col-lg-10">
                        <h1>Auditoria</h1>
                        <small class="text-muted"></small>
                    </div>
                    <!-- Botones de accion -->
                    <div class="col-lg-2 d-flex justify-content-end">
                        <!--<button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#crearPermisoModal" style="height: 40px;">
                                                    <i class="fas fa-plus"></i>
                                                </button>

                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="editPermisoModalBtn"
                                                    data-bs-target="#editPermisoModal" style="height: 40px;" hidden>
                                                    Editar permiso
                                                </button>

                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="deletePermisoModalBtn"
                                                    data-bs-target="#deletePermisoModal" style="height: 40px;" hidden>
                                                    Eliminar permiso
                                                </button>-->
                    </div>
                </div>
            </div>
            <!-- Buscador -->
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-10">
                        <div class="row">

                        </div>
                    </div>
                </div>
            </div>
            <!-- Tabla de auditorias -->
            <div class="row">
                <div class="card-body">
                    <div v-if="loading" role="alert" style="display:block; margin-left: 50%;" id="loading">
                        <i class="fas fa-spinner fa-spin"></i> Cargando...
                    </div>

                    <div v-if="auditorias.error" class="alert alert-danger" role="alert">
                        <h3>@{{ auditorias.error }}</h3>
                    </div>

                    <div v-if="auditorias.length > 0" class="table-responsive">

                        <table ref="table" class="table table-striped  table-hover" style="text-align: center;">
                            <thead>
                                <tr>
                                    <th scope="col">Usuario</th>
                                    <th scope="col">Evento</th>
                                    <th scope="col">Tabla</th>
                                    <th scope="col">Fecha</th>

                                </tr>
                            </thead>
                            <tbody>
                                <!-- vue foreach -->
                                <tr v-for='auditoria in auditorias' :key="auditoria.id">
                                    <td>@{{ auditoria.usuario }}</td>
                                    <td>@{{ auditoria.evento }}</td>
                                    <td>@{{ auditoria.tabla }}</td>
                                    <td>@{{ formatDate(auditoria.created_at) }}</td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer" style="overflow: auto;">
                <div class="d-flex justify-content-center" style="gap: 10px;">
                    <ul class="pagination justify-content-center">
                        <li class="page-item" :disabled="page === 1">
                            <a class="page-link" href="#" aria-label="Previous" @click="pageMinus">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li class="page-item" v-for="pageNumber in totalPages" :key="pageNumber"
                            :class="{ active: pageNumber === page }">
                            <a class="page-link" href="#" @click="specificPage(pageNumber)">
                                @{{ pageNumber }}
                            </a>
                        </li>
                        <li class="page-item" :disabled="page === totalPages">
                            <a class="page-link" href="#" aria-label="Next" @click="pagePlus">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                    <ul class="pagination justify-content-center">

                        <li class="page-item">
                            <select class="form-select" v-model="per_page" @change="changePerPage">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    <script>
        new Vue({
            el: '#app',
            data: {
                auditorias: [],

                page: 1,
                per_page: 10,
                total: 0,
                totalPages: 0,
                nextPageUrl: '',
                prevPageUrl: '',
            },
            methods: {
                //Paginacion
                pageMinus() {
                    if (this.page > 1) {
                        this.page--;
                        this.getAllAudits();
                    }
                },
                pagePlus() {
                    if (this.page < this.totalPages) {
                        this.page++;
                        this.getAllAudits();
                    }
                },
                specificPage(page) {
                    this.page = page;
                    this.getAllAudits();
                },
                changePerPage() {
                    this.page = 1;
                    this.getAllAudits();
                },
                //Limpiar formulario y busqueda
                cleanSearch() {
                    this.search = '';
                    this.searchError = '';
                    this.getAllAudits();
                },

                formatDate(date) {

                    if (!date) {
                        return;
                    }

                    let dateObj = new Date(date);
                    let month = dateObj.getUTCMonth() + 1;
                    let day = dateObj.getUTCDate();
                    let year = dateObj.getUTCFullYear();
                    let hours = dateObj.getHours();
                    let minutes = dateObj.getMinutes();
                    let seconds = dateObj.getSeconds();

                    return day + "/" + month + "/" + year + " - -  " + hours + ":" + minutes + ":" + seconds;

                },
                //Obtener recursos
                async getAllAudits() {

                    try {
                        axios({
                            method: 'get',
                            url: '/allAuditorias',
                            params: {
                                page: this.page,
                                per_page: this.per_page,
                                search: this.search,
                            }
                        }).then(response => {

                            this.loading = false;
                            this.auditorias = response.data.data;

                            this.total = response.data.total;
                            this.totalPages = response.data.last_page;

                            if (this.page > this.totalPages) {
                                this.page = 1;
                                this.getAllAudits();
                            } else {
                                this.page = response.data.current_page;
                            }

                            this.per_page = response.data.per_page;
                            this.nextPageUrl = response.data.next_page_url;
                            this.prevPageUrl = response.data.prev_page_url;

                        }).catch(error => {
                            this.loading = false;
                            swal.fire({
                                title: 'Error',
                                text: 'Ha ocurrido un error al obtener los permisos',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        })

                    } catch (error) {

                    }
                },


            },
            mounted() {

                this.getAllAudits();

            }
        });
    </script>
@endsection

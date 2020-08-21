<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <header style="margin-top:30px;">
                    <b-row>
                        <h3 style="margin-right:54vw;">
                            Reservaciones
                        </h3>
                        <b-button
                            variant="primary"
                            data-toggle="modal"
                            @click="openModal"
                            >Agregar</b-button
                        >
                    </b-row>
                </header>
                <br />
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Restaurant</th>
                            <th>Fecha</th>
                            <th>Mesa</th>
                            <th>Cantidad de Personas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(reservation, index) in reservations"
                            :key="reservation.id"
                        >
                            <td>{{ index + 1 }}</td>
                            <td>{{ reservation.restaurant.name }}</td>
                            <td>{{ reservation.reservation_date }}</td>
                            <td>{{ reservation.table_name }}</td>
                            <td>{{ reservation.amount_of_people }}</td>
                            <td>
                                <a href="#" @click="edit(reservation)">
                                    <i
                                        class="fa fa-pencil"
                                        style="margin-right:8px;"
                                    ></i>
                                </a>

                                <a href="#" @click="destroy(reservation.id)">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <b-modal ref="my-modal" id="bv-modal" hide-footer>
            <template v-slot:modal-title>
                <h5 v-show="!editMode">Agregar nueva Reservacion</h5>
                <h5 v-show="editMode">Actualizar Reservacion</h5>
            </template>
            <form @submit.prevent="editMode ? update() : add()">
                <b-container fluid>
                    <b-form-group>
                        <label for="restaurant">Restaurant</label>
                        <b-form-select
                            name="restaurant"
                            v-model="form.restaurant_id"
                            :options="restaurants"
                            :class="{ 'is-invalid': form.errors.has('restaurant') }"
                        >
                            <b-form-select-option v-for="restaurant in restaurants" :value="restaurant.id" :key="restaurant.id">{{restaurant.name}} - {{restaurant.city}}</b-form-select-option>
                        </b-form-select>
                        <has-error :form="form" field="restaurant"></has-error>
                    </b-form-group>
                    <b-form-group>
                        <label for="date">Fecha</label>
                        <b-form-datepicker id="date" v-model="form.reservation_date" 
                            :class="{'is-invalid': form.errors.has('date')}">
                        </b-form-datepicker>
                        <has-error :form="form" field="date"></has-error>
                    </b-form-group>
                    <b-form-group>
                        <label for="table_name">Mesa</label>
                        <b-form-input
                            name="table_name"
                            v-model="form.table_name"
                            placeholder="nombre de la Mesa"
                            :class="{
                                'is-invalid': form.errors.has('table_name')
                            }"
                        ></b-form-input>
                        <has-error :form="form" field="table_name"></has-error>
                    </b-form-group>
                    <b-form-group>
                        <label for="amount_of_people">Cantidad de personas</label>
                        <b-form-input
                            name="amount_of_people"
                            v-model="form.amount_of_people"
                            placeholder="Cantidad de personas"
                            :class="{ 'is-invalid': form.errors.has('amount_of_people') }"
                        ></b-form-input>
                        <has-error :form="form" field="amount_of_people"></has-error>
                    </b-form-group>
                </b-container>
            </form>
            <b-button
                v-show="!editMode"
                variant="primary"
                data-toggle="modal"
                @click="add"
                >Crear</b-button
            >
            <b-button
                v-show="editMode"
                variant="primary"
                data-toggle="modal"
                @click="update"
                >Actualizar</b-button
            >
        </b-modal>
    </div>
</template>

<script>
export default {
    data() {
        return {
            editMode: false,
            restaurants: [],
            reservations: {},
            form: new Form({
                id:"",
                restaurant_id: "",
                reservation_date: "",
                table_name: "",
                amount_of_people: ""
            })
        };
    },
    methods: {
        loadRestaurants() {
            axios
                .get("api/restaurants")
                .then(data => {
                    this.restaurants = data.data.data;
                })
                .catch(error => console.log(error));
        },
        loadReservations() {
            axios
                .get("api/reservations")
                .then(data => {
                    this.reservations = data.data.data;
                })
                .catch(error => console.log(error));
        },
        openModal() {
            this.$refs["my-modal"].show();
        },
        add() {
            this.form
                .post("api/reservations")
                .then((response) => {
                    if(response.status == "204"){
                        Toast.fire({
                            icon: "Error",
                            title: "Se ha alcanzado el limite de reservaciones diarias"
                        });
                    }else{
                        Toast.fire({
                            icon: "success",
                            title: "La Reservación ha sido creada"
                        });
                    }                    
                    this.loadReservations();
                    this.$refs["my-modal"].hide();
                })
                .catch(error => {
                    Toast.fire({
                        icon: "error",
                        title: "Ha ocurrido un error"
                    });
                });
        },
        edit(reservation) {
            this.form.clear();
            this.form.reset();
            this.editMode = true;
            this.$refs["my-modal"].show();
            this.form.fill(reservation);
        },
        update() {
            this.form
                .put("api/reservations/" + this.form.id)
                .then(() => {
                    Toast.fire({
                        icon: "success",
                        title: "La Reservación ha sido actualizada"
                    });
                    this.loadReservations();
                    this.$refs["my-modal"].hide();
                })
                .catch(error => {
                    console.log(error);
                    Toast.fire({
                        icon: "error",
                        title: "Ha ocurrido un error"
                    });
                });
        },
        destroy(id) {
            this.form
                .delete("api/reservations/" + id)
                .then(() => {
                    Swal.fire({
                        title: "¿Esta seguro de eliminar a esta Reservación?",
                        text: "Luego de eliminada no se podra revertir",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Si, deseo eliminarla",
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33"
                    }).then(result => {
                        if (result.value) {
                            Swal.fire(
                                "La Reservación Eliminada",
                                "La Reservación seleccionada ha sido eliminada",
                                "success"
                            );
                            this.loadReservations();
                        }
                    });
                })
                .catch(error => {
                    Swal.fire({
                        title: "Ooop!",
                        text: "Algo ha salido mal",
                        icon: "error"
                    });
                });
        }
    },
    created() {
        this.loadRestaurants();
        this.loadReservations();
    }
};
</script>

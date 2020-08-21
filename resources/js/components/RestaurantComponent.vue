<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <header style="margin-top:30px;">
                    <b-row>
                        <h3 style="margin-right:54vw;">
                            Restaurantes
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
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Dirección</th>
                            <th>Ciudad</th>
                            <th>URL Foto</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(restaurant, index) in restaurants"
                            :key="restaurant.id"
                        >
                            <td>{{ index + 1 }}</td>
                            <td>{{ restaurant.name }}</td>
                            <td>{{ restaurant.description }}</td>
                            <td>{{ restaurant.address }}</td>
                            <td>{{ restaurant.city }}</td>
                            <td>{{ restaurant.photo_url }}</td>
                            <td>
                                <a href="#" @click="edit(restaurant)">
                                    <i
                                        class="fa fa-pencil"
                                        style="margin-right:8px;"
                                    ></i>
                                </a>

                                <a href="#" @click="destroy(restaurant.id)">
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
                <h5 v-show="!editMode">Agregar nuevo Restaurant</h5>
                <h5 v-show="editMode">Actualizar Restaurant</h5>
            </template>
            <form @submit.prevent="editMode ? update() : add()">
                <b-container fluid>
                    <b-form-group>
                        <label for="name">Nombre</label>
                        <b-form-input
                            name="name"
                            type="text"
                            v-model="form.name"
                            placeholder="Nombre del Restaurant"
                            :class="{ 'is-invalid': form.errors.has('name') }"
                        ></b-form-input>
                        <has-error :form="form" field="name"></has-error>
                    </b-form-group>
                    <b-form-group>
                        <label for="descripcion">Descripción</label>
                        <b-form-input
                            name="descripcion"
                            type="text"
                            v-model="form.description"
                            placeholder="Descripcion del Restaurant"
                            :class="{
                                'is-invalid': form.errors.has('description')
                            }"
                        ></b-form-input>
                        <has-error :form="form" field="descripcion"></has-error>
                    </b-form-group>
                    <b-form-group>
                        <label for="address">Dirección</label>
                        <b-form-input
                            name="address"
                            v-model="form.address"
                            placeholder="Dirección del Restaurant"
                            :class="{
                                'is-invalid': form.errors.has('address')
                            }"
                        ></b-form-input>
                        <has-error :form="form" field="address"></has-error>
                    </b-form-group>
                    <b-form-group>
                        <label for="city">Ciudad</label>
                        <b-form-input
                            name="city"
                            v-model="form.city"
                            placeholder="Ciudad del Restaurant"
                            :class="{ 'is-invalid': form.errors.has('city') }"
                        ></b-form-input>
                        <has-error :form="form" field="city"></has-error>
                    </b-form-group>
                    <b-form-group>
                        <label for="photo_url">URL Foto</label>
                        <b-form-input
                            name="photo_url"
                            v-model="form.photo_url"
                            placeholder="URL de la foto del Restaurant"
                            :class="{
                                'is-invalid': form.errors.has('photo_url')
                            }"
                        ></b-form-input>
                        <has-error :form="form" field="photo_url"></has-error>
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
            restaurants: {},
            form: new Form({
                id: "",
                name: "",
                description: "",
                address: "",
                city: "",
                photo_url: ""
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
        openModal() {
            this.$refs["my-modal"].show();
        },
        add() {
            this.form
                .post("api/restaurants")
                .then(() => {
                    Toast.fire({
                        icon: "success",
                        title: "El Restaurant ha sido creado"
                    });
                    this.loadRestaurants();
                    this.$refs["my-modal"].hide();
                })
                .catch(error => {
                    Toast.fire({
                        icon: "error",
                        title: "Ha ocurrido un error"
                    });
                });
        },
        edit(restaurant) {
            this.form.clear();
            this.form.reset();
            this.editMode = true;
            this.$refs["my-modal"].show();
            this.form.fill(restaurant);
        },
        update() {
            this.form
                .put("api/restaurants/" + this.form.id)
                .then(() => {
                    Toast.fire({
                        icon: "success",
                        title: "El Restaurant ha sido actualizado"
                    });
                    this.loadRestaurants();
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
                .delete("api/restaurants/" + id)
                .then(() => {
                    Swal.fire({
                        title: "¿Esta seguro de eliminar a este Restaurant?",
                        text: "Luego de eliminada no se podra revertir",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Si, deseo eliminarla",
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33"
                    }).then(result => {
                        if (result.value) {
                            Swal.fire(
                                "Restaurant Eliminado",
                                "El restaurant seleccionada ha sido eliminado",
                                "success"
                            );
                            this.loadRestaurants();
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
    }
};
</script>

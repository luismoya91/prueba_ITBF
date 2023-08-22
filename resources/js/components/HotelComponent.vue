<template>
    <div class="container " style="padding-top: 240px;">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Creacion Hotel</div>

                    <div class="card-body">
                        <form @submit.prevent="addhotel">
                                <!-- <h3>Agregar compañia</h3> -->
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" placeholder="" name="name" id="name" class="form-control mb-2" v-model="hotel.name" required>
                                </div>
                                <div class="form-group">
                                    <label for="address">Dirección</label>
                                    <input type="text" placeholder="" name="address" id="address" class="form-control mb-2" v-model="hotel.address" required>
                                </div>
                                <div class="form-group">
                                    <label for="city">Ciudad</label>
                                    <input type="text" placeholder="" name="city" id="city" class="form-control mb-2" v-model="hotel.city" required>
                                </div>
                                <div class="form-group">
                                    <label for="doc_number">NIT</label>
                                    <input type="text" placeholder="NIT/RUT" name="doc_number" id="doc_number" class="form-control mb-2" v-model="hotel.doc_number" required>
                                </div>
                                <div class="form-group">
                                    <label for="total_rooms">Numero de habitaciones</label>
                                    <input type="number" placeholder="" name="total_rooms" id="total_rooms"
                                    class="form-control mb-2" v-model="hotel.total_rooms" required>
                                </div>
                                <button class="btn btn-primary" type="submit">Guardar</button>
                            </form>
                            <hr class="mt-3">
                            <div class="row">
                            <div class="col-md-12">


                                <h3>Hoteles</h3>
                                <ul class="list-group my-2">
                                    <li class="list-group-item mt-1 row" v-for="(hotel_in, index) in hotels" :key="index" style="display: flex;">

                                        <div class="col-md-3">
                                            <p><strong>Nombre:</strong>  <br>
                                                {{ hotel_in.name }}</p>
                                            <p><strong>Ciudad:</strong><br>{{ hotel_in.city }}</p>
                                            <p><strong>Direccion:</strong>   <br>{{ hotel_in.address }}</p>
                                            <p><strong>NIT:</strong> <br>{{ hotel_in.doc_number }}</p>
                                            <p><strong>Total Habitaciones:</strong><br>{{ hotel_in.total_rooms }}</p>

                                        </div>
                                        <div class="col-md-9">
                                            <room-component :roomsall="hotel_in.rooms"
                                            :max_qty="hotel_in.total_rooms" :id_hotel="hotel_in.id"
                                            ></room-component>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['auth','hotelsa'],
        data() {
            return {
                hotels: [],
                hotel: {
                    id: 0,
                    name: '',
                    address: '',
                    doc_number: '',
                    city: '',
                    total_rooms: '',
                    rooms: [{
                        id_hotel: '',
                        qty : '',
                        type : '',
                        accommodation : '',
                        }
                    ],


                },
                modeEdit: false,
            }
        },
        methods:{
            addhotel(){
                //registro los valores para guardar
                const params = {
                    name : this.hotel.name,
                    address : this.hotel.address,
                    doc_number : this.hotel.doc_number,
                    city : this.hotel.city,
                    total_rooms : this.hotel.total_rooms,

                }
                //limpio campos del input
                this.hotel.name = '';
                this.hotel.address = '';
                this.hotel.city = '';
                this.hotel.total_rooms = '';
                this.hotel.doc_number = '';

                axios.post('/api/hotels',params)
                    .then(
                        res =>{
                        if(res.data.error){
                            alert(res.data.errors);
                            return;
                        }else{
                            this.hotels = res.data.hotel;
                        }
                })
            }
        },
        mounted() {
            axios.get('/api/hotels')
                    .then(
                        res =>{
                            this.hotels = res.data.data;
                            })
        }
    }
</script>

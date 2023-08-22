<template>
    <div class="row">
        <h4>Habitaciones</h4>
        <br><br>
        <div v-if="getTotalqty(roomsold) < max">
            <form @submit.prevent="addRoom(hotel_id)">
                <table class="table " v-for="(room, index) in rooms">
                    <thead>
                        <th>#</th>
                        <th>Tipo</th>
                        <th>Acomodación</th>
                        <th>Cantidad</th>
                        <th></th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ index +1}}</td>
                            <td>
                                <select class="form-select" aria-label="" v-model="room.type" required>
                                    <option value="">[Seleccione]</option>
                                    <option value="ESTANDAR">ESTANDAR</option>
                                    <option value="JUNIOR">JUNIOR</option>
                                    <option value="SUITE">SUITE</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-select" aria-label="" v-if="room.type === 'ESTANDAR' " v-model="room.accommodation" required>
                                    <option value="">[Seleccione]</option>
                                    <option value="SENCILLA">SENCILLA</option>
                                    <option value="DOBLE">DOBLE</option>
                                </select>
                                <select class="form-select" aria-label="" v-else-if="room.type === 'JUNIOR'" v-model="room.accommodation" required>
                                    <option value="">[Seleccione]</option>
                                    <option value="TRIPLE">TRIPLE</option>
                                    <option value="CUADRUPLE">CUADRUPLE</option>
                                </select>
                                <select class="form-select" aria-label=""  v-else-if="room.type === 'SUITE'" v-model="room.accommodation" required>
                                    <option value="">[Seleccione]</option>
                                    <option value="SENCILLA">SENCILLA</option>
                                    <option value="DOBLE">DOBLE</option>
                                    <option value="TRIPLE">TRIPLE</option>
                                </select>
                                <select class="form-select" aria-label=""  v-else v-model="room.accommodation" required>
                                    <option value="">[Seleccione]</option>
                                </select>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="number" placeholder="" name="total_rooms" id="total_rooms"
                                    class="form-control mb-2" v-model="room.qty" required>
                                </div>
                            </td>
                            <td data-toggle="tooltip" data-placement="right" title="Agregar campos" v-if="index === 0 ">
                                <svg title="Agregar" v-on:click="addRoomInput()"  xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="40" height="40" viewBox="0 0 48 48">
                                <path fill="#4caf50" d="M44,24c0,11.045-8.955,20-20,20S4,35.045,4,24S12.955,4,24,4S44,12.955,44,24z"></path><path fill="#fff" d="M21,14h6v20h-6V14z"></path><path fill="#fff" d="M14,21h20v6H14V21z"></path>
                                </svg>

                            </td>
                            <td  data-toggle="tooltip" data-placement="right" title="Eliminar campos" v-if="index > 0 ">
                                <svg v-on:click="deleteRoomInput(index)" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="60" height="60" viewBox="0 0 48 48">
                                <path fill="#FF0000" d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm7 14h-14v-4h14v4z"></path>
                                </svg>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-end">
                    <button class="btn btn-primary float-right">Guardar</button>
                </div>
            </form>
        </div>
        <table v-if="roomsold.length > 0" class="table ">
            <thead>
                <th>#</th>
                <th>Tipo</th>
                <th>Acomodación</th>
                <th>Cantidad</th>
                <th></th>
            </thead>
            <tbody>
                <tr v-for="(a, b) in roomsold">
                    <td>{{ b +1 }}</td>
                    <td>{{ a.type }}</td>
                    <td>{{ a.accommodation }}</td>
                    <td>{{ a.qty }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <div v-if="roomsold.length > 0" class="text-end">
            Total habitaciones en uso {{ getTotalqty(roomsold) }}
        </div>
    </div>
</template>

<script>
    export default {
        props:['id_hotel','roomsall','max_qty'],
        data() {
            return {
                roomsold :[],
                max : 0,
                type : 0,
                accommodation : 0,
                qty : '',
                hotel_id : '',
                rooms:[{
                    type : 0,
                    accommodation : 0,
                    qty : '',
                }]

            }
        },
        methods:{
            addRoomInput(){
                this.rooms.push({
                        type : '',
                        qty : '',
                        accommodation : ''
                    });

            },
            deleteRoomInput(key){
                this.rooms.splice(key ,1);

            }
            ,
            addRoom(hotel_id){
                const params = {
                    id_hotel : hotel_id,
                    body : this.rooms,

                }
                axios.post('/api/rooms',params)
                    .then(
                        res =>{
                        if(res.data.error){
                            alert(res.data.errors);
                            return;
                        }else{
                            this.rooms = [{
                                type : 0,
                                accommodation : 0,
                                qty : '',
                            }];
                            res.data.room.forEach(element => {

                                this.roomsold.push(element);
                            });
                        }
                })
            },
            getTotalqty(array){
                if( typeof (array) === 'undefined'){
                    return 0;
                }else{
                    return array.reduce((total, num) => total + parseInt( num.qty), 0)
                }
            }
        },
        mounted() {
            this.roomsold = this.roomsall;
            this.max = this.max_qty;
            this.hotel_id = this.id_hotel;
        }
    }
</script>

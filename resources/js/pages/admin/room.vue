<template>
        <v-app>
            <card>
                <v-btn class="ma-2" color="primary" :to="'/admin/createroom'"><!--@click="confirmation()" reorder-->
                    <v-icon left>add</v-icon>เพิ่ม
                </v-btn>
            <v-simple-table >
                <template v-slot:default>
                  <thead>
                    <tr>
                      <th class="text-left" >รหัส</th>
                      <th class="text-left">ชื่อห้อง</th>
                      <th class="text-left">อาคารเรียน</th>
                      <th class="text-left">เปิด</th>
                      <th class="text-left">ปิด</th>
                      <th class="text-left"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="room in roomtotal" :key="room.id">
                        <td>{{room.id}}</td>
                        <td>{{room.name}}</td>
                        <td>{{room.buildingname}}</td>
                        <td>{{room.open}}</td>
                        <td>{{room.close}}</td>
                        <td>
                        <v-btn class="ma-2" color="warning" :to="'/admin/editroom/'+room.id"><!--@click="confirmation()" reorder-->
                            <v-icon left>edit </v-icon>แก้ไข
                        </v-btn>
                        <v-btn class="ma-2" color="error" @click="dialog = true"><!--@click="confirmation()" reorder-->
                            <v-icon left>delete </v-icon>ลบ
                        </v-btn>
                        </td>

                        <v-dialog v-model="dialog" max-width="300px">
                            <v-card >
                              <v-card-title>คุณต้องการลบข้อมูลอาคารใช่หรือไม่</v-card-title>
                              <v-card-actions >
                                <v-spacer></v-spacer>
                                <v-btn color="red darken-4" text @click="dialog = false" >ไม่</v-btn>
                                <v-btn color="blue darken-1" text @click="Delete(room.id)">ใช่</v-btn>
                              </v-card-actions>
                            </v-card>
                          </v-dialog>
                    </tr>
                  </tbody>
                </template>
            </v-simple-table>
            </card>
        </v-app>
</template>
<script>
import axios from 'axios';
export default {
    middleware:'admin',
    name:'adminroom',
    data(){
        return{
            rooms:'',
            building:'',
            roomtotal:[],
            dialog:false,
        }
    },
    mounted(){
        this.getRoomData();
    },
    methods:{
        getBuildingData(){
            axios.get('/api/buildings').then(response => {
                this.building = response.data.building;
            })
        },
        getRoomData(){
            this.getBuildingData();
            axios.get('/api/rooms').then(response => {
                this.rooms = response.data.room;
                for (let index = 0; index < this.rooms.length; index++) {
                    console.log(this.rooms[index].id)
                    for (let buildingloop = 0; buildingloop < this.building.length; buildingloop++) {
                        
                        if (this.rooms[index].building_id == this.building[buildingloop].id){
                            this.roomtotal.push({
                            id:this.rooms[index].id,
                            name:this.rooms[index].room_name,
                            buildingname:this.building[buildingloop].building_name,
                            open:this.building[buildingloop].open,
                            close:this.building[buildingloop].close});
                        }
                    }
                }
            })
        },

        Delete(id){
          axios.delete('/api/rooms/'+id,{
            id:id
          })
          this.$router.go();
        },
    }
    
}
</script>
<template>
        <v-app>
            <card>
              <v-btn class="ma-2" color="primary" @click="Create"><!--@click="confirmation()" reorder-->
                <v-icon left>add </v-icon>เพิ่ม
              </v-btn>
            <v-simple-table >
                <template v-slot:default>
                  <thead>
                    <tr>
                      <th class="text-left">ชื่ออาคาร</th>
                      <th class="text-left">เวลาเปิด</th>
                      <th class="text-left">เวลาปิด</th>
                      <th class="text-left">สถานะ</th>
                      <th class="text-left"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="building in buildings"
                    :key='building.id'>
                        <td>{{ building.building_name }}</td>
                        <td>{{ building.open }}</td>
                        <td>{{ building.close }}</td>
                        <td>เปิด</td>
                        <td>
                        <v-btn class="ma-2" color="warning" @click="Edit(building.id)"><!--@click="confirmation()" reorder-->
                            <v-icon left>edit </v-icon>แก้ไข
                        </v-btn>
                        <v-btn class="ma-2" color="error" @click="dialog = true"><!--@click="confirmation()" reorder-->
                            <v-icon left >delete </v-icon>ลบ
                        </v-btn>
                        </td>

                        <v-dialog v-model="dialog" max-width="300px">
                            <v-card >
                              <v-card-title>คุณต้องการลบข้อมูลอาคารใช่หรือไม่</v-card-title>
                              <v-card-actions >
                                <v-spacer></v-spacer>
                                <v-btn color="red darken-4" text @click="dialog = false" >ไม่</v-btn>
                                <v-btn color="blue darken-1" text @click="Delete(building.id)">ใช่</v-btn>
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
window.moment = require('moment');
export default {
    middleware:'admin',
    name:'adminbuilding',
    data () {
      return {
        buildings:Object, 
        dialog:false,
      }
    },
    mounted(){
        this.getBuildingData();
        
    },
    methods:{
        getBuildingData(){
            axios.get('/api/buildings').then(response=>{
                this.buildings = response.data.building;
                //this.image = this.buildings.image1;
            })
        },
        Edit(id){
          this.$router.push('/admin/editbuilding/'+id)
        },
        Create(){
          this.$router.push('/admin/createbuilding/')
        },
        Delete(id){
          axios.delete('/api/buildings/'+id,{
            id:id
          })
          this.$router.go();
        }

        
    }
}
</script>
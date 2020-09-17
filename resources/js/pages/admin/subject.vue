<template>
    <v-app>
            <card>
            <v-btn class="ma-2" color="primary" @click="tocreate"><!--@click="confirmation()" reorder-->
                <v-icon left>add</v-icon>เพิ่ม
            </v-btn>

            <v-simple-table >
                <template v-slot:default>
                  <thead>
                    <tr>
                      <th class="text-left" >ลำดับ</th>
                      <th class="text-left">ชื่อวิชา</th>
                      <th class="text-left">วันที่</th>
                      <th class="text-left">เริ่มวันที่</th>
                      <th class="text-left">วันที่สิ้นสุด</th>
                      <th class="text-left">เวลาเริ่ม</th>
                      <th class="text-left">เวลาสิ้นสุด</th>
                      <th class="text-left">ห้องที่เรียน</th>
                      <th class="text-left">อาคารเรียน</th>
                      <th class="text-left"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="ob in object" :key="ob.id">
                        <td>{{ob.num}}</td>
                        <td>{{ob.subject}}</td>
                        <td>{{ob.day}}</td>
                        <td>{{ob.start}}</td>
                        <td>{{ob.end}}</td>
                        <td>{{ob.timestart}}</td>
                        <td>{{ob.timeend}}</td>
                        <td>{{ob.roomname}}</td>
                        <td>{{ob.buildingname}}</td>
                        <td>
                        <v-btn class="ma-2" color="warning" @click="editSubject(ob.id)"><!--@click="confirmation()" reorder-->
                            <v-icon left>edit </v-icon>แก้ไข
                        </v-btn>
                        <v-btn class="ma-2" color="error" @click="dialog = true"><!--@click="confirmation()" reorder-->
                            <v-icon left>delete </v-icon>ลบ
                        </v-btn>
                        </td>

                        <v-dialog v-model="dialog" max-width="300px">
                            <v-card >
                              <v-card-title>คุณต้องการลบวิชาที่สอนใช่หรือไม่</v-card-title>
                              <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="red darken-4" text @click="dialog = false" >ไม่</v-btn>
                                <v-btn color="blue darken-1" text @click="Delete(ob.id)">ใช่</v-btn>
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
    name:'subject',
    data(){
        return {
            subject:'',
            object:[],
            room:'',
            building:'',
            num:0,
            dialog:false,
        }
    },
    mounted(){
        this.getSubjectData();
    },
    methods:{
        getSubjectData(){
            this.getRoomdata();
            this.getBuildingdata();
            axios.get('/api/subjects').then(response => {
                this.subject = response.data;
                for (let index = 0; index < this.subject.length; index++) {
                    const start = moment(this.subject[index].start).format('DD-MM-YYYY');
                    const end = moment(this.subject[index].end).format('DD-MM-YYYY');
                    const timestart = moment(this.subject[index].timestart,'hhmm').format('HH:mm');
                    const timeend = moment(this.subject[index].timeend,'hhmm').format('HH:mm');

                    for (let roomloop = 0; roomloop < this.room.length; roomloop++) {
                        if(this.subject[index].room_id == this.room[roomloop].id){
                            for (let buildingloop = 0; buildingloop < this.building.length; buildingloop++) {
                                if(this.room[roomloop].building_id == this.building[buildingloop].id){
                                    this.num++;
                                    this.object.push({
                                    num:this.num,
                                    id:this.subject[index].id,
                                    subject:this.subject[index].subject,
                                    day:this.subject[index].day,
                                    start:start,
                                    end:end,
                                    timestart:timestart,
                                    timeend:timeend,
                                    roomname:this.room[roomloop].room_name,
                                    buildingname:this.building[buildingloop].building_name
                                    });
                                }
                            }
                        }
                    }
                }
            })
        },
        getRoomdata(){
            axios.get('/api/rooms/').then(response => {
                this.room = response.data.room;
            })
        },
        getBuildingdata(){
            axios.get('/api/buildings').then(response => {
                this.building = response.data.building;
            })
        },
        
        tocreate(){
            this.$router.push('/admin/createsubject');
        },

        editSubject(id){
            this.$router.push('/admin/editsubject/'+id);
        },

         Delete(id){
          axios.delete('/api/subjects/'+id,{
            id:id
          })
          this.$router.go();
        },

        
    }
}
</script>
<template>
    <v-app>
        <v-simple-table>
    <template v-slot:default>
      <thead>
        <tr>
          <th class="text-left">รายการที่</th>
          <th class="text-left">อาคาร</th>
          <th class="text-left">ชื่อห้อง</th>
          <th class="text-left">วันที่</th>
          <th class="text-left">เวลาเริ่ม</th>
          <th class="text-left">เวลาสิ้นสุด</th>
          <th class="text-left">สถานะ</th>
          <th class="text-left"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(event,index) in filteredData" :key="index">
          <td>{{event.id}}</td>
          <td>{{event.buildingname}}</td>
          <td>{{event.roomname}}</td>
          <td>{{event.day}}</td>
          <td>{{event.start}}</td>
          <td>{{event.end}}</td>
          <td>
            <!--<v-icon  color="red">clear</v-icon>-->
            <v-progress-circular v-if="event.status == 0"
            indeterminate
            color="primary"
            ></v-progress-circular>
            <v-icon v-else-if="event.status == 1" color="success">check</v-icon>
            <v-icon v-else color="red">clear</v-icon>
          </td>
          <td>
            <v-btn v-if="event.status == 0" depressed color="error" @click="dialog = true;getStatus(event.id)">ยกเลิก</v-btn>
          </td>
          </tr>
        </tbody>
      </template>
    </v-simple-table>

    <v-dialog v-model="dialog" persistent max-width="290">
      <v-card>
        <v-card-title>คุณต้องการยกเลิกใช่หรือไม่</v-card-title>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" text @click="dialog = false">ไม่</v-btn>
          <v-btn color="green darken-1" text @click="dialog = false;editStatus()">ใช่</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    </v-app>
</template>
<script>
import axios from 'axios';
window.moment = require('moment');
import { mapGetters } from 'vuex'
export default {
    middleware:'auth',
    name:'history',
    data(){
      return {
        booking:'',
        users:'',
        rooms:'',
        events:[],
        building:'',
        dialog:false,
        id:null,
        /*headers: [
        {
          text: 'id',
          align: 'start',
          sortable: false,
          value: 'id',
        },
        { text: 'อาคาร', value: 'buildingname' },
        { text: 'ห้อง', value: 'roomname' },
        { text: 'วัน', value: 'day' },
        { text: 'เวลาเริ่ม', value: 'start' },
        { text: 'เวลาสิ้นสุด', value: 'end', sortable: false },
        /*id:this.booking[index].id,
                    roomname:this.rooms[roomloop].room_name,
                    buildingname:this.building[buildingloop].building_name,
                    day:day,
                    start:start,
                    end:end,
                    userid:this.booking[index].user_id,
                    status:this.booking[index].status
      ],*/
      }
    },
    computed: {
    ...mapGetters({
        user: 'auth/user',
      }),
      filteredData(){
          return this.events.filter(item => item.userid === this.user.id)
      }
    },
    mounted(){
      this.getBookingdata();
    },
    methods:{
      getRoomData(){
        axios.get('/api/rooms').then(response =>{
          this.rooms = response.data.room;

        });

      },
      getUserdata(){
        axios.get('/api/users').then(response =>{
        this.users = response.data;
        })
      },
      getBuildingdata(){
        axios.get('/api/buildings').then(response =>{
        this.building = response.data.building;
        })
      },
      getBookingdata(){
        this.getRoomData();
        this.getUserdata();
        this.getBuildingdata();
        axios.get('/api/bookings').then(response =>{
          this.booking = response.data;
          for (let index = 0;index < this.booking.length;index++){
            const day = moment(this.booking[index].start).format('DD-MM-YYYY');
            const start = moment(this.booking[index].start, 'YYYYMMDDhhmm').format('HH:mm');
            const end = moment(this.booking[index].end, 'YYYYMMDDhhmm').format('HH:mm');

            for (let roomloop = 0;roomloop < this.rooms.length;roomloop++){
              if (this.booking[index].room_id == this.rooms[roomloop].id){
                /*this.events.push({
                  id:this.booking[index].id,
                  roomname:this.rooms[roomloop].room_name
                })*/
                for (let buildingloop =0;buildingloop < this.building.length;buildingloop++){
                  if (this.building[buildingloop].id == this.rooms[roomloop].building_id){
                  this.events.push({
                    id:this.booking[index].id,
                    roomname:this.rooms[roomloop].room_name,
                    buildingname:this.building[buildingloop].building_name,
                    day:day,
                    start:start,
                    end:end,
                    userid:this.booking[index].user_id,
                    status:this.booking[index].status
                  })
                  }
                }
              }
            }
            
          }
        this.events.sort((a,b)=> b.id-a.id);
       // this.tabbbb = this.tabb.sort((a, b) => b.update - a.update)
        }); 
      },
      getStatus(id){
        this.id = id;
      },
      editStatus(){
        axios.put('/api/bookings/'+this.id,{
          status:2
        });
        this.$router.go();
      }
    }
    
}
</script>
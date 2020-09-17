<template>
    <card :title="$t('ห้องที่ได้รับการอนุมัติล่าสุด')" style="text-align: center font-family: arial; width: 100%; margin: auto;">
      <!--<table class="table table-bordered">
        <tr >
          <th>รหัส</th>
          <th>ชื่อห้อง</th>
          <th>เวลาเริ่ม</th>
          <th>เวลาสิ้นสุด</th>
          <th>หมายเหตุ</th>
          <th>จำนวน</th>
          <th>Actions</th>
        </tr>
        <tr v-for="item in tabb" :key="item.id"
        >
          <td>{{item.id}}</td>
          <td>{{item.roomname}}</td>
          <td>{{item.start}}</td>
          <td>{{item.end}}</td>
          <td>{{item.describe}}</td>
          <td>{{item.count}}</td>
          <td><v-btn @click="gotoBookingdetail(item.id)">
            เข้าร่วม </v-btn>
           <v-dialog v-model="dialog" max-width="300px">
              <v-card>
                <v-card-text>ต้องการเข้าร่วมหรือไม่</v-card-text>
                <v-spacer></v-spacer>
              <v-card-actions>
                <v-btn color="green" @click="dialog = false" text>ใช่</v-btn>
                <v-btn color="red" @click="dialog = false" text>ไม่</v-btn>
              </v-card-actions>
              </v-card>
            </v-dialog>
            </td>
        </tr>
        
      </table>-->
      <v-btn @click="getBookingdata();getRoomData()">
        Show
      </v-btn>
          <v-simple-table>
        <template v-slot:default>
          <thead>
            <tr>
              <th>รหัส</th>
              <th>ชื่อห้อง</th>
              <th>เวลาเริ่ม</th>
              <th>เวลาสิ้นสุด</th>
              <th>หมายเหตุ</th>
              <th>จำนวน</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in items.slice(0, 5)" :key="item.id" >
              <td>{{item.id}}</td>
              <td>{{item.roomname}}</td>
              <td>{{item.start}}</td>
              <td>{{item.end}}</td>
              <td>{{item.describe}}</td>
              <td>{{item.member}}/{{item.count}}</td>
              <td><v-btn color="blue" text @click="gotoBookingdetail(item.id)">
            รายละเอียด </v-btn>
            </td>
            </tr>
          </tbody>
        </template>
      </v-simple-table>

      <v-btn absolute right text color="blue" @click="$router.push('/alloflist')">ดูทั้งหมด>></v-btn>
      <br/>
    </card>
</template>


<script>
import axios from 'axios';
window.moment = require('moment');
export default {

    data(){
        return {
            bookings:[],
            users:[],
            groupjoin:[],
            building:[],
            rooms:[],
            items:[
               /* {
                    id:1,
                    roomname:'aa',
                    start:'1',
                    end:'2',
                    describe:'1122',
                    member:11,
                    count:13,
                }*/
            ],
            member:[],
            membercount:0,
        }
    },

    computed:{
    
      getRoomdata(){
        
      },

       getBookingdata(){
         axios.get('/api/joingroups').then(response=>{
              this.groupjoin = response.data;
            });

         axios.get('api/rooms').then(response=>{
              this.rooms = response.data.room;
          })
        axios.get('api/bookings').then(response=>{
          this.bookings = response.data;
          this.getItem();
        })

        
      },
      
    },


    mounted:function (){
      //this.getBookingdata();
      //this.getRoomData();
      

      
    },

    methods:{
     gotoBookingdetail(id){
          this.$router.push('bookingdetail/'+id)
        },



    getItem(){
        
        this.bookings.forEach((book)=>{
          let start = moment(book.start).format('YYYY-MM-DD HH:mm');
          let end = moment(book.end).format('YYYY-MM-DD HH:mm');
          let update = new Date(book.updated_at)
          this.Member(book.id)
          let membercount = this.member.length;
            if (book.status == 1){
                this.rooms.forEach((room)=>{
                  if (book.room_id == room.id){
                    
                    this.items.push({
                    id:book.id,
                    start:start,
                    end:end,
                    count:book.count,
                    describe:book.describe,
                    roomname:room.room_name,
                    update:update,
                    member:membercount
                  })
                  }
                })
              
            }
          this.member = [];
        })
        this.items.sort((a, b) =>  b.update-a.update)
      },

     Member(bookingid){
      this.groupjoin.forEach((join)=>{
        if (join.booking_id == bookingid){
          this.member.push({
            book:join.booking_id,
            user:join.user_id
            })
        }
      })
     // debugger;
    },

    },



   
}
</script>
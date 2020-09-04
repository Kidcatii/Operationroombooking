<template>
  
  <div>
    
    <br/>
    <br/>
    <v-app>
    <!--<card class="text-center" :title="$t('home')">
      {{ $t('you_are_logged_in') }}
    </card>-->
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
            <tr v-for="item in tabbbb.slice(0, 5)" :key="item.id" >
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

    
    <br/>
    <card :title="$t('อาคารเรียน')" style="text-align: center font-family: arial; width: 100%; margin: auto;" v-if="this.user.role !== 'admin'">
    <v-container class="grey lighten-5">
      <v-row>
        <v-col
          cols="6"
          xs="15"
          sm="12"
          md="12"
          lg="4"
          xl="3"
          v-for="building in buildings"
          :key='building.id'
        >
          <v-card
            class="mx-auto"
            max-width="500"
          >
          <v-card-text class="text--primary green">
            <div class="headline text-center"><span class="white--text">{{building.building_name}}</span></div>
          </v-card-text>
            <v-img v-if="!building.image1"
              class="white--text align-end"
              height="200px"
              src="https://upload.wikimedia.org/wikipedia/commons/0/0a/No-image-available.png"
            >
            </v-img>
            <v-img v-else
              class="white--text align-end"
              height="200px"
              :src="building.image1"
            >
            </v-img>
            <v-card-text class="text--primary text-left">
              <div>เวลาเปิด : {{building.open}}</div>
              <br/>
              <div>เวลาปิด  : {{building.close}}</div>
            </v-card-text>
            <v-btn class="ma-2" tile outlined color="green" :to="'building/'+building.id">
              <v-icon left>mdi-pencil</v-icon> จอง
            </v-btn>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
    </card>
    </v-app>
    
  </div>
</template>

<script>
//v-if="this.user.role !== 'admin'"
import axios from 'axios';
window.moment = require('moment');
import { mapGetters } from 'vuex'
export default {
  middleware: 'auth',

  metaInfo () {
    return { title: this.$t('home') }
  },
data(){
        return {
            dialog:false,
            group:[],
            roomroom:[],
            rooms:[],
            users:[],
            buildings:Object,
            building:{
                id:'',
                building_name:'',
                open:'',
                close:'',
            },
            booking:[],
            bookings:[],
            events:[],
            groupjoin:[],
            grouplength:'',
            group:[],
            tabb:[],
            tabbb:[],
            tabbbb:[],
            id:1,
            num:0,
            member:[],
            buildingsid:null,
            count:[],
            membercount:0,
            
            cards: [
            { title: 'Pre-fab homes', src: 'https://cdn.vuetifyjs.com/images/cards/house.jpg', flex: 12 },
            { title: 'Favorite road trips', src: 'https://cdn.vuetifyjs.com/images/cards/road.jpg', flex: 6 },
            { title: 'Best airlines', src: 'https://cdn.vuetifyjs.com/images/cards/plane.jpg', flex: 6 },
      ],
        }
    },

    computed: {...mapGetters({
        user : 'auth/user',  
      })
      },

    mounted(){
        this.getBuildingID();
        this.getBuildingData();
        this.myprop();
        this.runCron();
        this.groupdata();
        this.CSS();
        //this.getcallgroup(bookingid);
        //this.getcallgroup(1062)
        //this.checkstatus();
    },
    methods:{

        gotoBookingdetail(id){
          this.$router.push('bookingdetail/'+id)
        },

        getBuildingData(){
            axios.get('api/bookings').then(response=>{
                this.booking = response.data;
            })
            
            axios.get('api/buildings').then(response=>{
                this.buildings = response.data.building;
            })

            axios.get('/api/rooms').then(response=>{
              this.rooms = response.data.room;
            })

            axios.get('/api/users').then(response=>{
              this.users = response.data;
            })

            axios.get('/api/joingroups').then(response=>{
              this.groupjoin = response.data;
            });     
            

        },

        getBuildingID(){
            axios.get('api/buildings/'+1).then(response=>{
                this.buildingsid = response.data;
            })
        },

        getBookingData(){
          axios.get('api/bookings').then(response=>{
            this.booking = response.data;
            let timenow = new Date();
            for(let index = 0; index < this.booking.length;index++ ) {
              
              if(this.booking[index].status == 1){
                console.log(this.booking[index].id);
                var bookingdate = new Date(this.booking[index].created_at+' UTC');
                
                bookingdate.setMinutes(bookingdate.getMinutes()+10);
                //console.log(timenow);
                //console.log(bookingdate);

                
                  if(timenow > bookingdate){
                    axios.put('/api/bookings/'+this.booking[index].id,{
                      status:2
                    });
                    console.log('Cancel');
                    
                  }

                  //if(timenow ===)
                
                //console.log(bookingdate);
                //console.log(bookingdatesss);
                //console.log(bookinghour);
                //console.log(bookingminute);
                //console.log(bookingsecond);
                
              }
              else {
                console.log('Checking Book status.');
              }
              }
            });
        },

        myprop(){
            this.buildingsid=this.id;
        },

        checkstatus(){
          this.getBookingData();
          /*if(this.booking.status = 0){
            
          }*/
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

        groupdata(){

            /*for (let i = 0; i < this.booking.length; i++) {
            axios.get('/api/rooms').then(response=>{
              this.roomroom =response.data.room;
              for (let j = 0; j < this.roomroom.length; j++) {
                if(this.booking[i].room_id == this.roomroom[j].id && this.booking[i].status == 1){
                  

                  let start = moment(this.booking[i].start,).format('YYYY-MM-DD HH:mm');
                  let end = moment(this.booking[i].end,).format('YYYY-MM-DD HH:mm');
                  this.Member(this.booking[i].id);
                  this.membercount = this.member.length;

                  this.tabb.push({
                    id:this.booking[i].id,
                    start:start,
                    end:end,
                    roomname:this.roomroom[j].room_name,
                    describe:this.booking[i].describe,
                    //person:this.num,
                    member:this.membercount,
                    count:this.booking[i].count,
                    password:this.booking[i].password,
                    update:new Date(this.booking[i].updated_at)
                  });

                  this.member = []*/

                  //this.member = []
                  
                  /*this.tabb.sort(function (a, b) {
                      this.tabbb = a.update.localeCompare(b.update);
                  });*/
                  //this.tabbbb = this.tabb.sort((a, b) => b.update - a.update)
                  //console.log(tabb);
                  //this.tabb.reverse();
                  //this.tabbbb = this.tabbb.slice(0,5)
               /* }
              }
            })
            */
          //}

          this.booking.forEach((book)=>{
            this.tabbbb.push({
                    id:this.book[i].id,

                    //person:this.num,
                    count:this.booking[i].count,
                    password:this.booking[i].password,
                  });
          })
          
        },


        totalData(){
          
        },

        runCron(){
          var CronJob = require('cron').CronJob;
          var job = new CronJob('* 10 * * * *', function() {
            axios.get('api/bookings').then(response=>{
            this.booking = response.data;
            let timenow = new Date();
            for(let index = 0; index < this.booking.length;index++ ) {
              
              if(this.booking[index].status === 0){
                //console.log(this.booking[index].id);
                var bookingdate = new Date(this.booking[index].created_at+' UTC');
                
                bookingdate.setMinutes(bookingdate.getMinutes()+10);
                //console.log(timenow);
                //console.log(bookingdate);

                
                  if(timenow > bookingdate){
                    axios.put('/api/bookings/'+this.booking[index].id,{
                      status:2
                    });
                    console.log('Cancel');
                    
                  }

                  //if(timenow ===)
                
                //console.log(bookingdate);
                //console.log(bookingdatesss);
                //console.log(bookinghour);
                //console.log(bookingminute);
                //console.log(bookingsecond);
                
              }
              else {
                console.log('Checking Book status.');
              }
              }
            });
            //this.getBookingData();
          }, null, true, 'America/Los_Angeles');
          job.start();
        },

        CSS(){
      var all = document.getElementsByClassName('v-application--wrap');
      for (var i = 0; i < all.length; i++) {
      all[i].style.minHeight = '10vh';
      }
    }
    }
  }
</script>

<template>
    <v-app>
        <card class="card" style="text-align: center font-family: arial; width: 50%; margin: auto;">
            <h3 class="text-center">  รายละเอียดการจองห้อง  </h3>

        <table class="table table-bordered center">
            <tr>
              <th style="width: 35%;text-align: center;">วัน/เดือน/ปี</th>
              <th style="width: 15%;text-align: center;">จำนวน</th>
              <th style="width: 25%;text-align: center;">เวลาเริ่ม</th>
              <th style="width: 25%;text-align: center;">เวลาสิ้นสุด</th>
            </tr>
            <tr>
              <td style="text-align: center;">{{day}}</td>
              <td style="text-align: center;">{{member.length}} / {{count}} คน</td>
              <td style="text-align: center;">{{start}}</td>
              <td style="text-align: center;">{{end}}</td>
            </tr>
        </table>

            <h3 class="text-center">ข้อมูลห้องปฏิบัติ</h3>
            
            <div class="row">
              <div class="col-sm-6">ห้อง</div>
              <div class="col-sm-6">อาคาร</div>
            </div>
            <div class="row">
              <div class="col-sm-6"><input type="text" class="form-control" v-model="room" disabled></div>
              <div class="col-sm-6"><input type="text" class="form-control" v-model="building" disabled></div>
            </div>

            <br/>
            <br/>

            <div class="row">
              <div class="col-sm-6">ชื่อผู้จอง</div>
              <div class="col-sm-6">อีเมล</div>
            </div>
            <div class="row">
              <div class="col-sm-6"><input type="text" class="form-control disabled" v-model="name" disabled></div>
              <div class="col-sm-6"><input type="text" class="form-control disabled" v-model="email" disabled></div>
            </div>
            
            <br/>
            <br/>

            <div class="row">
              <div class="col-sm-3">หมายเหตุ</div>
            </div>
            <div class="row">
              <div class="col-sm-12"><textarea class="form-control" rows="5" v-model="describe" disabled></textarea></div>
            </div>
            
            <br/>
            <br/>
            <v-btn color="green" 
            absolute
            right
            @click='invitedialog = true'
            v-if="bookings.user_id == user.id && today < reserveday">
            เชิญ
            </v-btn>
            <br/>
            <br/>
            <table class="table table-bordered center">
                <tr>
                  <th style="width: 10%;">ลำดับ</th>
                  <th style="width: 45%;">ชื่อ-นาสกุล</th>
                  <th style="width: 45%;">สิทธิ์</th>
                </tr>
                <tr v-for="(mem,index) in member" :key="index">
                  <td>{{mem.number}}</td>
                  <td>{{mem.name}}</td>
                  <td>{{mem.role}}</td>
                </tr>
            </table>

            <v-dialog v-model="invitedialog" max-width="1000px">
                <v-card>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                  <v-autocomplete
              v-model="friends"
              :items="users"
              filled
              chips
              color="blue-grey lighten-2"
              label="Select"
              :item-text="item =>`${item.name} ${item.email}`"
              
              item-value="id"
              multiple
            >
              <template v-slot:selection="data">
                <v-chip
                  v-bind="data.attrs"
                  :input-value="data.selected"
                  close
                  @click="data.select"
                  @click:close="remove(data.item)"
                >
                  {{ data.item.email }}
                  <br/>
                  {{ data.item.name }}
                </v-chip>
              </template>
              <template v-slot:item="data">
                <template v-if="typeof data.item !== 'object'">
                  <v-list-item-content v-text="data.item"></v-list-item-content>
                </template>
                <template v-else>
                  <v-list-item-content>
                    <v-list-item-title v-html="data.item.name"></v-list-item-title>
                    <v-list-item-subtitle v-html="data.item.email"></v-list-item-subtitle>
                  </v-list-item-content>
                </template>
              </template>
            </v-autocomplete>

            

            <v-btn color="green" @click="invite();invitedialog=false;">เชิญ</v-btn>

                <br/>
                <br/>
                <br/>
                <br/>

              </v-card>
            </v-dialog>
            
         
            <v-btn 
              v-if="today < reserveday && user.role !== 'admin'"
              absolute
              left
              color="green"
              :disabled="checkJoined()"
              @click="dialog = true"
            > 
              <v-icon v-if= 'password'>lock</v-icon>
              {{joined}}
              <v-dialog v-model="dialog" max-width="300px">
                <v-card v-if = 'password'>
                    <br/>
                    <div class="a">{{passer}}</div>
                    <br/>
                    <v-text-field type="password" v-model="confirmpass">
                      <v-icon slot="prepend" >lock</v-icon>
                    </v-text-field>
                    <br/>
                    
                    
                    <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                    text
                    color="red" @click="dialog = false;passer = ''">
                    ยกเลิก</v-btn>
                    <v-btn
                    color="green" @click="JoinwithPass()">
                    ยืนยัน</v-btn>
                    
                    
                    </v-card-actions>
                    <br/>
                </v-card>
                <v-card v-else>
                <v-card-text>ต้องการเข้าร่วมหรือไม่</v-card-text>
                <v-spacer></v-spacer>
                <v-card-actions>
                  <v-btn color="green" @click="joinRoom();dialog = false;" text >ใช่</v-btn>
                  <v-btn color="red" @click="dialog = false" text>ไม่</v-btn>
                </v-card-actions>
              </v-card>

              </v-dialog>
            </v-btn>


            <v-btn style="font-family: arial;"
              absolute
              right
              color="red"
              @click="gotoHome()"
            >
              ตกลง
            </v-btn>
            <br/>
            <br/>

        </card>

  </v-app>
</template>

<script>
import axios from 'axios';
window.moment = require('moment');
import { mapGetters } from 'vuex'
export default {

  middleware: 'auth',

  data(){
    return {
      group:[],
      groupcall:[],
      member:[],
      users:[],
      bookings:[],
      day:'',
      start:'',
      end:'',
      time:'',
      dialog:false,
      invitedialog:false,
      friends:'',
      today:new Date(),
      reserveday:'',
      password:'',
      joined:'เข้าร่วม',
      confirmpass:'',
      passer:'',
      room:'',
      building:'',
      buildings:'',
      name:'',
      email:'',
      count:'',
      rooms:[],
      email:'',
      describe:'',
      nub:0,
      roomid:null,
    }
  },

  watch: {
    
  },
  

  computed:{...mapGetters({
        user : 'auth/user',  
      }),
      
      map(){
        
      },

      
  },

  mounted(){
    this.getcallgroup();
    //this.getData();
    this.getBooking();
    this.checkJoined();
    
    
  },

  methods:{
    getusers(){
      
    },

    getcallgroup(){
      axios.get('/api/rooms').then(response => {
            this.rooms = response.data.room;
      });

       axios.get('/api/buildings').then(response => {
            this.buildings = response.data.building;
      })

      axios.get('/api/users/').then(response=>{
          this.users = response.data;
          //console.log(this.user);
          axios.get('/api/joingroups/').then(response=>{
          this.groupcall = response.data;
          let group = this.groupcall;
          console.log(this.groupcall);

         for (let index = 0; index < this.users.length; index++) {

          for (let i = 0; i < this.groupcall.length; i++) {
              if(this.groupcall[i].booking_id == this.$route.params.id){

              if(this.groupcall[i].user_id == this.users[index].id){
                console.log('1')
                this.nub++;
                  if (this.users[index].id == this.bookings.user_id){
                      this.member.push({
                      id:this.users[index].id,
                      number:this.nub,
                      name:this.users[index].name,
                      role:'ผู้จองห้องปฏิบัติการ'
                    })
                  }
                  else {
                    this.member.push({
                      id:this.users[index].id,
                      number:this.nub,
                      name:this.users[index].name,
                      role:'ผู้ร่วมใช้งาน'
                    })
                  }
                
                
                //count++;
              }
            }
        }
      }  
      });
      });
    },

    joinRoom(){

      let time = moment(new Date()).format('YYYY-MM-DD HH:mm');
      let bookingid = Number(this.$route.params.id);
      let userid = this.user.id;
      let username = this.user.name;
      axios.post('/api/joingroups',{
        booking_id:bookingid,
        user_id:userid,
        timein:time
      });   
      this.nub++;
       this.member.push({
          id:userid,
          number:this.nub,
          name:username,
          role:'สมาชิก'
        })
    },

    checkJoined(){
      /*this.member.forEach((mem)=>{
        if (this.user.id == mem.id){
          this.joined = 'เข้าร่วมแล้ว';
          return true;
          //break;
        } 
        else {
          this.joined = 'เข้าร่วม';
          return false;
        } 
      });*/
      /*let userid = '';
      this.users.forEach((use)=>{
        if (this.user.id == use.id){
          userid = Number(use.id);
        }
      });

      console.log(userid)

      console.log(this.user.id);
      for (let i = 0; i < this.member.length; i++) {
        console.log(this.member);
        console.log('4');
        if (id == this.member[i].id){
          
          this.joined = 'เข้าร่วมแล้ว';
          console.log('5');
          return true;
          break;
        }
        else {

          console.log('6');
          this.joined = 'เข้าร่วม';
          return false;

        }
      }*/

      for (let index = 0; index < this.member.length; index++) {
        if(this.user.id == this.member[index].id){
          console.log('5')
          this.joined = 'เข้าร่วมแล้ว'
          return true;
          break;
        }
        else {
          if (this.member.length >= this.count){
            this.joined = 'เต็มแล้ว'
            return true;
          }
          else {
          console.log('6')
          }
        }
        //debugger
      }

      
    },
    

    getBooking(){
      axios.get('/api/bookings/'+this.$route.params.id).then(response=>{
        this.bookings = response.data;
        this.password = this.bookings.password;
        let day = moment(this.bookings.start).format('DD-MM-YYYY');
        let start = moment(this.bookings.start).format('HH:mm');
        let end = moment(this.bookings.end).format('HH:mm');
        let hour = moment(this.bookings.end).format('HH:mm');
        //hour.setHours(hour.getHours()-this.bookings.start.getHours());
        this.reserveday = new Date(this.bookings.start);
        this.day = day;
        this.start = start;
        this.end = end;
        this.time = hour;
        this.count = this.bookings.count;
        this.describe = this.bookings.describe;
        this.rooms.forEach((room)=>{

          if (this.bookings.room_id == room.id){
              this.roomid = room.building_id
              this.room = room.room_name
            }

          this.buildings.forEach((building)=>{
            if (this.roomid == building.id){
              this.building = building.building_name
            }

          });
        });

        this.users.forEach((user)=>{
          if (this.bookings.user_id == user.id){
            this.name = user.name;
            this.email = user.email;
          }
        });
        
      });
     
    },

    gotoHome(){
      this.$router.push("/home");
    },

    remove (item) {
        const index = this.friends.indexOf(item.email)
        if (index >= 0) this.friends.splice(index, 1)
      },

    invite(){
      let bookingid = Number(this.$route.params.id);
      let time = moment(new Date()).format('YYYY-MM-DD HH:mm');
      for (let index = 0; index < this.users.length; index++) {
        for(let i = 0; i < this.friends.length; i++)
        if ( this.users[index].id == this.friends[i]){
           this.member.push({
              id:this.users[index].id,
              name:this.users[index].name
            })
        }
      }

      for (let i = 0;i < this.friends.length;i++){
        console.log('2')
        axios.post('/api/joingroups',{
        booking_id:bookingid,
        user_id:this.friends[i],
        timein:time
      });  
      }
      
    },
    

    JoinwithPass(){
      if (this.confirmpass !== this.password) {
        this.passer = 'รหัสไม่ถูกต้อง'
      }
      else {
        let bookingid = Number(this.$route.params.id);
        let time = moment(new Date()).format('YYYY-MM-DD HH:mm');
        axios.post('/api/joingroups',{
        booking_id:bookingid,
        user_id:this.user.id,
        timein:time
      });
        this.dialog = false;
      }
    },

    
  }
}
</script>

<style>
div.a {
  text-align: center;
}
</style>

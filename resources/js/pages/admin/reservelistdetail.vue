<template>
<v-app>
    <div class="container">
        <card :title="$t('รายละเอียดการจอง')" >
            <form >
                <form class="form-inline">
                    <label class="col-md-1 col-form-label text-md-right">{{ $t('รหัสการจอง') }}</label>
                    <div class="form-group mb-2">
                        <input type="text" style="width: 500px; height: 35px;" class="form-control " aria-describedby="emailHelp" v-model="bookingid" disabled><br/>
                    </div>
                </form>

                <form class="form-inline">
                    <label class="col-md-1 col-form-label text-md-right">{{ $t('ชื่อผู้จอง') }}</label>
                    <div class="form-group mb-2">
                        <input type="text" style="width: 500px; height: 35px;" class="form-control " aria-describedby="emailHelp" v-model="name" disabled><br/>
                    </div>
                </form>

                <form class="form-inline">
                    <label class="col-md-1 col-form-label text-md-right">{{ $t('วันที่') }}</label>
                    <div class="form-group mb-2">
                        <input type="text" style="width: 500px; height: 35px;" class="form-control " aria-describedby="emailHelp" v-model="day" disabled><br/>
                    </div>
                </form>

                <form class="form-inline">
                    <label class="col-md-1 col-form-label text-md-right">{{ $t('เริ่ม') }}</label>
                    <div class="form-group mb-2">
                        <input type="text" style="width: 500px; height: 35px;" class="form-control " aria-describedby="emailHelp" v-model="start" disabled><br/>
                    </div>
                </form>

                <form class="form-inline">
                    <label class="col-md-1 col-form-label text-md-right">{{ $t('สิ้นสุด') }}</label>
                    <div class="form-group mb-2">
                        <input type="text" style="width: 500px; height: 35px;" class="form-control " aria-describedby="emailHelp" v-model="end" disabled><br/>
                    </div>
                </form>

                <form class="form-inline">
                    <label class="col-md-1 col-form-label text-md-right">{{ $t('คำอธิบาย') }}</label>
                    <div class="form-group mb-2">
                        <textarea class="form-control" style="width: 800px; height: 200px;" aria-label="With textarea" v-model="describe" disabled></textarea>
                    </div>
                </form>


                <v-btn class="ma-2" tile outlined color="green" @click="confirm"><!--@click="confirmation()" -->
            <v-icon left>check_circle </v-icon>ยืนยัน
            <v-dialog v-model="dialog" max-width="300px">
                <v-card >
                  <v-card-title>แน่ใจใช่หรือไม่</v-card-title>
                  <v-card-actions >
                    <v-spacer></v-spacer>
                    <v-btn color="red darken-4" text @click="close" >ไม่</v-btn>
                    <v-btn color="blue darken-1" text @click="confirmation(bookingid)">ใช่</v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
          </v-btn>
                <v-btn class="ma-2"  tile outlined color="red" @click="cancel"><!--@click="confirmation()" reorder-->
                    <v-icon left>delete</v-icon>ยกเลิก
                    <v-dialog v-model="canceldialog" max-width="300px">
                <v-card >
                  <v-card-title>แน่ใจใช่หรือไม่</v-card-title>
                  <v-card-actions >
                    <v-spacer></v-spacer>
                    <v-btn color="red darken-4" text @click="cancelclose" >ไม่</v-btn>
                    <v-btn color="blue darken-1" text @click="confirmcancel(bookingid)">ใช่</v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
                </v-btn>
            </form>
        </card>
    </div>
</v-app>
</template>

<script>
import axios from 'axios';
window.moment = require('moment');
export default {
    middleware:'admin',
    name:'reservelistdetail',
    data(){
        return {
            b:'asdas',
            bookings:'',
            bookinglist:[],
            rooms:'',
            users:'',
            bookingid:'',
            name:'',
            day:'',
            start:'',
            end:'',
            describe:'',
            dialog:false,
            bookingmail:'',
            canceldialog:false,
        }
    },
    mounted(){
        this.getBookingdata()
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
    getBookingdata(){
        this.getRoomData();
        this.getUserdata();
        axios.get('/api/bookings/'+this.$route.params.id).then(response =>{
            this.bookings = response.data;
            const formatdatecrt = moment(this.bookings.created_at,).format('DD-MM-YYYY');
            const formatdate = moment(this.bookings.start,).format('DD-MM-YYYY');
            const formatstart = moment(this.bookings.start,).format('HH:mm');
            const formatend = moment(this.bookings.end,).format('HH:mm');
            console.log(formatdatecrt);
            console.log(formatdate);
            console.log(formatstart);
            console.log(formatend);
              for(let roomsloop = 0; roomsloop < this.rooms.length;roomsloop++){
                  if (this.rooms[roomsloop].id == this.bookings.room_id){
                      for(let usersloop = 0; usersloop < this.users.length;usersloop++){
                    
                        if (this.users[usersloop].id == this.bookings.user_id){

                            this.bookingid=this.bookings.id;
                            this.name= this.users[usersloop].name;
                            this.day=formatdate;
                            this.start=formatstart;
                            this.end=formatend;
                            this.describe=this.bookings.describe;
                            this.bookinglist.push({
                            id:this.bookings.id,
                            day:formatdate,
                            start:formatstart,
                            end:formatend,
                            room:this.rooms[roomsloop].room_name,
                            username:this.users[usersloop].name,
                            userid:this.bookings.user_id,
                            describe:this.bookings.describe,
                            create:formatdatecrt,
                        })
                      }
                    }
                  }
                  }
        });
      },

      confirm(){
          this.dialog = true;
      },
      close(){
          this.dialog = false;
      },
      confirmation (id){
        axios.put('/api/bookings/'+id,{
          status:1
        });
        axios.get('/api/bookings/'+id).then(response =>{
          this.bookingmail = response.data;
          this.sendmail(this.bookingmail.id);
        });
        this.$router.push('/admin/reservelist');
        },
        
        cancel(){
            this.canceldialog=true;
        },

         cancelclose(){
            this.canceldialog=false;
        },
        
        confirmcancel(id){
            axios.put('/api/bookings/'+id,{
            status:2
            });
            this.$router.push('/admin/reservelist');
        },
    
        sendmail(id,room){
          axios.get('/api/send-mail/'+id);
        },

    }
}
</script>
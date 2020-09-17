<template>
        <v-app>
          <v-data-table
          :headers="headers"
          :items="events"
          :items-per-page="10"
          class="elevation-1"
        >
          <template v-slot:top>
            <v-toolbar flat >
              <v-toolbar-title class="headline font-weight-black">รายการการจอง</v-toolbar-title>
              <v-spacer></v-spacer>

              <v-dialog v-model="dialog" max-width="300px">
                <v-card >
                  <v-card-title>อนุมัติการใช้ห้องปฏิบัติการ</v-card-title>
                  <v-card-actions >
                    <v-spacer></v-spacer>
                    <v-btn color="red darken-4" text @click="close" >ไม่</v-btn>
                    <v-btn color="blue darken-1" text @click="reserveconfirmationagain(confirm)">ใช่</v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>

              <v-dialog v-model="canceldialog" max-width="300px">
                <v-card >
                  <v-card-title>ไม่อนุมัติการใช้ห้องปฏิบัติการ</v-card-title>
                  <v-card-actions >
                    <v-spacer></v-spacer>
                    <v-btn color="red darken-4" text @click="cancelclose" >ไม่</v-btn>
                    <v-btn color="blue darken-1" text @click="cancelagain(confirm)">ใช่</v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-toolbar>
          </template>
          <template v-slot:item.action="{item}" >
                <v-btn class="ma-2" tile outlined color="primary" :to="'/admin/reservelistdetail/'+ item.id" >
                  <v-icon left>reorder </v-icon>รายละเอียด
                </v-btn>
                <v-btn class="ma-2" tile outlined color="green"  @click="confirmation(item)">
                  <v-icon left>check_circle </v-icon>ยืนยัน
                </v-btn>
                <v-btn class="ma-2" tile outlined color="red" @click="cancel(item)">
                  <v-icon left>delete</v-icon>ยกเลิก
                </v-btn>
          </template>
        </v-data-table>
        </v-app>
</template>

<!--<template>
<v-app >--> <!--v-if="user.name == 'admin'"-->
 <!-- <card>
  <v-simple-table>
    <template v-slot:default>
      <thead>
        <tr>
          <th class="text-left">รหัส</th>
          <th class="text-left">ชื่อห้อง</th>
          <th class="text-left">วันที่จอง</th>
          <th class="text-left">เริ่ม</th>
          <th class="text-left">สิ้นสุด</th>
          <th class="text-left">ชื่อผู้จอง</th>
          <th class="text-left">วันที่ทำรายการ</th>
          <th class="text-left"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(event,index) in events" :key="index">
          <td>{{ event.id }}</td>
          <td>{{ event.room}}</td>
          <td>{{ event.day }}</td>
          <td>{{ event.start }}</td>
          <td>{{ event.end }}</td>
          <td>{{ event.username }}</td>
          <td>{{ event.create }}</td>

          
          <td>
          <v-btn class="ma-2" tile outlined color="blue"  :to="'/admin/reservelistdetail/'+ event.id"><!--@click="confirmation()" reorder-->
            <!--<v-icon left>reorder </v-icon>รายละเอียด
          </v-btn>
          <v-btn class="ma-2" tile outlined color="green"  @click="confirmation(event.id)"><!--@click="confirmation()" -->
            <!--<v-icon left>check_circle </v-icon>ยืนยัน
            <v-dialog v-model="dialog" max-width="300px">
                <v-card >
                  <v-card-title>แน่ใจใช่หรือไม่</v-card-title>
                  <v-card-actions >
                    <v-spacer></v-spacer>
                    <v-btn color="red darken-4" text @click="close" >ไม่</v-btn>
                    <v-btn color="blue darken-1" text @click="reserveconfirmationagain(confirm)">ใช่</v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
          </v-btn>
          <v-btn class="ma-2" tile outlined color="red" @click="sendmail(event.userid)">
            <v-icon left>delete</v-icon>ลบ
          </v-btn></td>

          
        </tr>
      </tbody>
    </template>
  </v-simple-table>
  </card>
</v-app>-->
<!--<v-app v-else>
  <h1>ไม่มีข้อมูล</h1>
</v-app>-->
<!--</template>-->

<script>
import axios from 'axios';
window.moment = require('moment');
import { mapGetters } from 'vuex'
export default {
    middleware: 'admin',
    name:'reservelist',
    data () {
      return {
        confirm:'',
        bookingmail:'',
        dialog: false,
        rooms:'',
        users:'',
        bookings:'',
        events:[],
        expanded: [],
        singleExpand: false,
        canceldialog:false,
        headers: [
          { text: 'รหัส', 
          sortable: true,
          value: 'id' },
          {
            text: 'ชื่อห้อง',
            align: 'left',
            sortable: false,
            value: 'room',
          },
          { text: 'วันที่ต้องการจอง', 
            value: 'day' 
          },
          { text: 'เวลาเริ่ม', 
            sortable: false,
            value: 'start' 
          },
          { text: 'เวลาสิ้นสุด',
            sortable: false,
            value: 'end' 
          },
          { text: 'ชื่อผู้จอง',
            sortable: false,
            value: 'username' 
           },
          { text: 'วันที่จอง', 
          sortable: false,
          value: 'create' 
          },
          { text: '', value: 'action', sortable: false },
        ],
      }
    },
    editedIndex: -1,
      editedItem: {
        id: '',
        room: '',
        day: '',
        start: '',
        end: '',
        username:'',
        create:''
      },
    
    computed:{

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
      getBookingdata(){
        this.getRoomData();
        this.getUserdata();
        axios.get('/api/bookings').then(response =>{
          this.bookings = response.data;
          for (let index = 0; index < this.bookings.length; index++) {
            if (this.bookings[index].status == 0){
                    
                    
              for(let roomsloop = 0; roomsloop < this.rooms.length;roomsloop++){
                  if (this.rooms[roomsloop].id == this.bookings[index].room_id){

                      const formatdatecrt = moment(this.bookings[index].created_at,).format('DD-MM-YYYY');
                      const formatdate = moment(this.bookings[index].start,).format('DD-MM-YYYY');
                      const formatstart = moment(this.bookings[index].start,).format('HH:mm');
                      const formatend = moment(this.bookings[index].end,).format('HH:mm');
                      

                      for(let usersloop = 0; usersloop < this.users.length;usersloop++){
                    
                        if (this.users[usersloop].id == this.bookings[index].user_id){
                        this.events.push({
                        id:this.bookings[index].id,
                        day:formatdate,
                        start:formatstart,
                        end:formatend,
                        room:this.rooms[roomsloop].room_name,
                        username:this.users[usersloop].name,
                        userid:this.bookings[index].user_id,
                        create:formatdatecrt,
                        })
                      }
                    }
                  }

                //this.events.push({
                //name:this.bookings[index].describe,
                //color: 'green'/*this.colors[this.rnd(0, this.colors.length - 1)]*/,
                //})
                  }
                }
              }
        });
          
          
         /* if (this.bookings[index].status == 0){
            this.events.push({
                name:this.bookings[index].describe,
                color: 'green',
                })*/   
      },
    confirmation (id) {
        //this.index = this.events.indexOf(item)
        this.dialog = true;
        this.confirm = id;
      },

    reserveconfirmationagain (id){
        const index = this.events.indexOf(id)
        let idd = parseInt(id.id);
        let userid = parseInt(id.userid);

        axios.put('/api/bookings/'+id.id,{
          status:1
        });
        axios.get('/api/bookings/'+id.id).then(response =>{
          this.bookingmail = response.data;
          this.sendmail(this.bookingmail.id);
        });
        let date = new Date();
        let datetime = moment(date).format('YYYY-MM-DD HH:mm')
        
        axios.post('/api/joingroups',{
          booking_id:idd,
          user_id:userid,
          timein:datetime
        });
        
        this.events.splice(index, 1)
        this.dialog = false;
        //debugger;
        
    },

    cancel (id) {
        //this.index = this.events.indexOf(item)
        this.canceldialog = true;
        this.confirm = id;
        /*const index = this.events.indexOf(id)
        confirm('Are you sure you want to delete this item?') && 
        axios.put('/api/bookings/'+id.id,{
          status:2
        }) &&
        this.events.splice(index, 1)*/
      },

    cancelagain (id){
        const index = this.events.indexOf(id)

        axios.put('/api/bookings/'+id.id,{
          status:2
        });
        /*axios.get('/api/bookings/'+id).then(response =>{
          this.bookingmail = response.data;
          this.sendmail(this.bookingmail.id);
          
        });*/
        this.events.splice(index, 1)
        axios.get('/api/cancel-mail/'+id.id);
        this.canceldialog = false;
        
    },

    cancelclose(){
      this.canceldialog = false;
    },

    editItem (item) {
        this.editedIndex = this.events.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },
    
    close () {
        this.dialog = false
      },

    sendmail(id){
      axios.get('/api/send-mail/'+id);
    },
    getDetail(){

    }
  }
}
</script>



<!--
<template>
  <div id="app">
    <GSTC :config="config" @state="onState" />
  </div>
</template>

<script>
import GSTC from "./components/GSTC.vue";
let subs = [];

export default {
  name: "app",
  components: {
    GSTC
  },
  data() {
    return {
      config: {
        height: 300,
        list: {
          rows: {
            "1": {
              id: "1",
              label: "Row 1"
            },
            "2": {
              id: "2",
              label: "Row 2"
            },
            "3": {
              id: "3",
              label: "Row 3"
            },
            "4": {
              id: "4",
              label: "Row 4"
            }
          },
          columns: {
            data: {
              id: {
                id: "id",
                data: "id",
                width: 50,
                header: {
                  content: "ID"
                }
              },
              label: {
                id: "label",
                data: "label",
                width: 200,
                header: {
                  content: "Label"
                }
              }
            }
          }
        },
        chart: {
          items: {
            "1": {
              id: "1",
              rowId: "1",
              label: "Item 1",
              time: {
                start: new Date().getTime(),
                end: new Date().getTime() + 10 * 60 * 60 * 1000
              }
            },
            "2": {
              id: "2",
              rowId: "2",
              label: "555",
              time: {
                start: new Date().getTime() + 4 * 24 * 60 * 60 * 1000,
                end: new Date().getTime() + 5 * 24 * 60 * 60 * 1000
              }
            },
            "3": {
              id: "3",
              rowId: "2",
              label: "Item 3",
              time: {
                start: new Date().getTime() + 6 * 24 * 60 * 60 * 1000,
                end: new Date().getTime() + 10 * 24 * 60 * 60 * 1000
              }
            },
            "4": {
              id: "4",
              rowId: "3",
              label: "Item 4",
              time: {
                start: new Date().getTime() + 10 * 24 * 60 * 60 * 1000,
                end: new Date().getTime() + 12 * 24 * 60 * 60 * 1000
              }
            },
            "5": {
              id: "5",
              rowId: "4",
              label: "Item 5",
              time: {
                start: new Date().getTime() + 12 * 24 * 60 * 60 * 1000,
                end: new Date().getTime() + 14 * 24 * 60 * 60 * 1000
              }
            }
          }
        }
      }
    };
  },
  methods: {
    onState(state) {
      this.state = state;
      subs.push(
        state.subscribe("config.chart.items.1", item => {
          console.log("item 1 changed", item);
        })
      );
      subs.push(
        state.subscribe("config.list.rows.1", row => {
          console.log("row 1 changed", row);
        })
      );
    }
  },
  mounted() {
    setTimeout(() => {
      const item1 = this.config.chart.items["1"];
      item1.label = "label changed dynamically";
      item1.time.end += 2 * 24 * 60 * 60 * 1000;
    }, 2000);
  },
  beforeDestroy() {
    subs.forEach(unsub => unsub());
  }
};
</script>

<style>
#app {
  font-family: "Avenir", Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>




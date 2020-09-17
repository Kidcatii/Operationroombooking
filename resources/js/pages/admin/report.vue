<template>
        <card class="card" style="text-align: center font-family: arial; width: 70%; margin: auto;">
            <div class="purple blue lighten-2">
                <h3 class="headline white--text text--accent-2" ><v-icon color="white" large>description</v-icon>Report รายงาน</h3>
            </div>
            <b-form-select v-model="selected" :options="options"></b-form-select>

            
            

            <card v-if="selected == 'a'" class="noline">
              <h3 class="headline black--text" >รายงานการใช้ห้อง</h3>
            <v-row align="center">
                <v-col class="d-flex" cols="3" sm="3">
                    <v-menu
                      ref="nowMenu"
                      v-model="nowMenu"
                      :close-on-click="nowMenu = false"
                      :close-on-content-click="false"
                      :nudge-right="40"
                      :return-value.sync="pick"
                      transition="scale-transition"
                      min-width="290px"
                      offset-y
                      color="green"
                      @click.stop
                    >
                      <template v-slot:activator="{ on }">
                        <v-text-field
                          v-model="pick"
                          class="mt-3 centered-input"
                          label="วันที่จอง"
                          prepend-icon="event"
                          dense
                          readonly
                          outlined
                          hide-details
                          v-on="on"
                          max-width="50"
                          color="green"
                        ></v-text-field>
                      </template>
                      <v-date-picker
                        v-model="pick"
                        no-title
                        scrollable
                        color="green"
                      >
                        <v-spacer></v-spacer>
                        <v-btn
                          text
                          color="green"
                          @click="nowMenu = false;pick=''"

                        >
                          Cancel
                        </v-btn>
                        <v-btn
                          text
                          color="green"
                          @click="$refs.nowMenu.save(pick)"
                        >
                          OK
                        </v-btn>
                      </v-date-picker>
                    </v-menu>
                </v-col>
            </v-row>
            <v-row align="center">
                <v-col class="d-flex" cols="12" sm="6">
                <v-select
                  class="selecter"
                  :items="buildingitem"
                  label="อาคาร"
                  filled
                  v-model="pickbuilding"
                  @click.stop
                >
                </v-select>
              </v-col>
              <v-col class="d-flex" cols="12" sm="6">
                <v-select
                    
                    class="selecter"
                    :items="filteredData"
                    label="ห้อง"
                    filled
                    v-model="pickroom"
                    :disabled="this.selectdisables()"
                    @click.stop
                >
                </v-select>
              </v-col>
            </v-row>
            <v-row align="center">
                <v-col class="d-flex" cols="12" sm="6">
                <v-select
                    class="selecter"
                    :items="status"
                    label="สถานะ"
                    filled
                    v-model="pickstatus"
                    @click.stop
                >
                </v-select>
              </v-col>
              <v-col class="d-flex" cols="12" sm="6">
                <v-spacer></v-spacer>
                <v-btn large color="success" @click="pickBookingdata(pick,pickbuilding,pickroom,pickstatus)">ยืนยัน</v-btn>
              </v-col>
            </v-row>

            <v-card >
                <v-card-title >

                    <h3 class="headline back--text"  v-if="date !== ''">วันที่ {{date}}</h3>
                    <br/>
                    <br/>
                    <h3 class="headline back--text"  v-if="buildingding !== ''">{{buildingding}}</h3>

                </v-card-title>
                <v-data-table
                  :headers="headers"
                  :items="bookingslist"
                  :items-per-page="5"
                  class="elevation-1 grey lighten-2"
                ></v-data-table>
                <br/>
                <v-btn id='cl' absolute right large color="blue-grey lighten-5" @click="pdfMake"><v-icon left large>picture_as_pdf</v-icon>Save PDF</v-btn>
                <br/>
                <br/>
                <br/>
            </v-card>
            

            <br/>
            <br/>
            <br/>
            </card>


            <card v-else-if="selected == 'b'" class="noline">
              รายงานห้องว่าง
              <BuildingReport/>
            </card>

            <card v-else-if="selected == 'c'" class="noline">
              รายงานสถิติ
              <Linechart/>
            </card>
        </card>
        

</template>

<script>
import axios from 'axios';
window.moment = require('moment');
import { mapGetters } from 'vuex'
import BuildingReport from './report/buildingreport.vue'
import Linechart from './report/linechart.vue'

import pdfMake from "pdfmake/build/pdfmake";
import pdfFonts from "pdfmake/build/vfs_fonts";
pdfMake.vfs = pdfFonts.pdfMake.vfs;

pdfMake.fonts = {
  THSarabunNew: {
    normal: 'THSarabunNew.ttf',
    bold: 'THSarabunNew-Bold.ttf',
    italics: 'THSarabunNew-Italic.ttf',
    bolditalics: 'THSarabunNew-BoldItalic.ttf'
  },
  Roboto: {
    normal: 'Roboto-Regular.ttf',
    bold: 'Roboto-Medium.ttf',
    italics: 'Roboto-Italic.ttf',
    bolditalics: 'Roboto-MediumItalic.ttf'
  }
}

export default {

  middleware: 'admin',

  components:{
    BuildingReport,
    Linechart
  },
  data(){
    return {
        picker:false,
        pick:'',
        nowMenu:true,
        status:[
           { 
               value:'',
               text:'ทั้งหมด'
            },
            {   value:'อนุมัติ',
                text:'อนุมัติ'},
            {   
                value:'ไม่อนุมัติ',
                text:'ไม่อนุมัติ'
            },
            {
                value:'กำลังดำเนินการ',
                text:'กำลังดำเนินการ'
            }  
        ],
        date:'',
        users:'',
        pickstatus:'',
        bookings:'',
        buildings:'',
        building:'',
        pickbuilding:'',
        buildingding:'',
        room:'',
        rooms:'',
        pickroom:'',
        buildingitem:[
            {
                value:'',
                text:'ทั้งหมด',
            }
        ],
        roomitem:[
            {
                value:'',
                text:'ทั้งหมด',
            }
        ],
        headers: [
          {
            text: 'รหัสการจอง',
            align: 'start',
            sortable: false,
            value: 'id',
          },
          { text: 'ชื่อห้อง', value: 'roomname' , sortable: false},
          { text: 'ชื่อผู้จอง', value: 'name' , sortable: false },
          { text: 'วันที่จอง', value: 'day' , sortable: false },
          { text: 'เวลาเริ่ม', value: 'start' , sortable: false },
          { text: 'เวลาสิ้นสุด', value: 'end' , sortable: false },
          { text: 'สถานะ', value: 'status' , sortable: false },
        ],
        bookingslist:[
            /*{
                id:'1',
                buildingname:'2',
                name:'3',
                day:'4',
                start:'5',
                end:'6',
                status:'7',
            }*/
        ],
        selected: 'a',
        options: [
          { value: 'a', text: 'รายงานการจองห้องปฏิบัติการ' },
          { value: 'b', text: 'รายงานห้องปฏิบัติการที่ว่าง' },
          { value: 'c', text: 'รายงานสถิติการจองห้องปฏิบัติการ'}
        ]
      }
    
  },

    computed:{
        filteredData(){
          return this.roomitem.filter(item => item.building === this.pickbuilding)
      },

        
    },

   mounted(){
        this.getDatabuidling();
        this.getRoomData();
        this.selectdisables();
        this.getBookingData();
        this.getUserData();

      /*const button = document.getElementById('cl');

        button.addEventListener('click', function(){
        	let doc = new jsPDF('p', 'pt');
        	let columns = [
        		{title: "ID", dataKey: "id"},
        		{title: "Name", dataKey: "name"},
        		{title: "Country", dataKey: "country"},
        	];
        	let rows = [
        		{"id": 1, "name": "Shaw", "country": "Tanzania"},
        		{"id": 2, "name": "Nelson", "country": "Kazakhstan"},
        		{"id": 3, "name": "Garcia", "country": "Madagascar"},
        	];
                   // Error ========================
        	doc.autoTable(columns, rows);
        	doc.save('teste.pdf');
        })*/

        
		    
	

    },
   
   methods:{
       selectdisables(){
            if (this.pickbuilding){
               // this.roomitem.splice(10, 1);
                
                return false;
                //this.getRoomData();
            }
            else {
                this.pickroom='';
                return true;
            }
        },

       getDatabuidling(){
            axios.get('/api/buildings').then(response =>{
                this.buildings = response.data.building;
                for (let index = 0; index < this.buildings.length; index++) {
                    this.buildingitem.push({
                        value:this.buildings[index].id,
                        text:this.buildings[index].building_name,
                    })
                }
            })
        },
        getRoomData(){
            axios.get('/api/rooms').then(response =>{
                this.rooms = response.data.room;
                for (let index = 0; index < this.rooms.length; index++) {
                        this.roomitem.push({
                            value:this.rooms[index].id,
                            text:this.rooms[index].room_name,
                            building:this.rooms[index].building_id
                        })
                }
            })
        },

        getBookingData(){
            axios.get('/api/bookings').then(response =>{
                this.bookings = response.data;
                
            })
        },

        getBookingData(){
            axios.get('/api/bookings').then(response =>{
                this.bookings = response.data;
                
            })
        },

        getUserData(){
          axios.get('/api/users').then(response =>{
                this.users = response.data;
                
            })
        },

        pickBookingdata(date,buildingid,roomid,status){
            while(this.bookingslist.length != 0){

                this.bookingslist.shift();
            }
            //console.log(date)
           // this.buildingding = this.pickbuilding;
            this.date = this.pick;
            this.bookings.forEach((booking) => {
              this.rooms.forEach((room) =>{
                this.buildings.forEach((building) =>{
                this.users.forEach((user) => {
              let dayformat = moment(booking.start).format('YYYY-MM-DD');
              let statusis = '';
                switch (booking.status) {
                  case 0:
                    statusis = 'กำลังดำเนินการ'
                    break;
                  
                  case 1:
                    statusis = 'อนุมัติ'
                    break;
                  
                  case 2:
                    statusis = 'ไม่อนุมัติ'
                    break;
                  
                  default:
                    break;
                }
              if(booking.room_id == room.id){
                  if (booking.user_id == user.id){
                    if(room.building_id == building.id){

              if (date == dayformat){
                if(building.id == buildingid){
                  if (booking.room_id == roomid){

                      if(statusis == status){
                        this.buildingding = building.building_name;
                        let sid = booking.id.toString();
                        let formatstart = moment(booking.start).format('HH:mm');
                        let formatend = moment(booking.end).format('HH:mm');
                        this.bookingslist.push({

                          id:sid,
                          roomname:room.room_name,
                          name:user.name,
                          day:dayformat,
                          start:formatstart,
                          end:formatend,
                          status:statusis,

                        });
                      }

                      else if (status == ''){
                        this.buildingding = building.building_name;
                        let sid = booking.id.toString();
                        let formatstart = moment(booking.start).format('HH:mm');
                        let formatend = moment(booking.end).format('HH:mm');
                        this.bookingslist.push({

                          id:sid,
                          roomname:room.room_name,
                          name:user.name,
                          day:dayformat,
                          start:formatstart,
                          end:formatend,
                          status:statusis,

                        });

                      }

                    
                 }
                 else if (roomid == ''){
                    
                    if(statusis == status){
                        this.buildingding = building.building_name;
                        let sid = booking.id.toString();
                        let formatstart = moment(booking.start).format('HH:mm');
                        let formatend = moment(booking.end).format('HH:mm');
                        this.bookingslist.push({

                          id:sid,
                          roomname:room.room_name,
                          name:user.name,
                          day:dayformat,
                          start:formatstart,
                          end:formatend,
                          status:statusis,

                        });
                      }

                      else if (status == ''){
                        this.buildingding = building.building_name;
                        let sid = booking.id.toString();
                        let formatstart = moment(booking.start).format('HH:mm');
                        let formatend = moment(booking.end).format('HH:mm');
                        this.bookingslist.push({

                          id:sid,
                          roomname:room.room_name,
                          name:user.name,
                          day:dayformat,
                          start:formatstart,
                          end:formatend,
                          status:statusis,

                        });

                      }
                 }
                 }

                 else if (buildingid == '') {
                   if (booking.room_id == roomid){
                     if(statusis == status){
                        let sid = booking.id.toString();
                        let formatstart = moment(booking.start).format('HH:mm');
                        let formatend = moment(booking.end).format('HH:mm');
                        this.bookingslist.push({

                          id:sid,
                          roomname:room.room_name,
                          name:user.name,
                          day:dayformat,
                          start:formatstart,
                          end:formatend,
                          status:statusis,

                        });
                      }

                      else if (status == ''){
                        let sid = booking.id.toString();
                        let formatstart = moment(booking.start).format('HH:mm');
                        let formatend = moment(booking.end).format('HH:mm');
                        this.bookingslist.push({

                          id:sid,
                          roomname:room.room_name,
                          name:user.name,
                          day:dayformat,
                          start:formatstart,
                          end:formatend,
                          status:statusis,

                        });

                      }
                   }
                   else if (roomid == ''){
                     if(statusis == status){
                        this.buildingding = building.name;
                        let sid = booking.id.toString();
                        let formatstart = moment(booking.start).format('HH:mm');
                        let formatend = moment(booking.end).format('HH:mm');
                        this.bookingslist.push({

                          id:sid,
                          roomname:room.room_name,
                          name:user.name,
                          day:dayformat,
                          start:formatstart,
                          end:formatend,
                          status:statusis,

                        });
                      }

                      else if (status == ''){
                        this.buildingding = building.name;
                        let sid = booking.id.toString();
                        let formatstart = moment(booking.start).format('HH:mm');
                        let formatend = moment(booking.end).format('HH:mm');
                        this.bookingslist.push({

                          id:sid,
                          roomname:room.room_name,
                          name:user.name,
                          day:dayformat,
                          start:formatstart,
                          end:formatend,
                          status:statusis,

                        });
                      }
                   }
                 }

              }



              else if (date == ''){
                  if(building.id == buildingid){
                  if (booking.room_id == roomid){

                      if(statusis == status){
                        this.buildingding = building.building_name;
                        let sid = booking.id.toString();
                        let formatstart = moment(booking.start).format('HH:mm');
                        let formatend = moment(booking.end).format('HH:mm');
                        this.bookingslist.push({

                          id:sid,
                          roomname:room.room_name,
                          name:user.name,
                          day:dayformat,
                          start:formatstart,
                          end:formatend,
                          status:statusis,

                        });
                      }

                      else if (status == ''){
                        this.buildingding = building.building_name;
                        let sid = booking.id.toString();
                        let formatstart = moment(booking.start).format('HH:mm');
                        let formatend = moment(booking.end).format('HH:mm');
                        this.bookingslist.push({

                          id:sid,
                          roomname:room.room_name,
                          name:user.name,
                          day:dayformat,
                          start:formatstart,
                          end:formatend,
                          status:statusis,

                        });

                      }

                    
                 }
                 else if (roomid == ''){
                    
                    if(statusis == status){
                        this.buildingding = building.building_name;
                        let sid = booking.id.toString();
                        let formatstart = moment(booking.start).format('HH:mm');
                        let formatend = moment(booking.end).format('HH:mm');
                        this.bookingslist.push({

                          id:sid,
                          roomname:room.room_name,
                          name:user.name,
                          day:dayformat,
                          start:formatstart,
                          end:formatend,
                          status:statusis,

                        });
                      }

                      else if (status == ''){
                        this.buildingding = building.building_name;
                        let sid = booking.id.toString();
                        let formatstart = moment(booking.start).format('HH:mm');
                        let formatend = moment(booking.end).format('HH:mm');
                        this.bookingslist.push({

                          id:sid,
                          roomname:room.room_name,
                          name:user.name,
                          day:dayformat,
                          start:formatstart,
                          end:formatend,
                          status:statusis,

                        });

                      }
                 }
                 }

                 else if (buildingid == '') {
                   if (booking.room_id == roomid){
                     if(statusis == status){
                        let sid = booking.id.toString();
                        let formatstart = moment(booking.start).format('HH:mm');
                        let formatend = moment(booking.end).format('HH:mm');
                        this.bookingslist.push({

                          id:sid,
                          roomname:room.room_name,
                          name:user.name,
                          day:dayformat,
                          start:formatstart,
                          end:formatend,
                          status:statusis,

                        });
                      }

                      else if (status == ''){
                        let sid = booking.id.toString();
                        let formatstart = moment(booking.start).format('HH:mm');
                        let formatend = moment(booking.end).format('HH:mm');
                        this.bookingslist.push({

                          id:sid,
                          roomname:room.room_name,
                          name:user.name,
                          day:dayformat,
                          start:formatstart,
                          end:formatend,
                          status:statusis,

                        });

                      }
                   }
                   else if (roomid == ''){
                     if(statusis == status){
                        this.buildingding = building.name;
                        let sid = booking.id.toString();
                        let formatstart = moment(booking.start).format('HH:mm');
                        let formatend = moment(booking.end).format('HH:mm');
                        this.bookingslist.push({

                          id:sid,
                          roomname:room.room_name,
                          name:user.name,
                          day:dayformat,
                          start:formatstart,
                          end:formatend,
                          status:statusis,

                        });
                      }

                      else if (status == ''){
                        this.buildingding = building.name;
                        let sid = booking.id.toString();
                        let formatstart = moment(booking.start).format('HH:mm');
                        let formatend = moment(booking.end).format('HH:mm');
                        this.bookingslist.push({

                          id:sid,
                          roomname:room.room_name,
                          name:user.name,
                          day:dayformat,
                          start:formatstart,
                          end:formatend,
                          status:statusis,

                        });
                      }
                   }
                 }

                /*else {
                  this.bookingslist.push({
                          id:booking.id,
                          roomname:room.room_name,
                          name:user.name,
                          day:dayformat,
                          start:booking.start,
                          end:booking.end,
                          status:statusis,
                          });
                }*/
              }
              }
              }
              }
            });
            });
            });
            });
            //debugger;
            //console.log(bookings);



            



            //console.log(roomid)
            /*axios.get('/api/rooms').then(response =>{
                this.rooms = response.data.room;
              
            for (let index = 0; index < this.bookings.length; index++) {
                for (let i = 0; index < this.rooms.length; i++) {
                  console.log(this.rooms[i].id);
                  //console.log(this.bookings[index].room_id)
                  if(this.bookings[index].room_id == this.rooms[i].id){
                let dayformat = moment(this.bookings[index].start).format('YYYY-MM-DD');
                //this.formatstart = moment(result,'YYYYMMDDhmmss').format('YYYY-MM-DD HH:mm:ss')
                console.log(dayformat)
                    if (dayformat == date){

                        this.bookingslist.push({
                            
                            id:this.bookings[index].id,
                            roomname:this.room[i].room_name,
                            name:this.bookings[index].user_id,
                            day:dayformat,
                            start:this.bookings[index].start,
                            end:this.bookings[index].end,
                            status:this.bookings[index].status,

                        }); 
                      }

                    else if (date == '',roomid == ''){
                        
                        this.bookingslist.push({
                            
                            id:this.bookings[index].id,
                            roomname:this.room[i].room_name,
                            name:this.bookings[index].user_id,
                            day:dayformat,
                            start:this.bookings[index].start,
                            end:this.bookings[index].end,
                            status:this.bookings[index].status,

                        });
                    }
                
               }
              }
               
            }

            });*/
             //debugger;
        },

          /*savePdf(){
              var pdf = new jsPDF('p', 'pt', 'a4');
                  pdf.html(document.getElementById('html'), {
                  callback: function (pdf) {
                  pdf.save('a4.pdf');
		        	}
		        });
          }*/

        pdfMake(){
          var rows = [];
          rows.push(['รหัสการจอง', 'ชื่อห้อง', 'ชื่อผู้จอง','วันที่จอง','เวลาเริ่ม','เวลาสิ้นสุด','สถานะ']);

          for(let i = 0;i < this.bookingslist.length;i++) {
              rows.push([this.bookingslist[i].id,this.bookingslist[i].roomname,this.bookingslist[i].name, this.bookingslist[i].day, this.bookingslist[i].start,this.bookingslist[i].end,this.bookingslist[i].status]);
          }
          var docDefinition = {
          
          content: [
            
            { text: 'รายงานการจอง', fontSize: 15 ,},
            {table: {
                body: rows
            }}
            //this.table(this.bookingslist, ['รหัสการจอง', 'ชื่อห้อง', 'ชื่อผู้จอง','วันที่จอง','เวลาเริ่ม','เวลาสิ้นสุด','สถานะ'])
            /*table: {
              
				body: [
					['รหัสการจอง', 'ชื่อห้อง', 'ชื่อผู้จอง','วันที่จอง','เวลาเริ่ม','เวลาสิ้นสุด','สถานะ'],
					['One value goes here', 'Another one here', 'OK?','2','2','6','7']
				]
			}},*/
            
            
          ],
          defaultStyle:{
            font: 'THSarabunNew'
          }
        };
        pdfMake.createPdf(docDefinition).open()
        },

        buildTableBody(data, columns) {
            var body = [];
        
            body.push(columns);
        
            data.forEach(function(row) {
                var dataRow = [];
        
                columns.forEach(function(column) {
                    dataRow.push(row[column].toString());
                })
        
                body.push(dataRow);
            });
        
            return body;
        },
        
        table(data, columns) {
            return {
                table: {
                    headerRows: 1,
                    body: this.buildTableBody(data, columns)
                }
            };
        },
        
    },
    
}
</script>

<style>
div.a {
  text-align: center;
}
/*.centered-input input {
  text-align: center
}
a{
    text-align: center
}*/
.selecter {
    width: 100%;
    justify-content: center;
}
.noline{
  border:none;
}
</style>


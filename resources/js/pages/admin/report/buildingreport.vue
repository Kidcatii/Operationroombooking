<template>
    <div>
        <h3 v-if="err">{{err}}</h3>
        <b-container class="bv-example-row">
        <b-row>
            <b-col md ='6' lg='6' xl="4">
                <div>
                   <b-form-datepicker id="example-datepicker" locale="th" v-model="pickdate"></b-form-datepicker>
                 </div>
            </b-col>
        </b-row>
<!--id="example-datepicker" locale="th" v-model="pickdate"-->
            <b-row>
                
            <b-col md ='4' lg='3' xl="3">
               ตั้งแต่เวลา : <b-form-select v-model="picktimestart" :options="timechoose"></b-form-select>
            </b-col>
            <b-col md ='4' lg='3' xl="3">
               ถึง : <b-form-select v-model="picktimeend" :options="timechoose"></b-form-select>
            </b-col>
            <b-col md ='4' lg='3' xl="2">
                สถานะ : <b-form-select v-model="pickstatus" :options="statusitem"></b-form-select>
            </b-col>

            </b-row>

            <b-row>
            <b-col>
                อาคาร : <b-form-select :options="buildingitem" v-model="pickbuilding" ></b-form-select>
            </b-col>
            <b-col>
                ห้อง : <b-form-select :options="filteredData" v-model="pickroom" :disabled="selectdisables()"></b-form-select>
            </b-col>

            
            
            </b-row>

            <b-row>
              <b-col cols="auto" class="mr-auto"></b-col>
              <b-col cols="auto" >
                <v-btn large color="success" @click="addDatatable()">ยืนยัน</v-btn>
              </b-col>
            </b-row>
            
             <v-card>
                    <h3 class="hecdline back--text">วันที่ {{pickdate}}</h3>
                    <br/>
                    <h3 class="headline back--text" >ช่วงเวลา: {{picktimestart}} - {{picktimeend}}</h3>
                    <br/>
                    <br/>
                <v-data-table
                  :headers="headers"
                  :items="tablelist"
                  :items-per-page="5"
                  class="elevation-1 grey lighten-2"
                ></v-data-table>
                <br/>
                <v-btn id='cl' absolute right large color="blue-grey lighten-5" @click="createPDF()"><v-icon left large>picture_as_pdf</v-icon>Save PDF</v-btn>
                <br/>
                <br/>
                <br/>
            </v-card>

        </b-container>
    </div>
</template>
<script>
import axios from 'axios';
window.moment = require('moment');
import { mapGetters } from 'vuex'

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
    data(){
        return {
            pickdate: moment(new Date()).format('YYYY-MM-DD'),
            pickdateformat: '',
            headers: [
                {
                  text: 'ชื่ออาคาร',
                  align: 'start',
                  sortable: false,
                  value: 'building',
                },
                { text: 'ห้อง', value: 'room' , sortable: false},
                { text: 'สถานะ', value: 'status' , sortable: false },
            ],
            bookings:[],
            buildings:[],
            rooms:[],
            subject:[],
            event:[],
            buildingitem:[
                {
                    value:'',
                    text:'ทั้งหมด',
                }
            ],
            pickbuilding:'',
            roomitem:[
                {
                    value:'',
                    text:'ทั้งหมด',
                }
            ],
            pickroom:'',
            timechoose:['08:00',
                        '09:00',
                        '10:00',
                        '11:00',
                        '12:00',
                        '13:00',
                        '14:00',
                        '15:00',
                        '16:00',
                        '17:00',],
            picktimestart:'08:00',
            picktimeend:'08:00',
            err:'',
            datetimestart:'',
            datetimeend:'',
            list:[
               /* {
                    building:'wq',
                    room:'eqw',
                    status:'qwe',
                }*/
            ],
            tablelist:[],
            total:'',
            check:[],
            status:'',
            pickstatus:'',
            statusitem:[
                    {
                      value:'',
                      text:'ทั้งหมด'  
                    },
                    
                        'ว่าง'  
                    ,

                      'ไม่ว่าง'  
                    ,
                ]
        }
    },

    computed:{
        filteredData(){
          return this.roomitem.filter(item => item.building === this.pickbuilding)
        },
    },

    mounted(){
        this.getData();

    },


    methods:{
        getData(){
            axios.get('/api/bookings').then(response =>{
                this.bookings = response.data;
                
                this.bookings.forEach((booking) => {
                    if (booking.status == 1) {
                    var date = moment(booking.start).format('YYYY-MM-DD');
                    var starttime = moment(booking.start).format('HH:mm');
                    var endtime = moment(booking.end).format('HH:mm');
                    this.event.push({
                        name:booking.id,
                        day:date,
                        start:starttime,
                        end:endtime,
                        roomid:booking.room_id
                    });
                    }
                });
                
            
            });

            axios.get('/api/buildings').then(response =>{
                this.buildings = response.data.building;
                 for (let index = 0; index < this.buildings.length; index++) {
                    this.buildingitem.push({
                        value:this.buildings[index].id,
                        text:this.buildings[index].building_name,
                    })
                }
            });

            axios.get('/api/rooms').then(response =>{
                this.rooms = response.data.room;
                for (let index = 0; index < this.rooms.length; index++) {
                        this.roomitem.push({
                            value:this.rooms[index].id,
                            text:this.rooms[index].room_name,
                            building:this.rooms[index].building_id
                        })
                }
            });

            axios.get('/api/subject').then(response =>{
                this.subject = response.data;
                this.subject.forEach((sub)=>{
                    
                   /* this.event.push({
                        name:sub.subject,
                        date:sub.start,
                        start:sub.timestart,
                        end:sub.timeend,
                        roomid:sub.room_id
                    })*/
                    var formatstart  = new Date(sub.start+' '+sub.timestart);
                    var formatend = new Date(sub.start+' '+sub.timeend);
                    this.event.push({
                            name:sub.subject,
                            start:formatstart,
                            end:formatend,
                            roomid:sub.room_id
                        })
                    var datestart = new Date(sub.start+' '+sub.timestart);
                    var dateend = new Date(sub.end+' '+sub.timestart);
                    //var formatday = moment(datestart).format('YYYY-MM-DD');
                    //var datetimeend = new Date(formatday+' '+sub.timeend);
                    while (datestart < dateend){
                        datestart.setDate(datestart.getDate()+7);
                        var formatday = moment(datestart).format('YYYY-MM-DD');
                        var datetimestart = new Date(formatday+' '+sub.timestart)
                        var datetimeend = new Date(formatday+' '+sub.timeend);
                            this.event.push({
                            name:sub.subject,
                            start:datetimestart,
                            end:datetimeend,
                            roomid:sub.room_id
                        })
                        
                    }
            });
                
            });
            console.log(this.bookings);
            console.log(this.buildings);
            console.log(this.rooms);
            console.log(this.subject);
        },

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

        addDatatable(){
            var datetimestart = new Date(this.pickdate+' '+this.picktimestart);
            var datetimeend = new Date(this.pickdate+' '+this.picktimeend);
            var check = '';
            this.total = datetimestart-datetimeend;

            while(this.check.length != 0){

                this.check.shift();
            }
            
            this.clearList();

            

            console.log(this.pickdate);
            console.log(datetimeend);
            if (datetimeend <= datetimestart){
                this.err = 'เวลาไม่ถูกต้อง'
            }
            else {
                
            /*
            this.rooms.forEach((room)=>{
                this.buildings.forEach((building)=>{
                    if(room.building_id == building.id){
                        this.event.forEach((event)=>{
                          var start = new Date(event.day+' '+event.start);
                          var end = new Date(event.day+' '+event.end);
                          var statuscheck = Boolean;

                            if (start >= datetimestart ||datetimestart <= end <= datetimeend){
                                statuscheck = false;
                                this.check.push({
                                    room:event.roomid,
                                    status:statuscheck,
                                })
                            }
                            else {
                                statuscheck = true;
                                this.check.push({
                                    room:event.roomid,
                                    status:statuscheck,
                                })
                            }
                            
                        });
                        this.list.push({
                            building:building.building_name,
                            room:room.room_name,
                            status:'',
                        })
                    }
                })
            })*/
            this.event.forEach((event)=>{
                var start = new Date(event.day+' '+event.start);
                var end = new Date(event.day+' '+event.end);
               this.rooms.forEach((room)=>{
                   if(event.roomid == room.id){
                       var statuscheck = Boolean;
                        if (start >= datetimestart && start <= datetimeend || end >= datetimestart && end <= datetimeend ||
                            start <= datetimestart && end >= datetimeend){
                                statuscheck = false;
                                this.check.push({
                                    room:event.roomid,
                                    status:statuscheck,
                                    choosestart:datetimestart,
                                    chooseend:datetimeend,
                                    start:start,
                                    end:end
                                })
                        }
                        else {
                            statuscheck = true;
                                this.check.push({
                                    room:event.roomid,
                                    status:statuscheck,
                                    choosestart:datetimestart,
                                    chooseend:datetimeend,
                                    start:start,
                                    end:end
                                })
                        }
                   }
               })
            })

            if (this.check !== null){
                       
                       this.rooms.forEach((room)=>{
                           for (let i = 0; i < this.check.length; i++) {
                                    if (room.id == this.check[i].room){
                                    if (this.check[i].status == false){
                                        this.status = 'ไม่ว่าง'
                                        break;
                                    }
                                    else {
                                        
                                        this.status = 'ว่าง'
                                    }
                                    }
                                    else {
                                        this.status = 'ว่าง'
                                    }
                                }
                                this.buildings.forEach((building)=> {
                                     if (room.building_id == building.id){
                                        if (this.pickbuilding == building.id){
                                            if (this.pickroom == room.id){
                                                this.list.push({
                                                    building:building.building_name,
                                                    room:room.room_name,
                                                    status:this.status,
                                                })
                                            this.status =''
                                            }
                                            else if (this.pickroom == ''){
                                                this.list.push({
                                                    building:building.building_name,
                                                    room:room.room_name,
                                                    status:this.status,
                                                })
                                            }
                                        }
                                    

                                    else if (this.pickbuilding == ''){
                                         
                                             if (this.pickroom == room.roomid){
                                                this.list.push({
                                                    building:building.building_name,
                                                    room:room.room_name,
                                                    status:this.status,
                                                })
                                            this.status =''
                                            }
                                            else if (this.pickroom == ''){
                                                this.list.push({
                                                    building:building.building_name,
                                                    room:room.room_name,
                                                    status:this.status,
                                                })
                                            }
                                        
                                        }
                                    }
                        })
                       })
                       
                   }
            if (this.list) {
                this.list.forEach((list) =>{
                    if (this.pickstatus == list.status){
                        this.tablelist.push({
                            building:list.building,
                            room:list.room,
                            status:list.status,
                        })
                    }
                    else if (this.pickstatus == ''){
                        this.tablelist.push({
                            building:list.building,
                            room:list.room,
                            status:list.status,
                        })
                    }
                })
                this.err = ''
            }
            }
        },

        clearList(){
            while(this.list.length != 0){

                this.list.shift();
            }

            while(this.tablelist.length != 0){

                this.tablelist.shift();
            }

            
        },

        createPDF(){
            var rows = [];
          rows.push(['ชื่ออาคาร', 'ชื่อห้อง', 'สถานะ']);

          for(let i = 0;i < this.tablelist.length;i++) {
              rows.push([this.tablelist[i].building,this.tablelist[i].room,this.tablelist[i].status]);
          }
          var docDefinition = {
          
          content: [
            //{{picktimestart}} - {{picktimeend}}
            { text: 'ห้องว่างวันที่ '+this.pickdate, fontSize: 15 ,},
            { text: 'ช่วงเวลาระหว่าง '+this.picktimestart+' - '+this.picktimeend, fontSize: 15 ,},
            {table: {
                body: rows
            }}
          ],
          defaultStyle:{
            font: 'THSarabunNew'
          }
        };
        pdfMake.createPdf(docDefinition).open()
        },
    },

}
</script>
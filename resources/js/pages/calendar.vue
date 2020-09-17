<template>
  <v-app class="container">
    
    
      <v-sheet height="64">
        <v-toolbar flat color="green">
          
          <v-toolbar-title color="black darken-2" style="color:white;">{{ title }}</v-toolbar-title>
          
          <v-spacer></v-spacer>

        

        <v-btn fab text small color="white darken-2" @click="prev">
            <v-icon small>mdi-chevron-left</v-icon>
          </v-btn>
          <v-btn fab text small color="white darken-2" @click="next">
            <v-icon small>mdi-chevron-right</v-icon>
          </v-btn>

        </v-toolbar>
      </v-sheet>
      <v-sheet height="600">
        <v-calendar
          ref="calendar"
          v-model="focus"
          color="primary"
          :events="events"
          :event-color="getEventColor"
          :now="today"
          :type="type"
          :interval-format="intervalFormat"
          @click:event="showEvent"
          @click:date="getDataReserve()"
          @change="updateRange"
        ></v-calendar>
        <v-menu
          v-model="selectedOpen"
          :close-on-content-click="false"
          :activator="selectedElement"
          offset-x
        >
          <v-card
            color="grey lighten-4"
            min-width="350px"
            flat
          >
            <v-toolbar
              :color="selectedEvent.color"
              dark
            >
              <v-btn icon>
                <v-icon>mdi-pencil</v-icon>
              </v-btn>
              <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
              <v-spacer></v-spacer>
            </v-toolbar>
            <v-card-text>
              {{events.describe}}
              <span v-html="selectedEvent.details"></span>
            </v-card-text>
            <v-card-actions>
              <v-btn
                text
                color="secondary"
                @click="selectedOpen = false"
              >
                Cancel
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-menu>
      </v-sheet>



      <v-dialog
          v-model="popup"
          :close-on-click="CloseOnClick"
        >
            
            
            <v-card height='700px' >

              <v-card-text style="height: 10px; position: relative" class="grey lighten-5 text-right">
                <v-icon large color="red" right @click="popup=false; Popout()">clear</v-icon>
              </v-card-text>
                <v-card-title>
                  <span class="headline">รายการการจองวันที่ {{this.formatfocus}}</span>
                </v-card-title>
                
                <table class="table table-bordered ">
                    <thead>
                      <tr class="row">
                        <th class="col-sm-1" scope="col">รายการที่</th>
                        <th class="col-sm-2" scope="col">ชื่อผู้จอง</th>
                        <th class="col-sm-1" scope="col">รายละเอียด</th>
                        <th class="col-sm-1" scope="col">ห้อง</th>
                        <th class="col-sm-1" scope="col">วันที่</th>
                        <th class="col-sm-1" scope="col">เวลาเริ่ม</th>
                        <th class="col-sm-1" scope="col">เวลาสิ้นสุด</th>
                        <th class="col-sm-3" scope="col">หมายเหตุ</th>
                        <th class="col-sm-1" scope="col">สถานะ</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="item in datainpopup"
                      :key="item.no" class="row">
                        <th class="col-sm-1">{{item.no}}</th>
                        <td class="col-sm-2">{{item.name}}</td>
                        <td class="col-sm-1 text-center"><router-link :to="'bookingdetail/'+item.no">คลิก</router-link></td>
                        <td class="col-sm-1">{{item.room}}</td>
                        <td class="col-sm-1">{{item.date}}</td>
                        <td class="col-sm-1">{{item.start}}</td>
                        <td class="col-sm-1">{{item.end}}</td>
                        <td class="col-sm-3">{{item.describe}}</td>
                        <td class="col-sm-1">{{item.status}}</td>
                      </tr>
                    </tbody>
                </table>
  
               
            </v-card>
        </v-dialog>


  </v-app>
</template>

<script>
import axios from 'axios';
window.moment = require('moment');
//import jsPDF from 'jspdf';
//import 'jspdf-autotable';
import { mapGetters } from 'vuex'
  export default {
    
    middleware: 'auth',
    name:'reservation',
    props:['app'],

    data: () => ({
      focus: '',
      type: 'month',
      typeToLabel: {
        month: 'เดือน',
        week: 'อาทิตย์',
        day: 'วัน',
        '4day': '4 วัน',
      },
      start: null,
      end: null,
      CloseOnClick:true,
      selectedEvent: {},
      selectedElement: null,
      selectedOpen: false,
      events: [
        {
          name:'ปีใหม่',
          start:'2020-01-01',
          color:'red',
        },
        {
          name:'ปีใหม่',
          start:'2019-12-31',
          color:'red',
        },
        {
          name:'ปีใหม่',
          start:'2019-12-30',
          color:'red',
        },
        {
          name:'ตรุษจีน',
          start:'2020-01-25',
          end:'2020-01-27',
          color:'green darken-4',
        },
        {
          name:'วันมาฆบูชา',
          start:'2020-02-08',
          color:'red',
        },
        {
          name:'หยุดชดเชยวันมาฆบูชา',
          start:'2020-02-10',
          color:'red',
        },
        {
          name:'วันวาเลนไทน์',
          start:'2020-02-14',
          color:'green darken-4',
        },
        {
          name:'วันจักรี',
          start:'2020-04-06',
          color:'red',
        },
        {
          name:'วันสงกรานต์',
          start:'2020-04-13',
          end:'2020-04-15',
          color:'red',
        },
        {
          name:'วันแรงงาน',
          start:'2020-05-01',
          color:'red',
        },
        {
          name:'วันฉัตรมงคล',
          start:'2020-05-04',
          color:'red',
        },
        {
          name:'วันวิสาขบูชา',
          start:'2020-05-06',
          color:'red',
        },

      ],
      //errors:[],
      colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'],
      names: ['Meeting', 'Holiday', 'PTO', 'Travel', 'Event', 'Birthday', 'Conference', 'Party'],
      rooms:[],
      room:[],
      nowMenu: false,
      now:'',
      bookings:'',
      popup:false,
      formatfocus:'',
      datainpopup:[],
      users:[],
      report:false,

      }),
    computed: {
    ...mapGetters({
        user: 'auth/user',
    }),

      title () {
        const { start, end } = this
        if (!start || !end) {
          return '';
        }

        const startMonth = this.monthFormatter(start)
        const endMonth = this.monthFormatter(end)
        const suffixMonth = startMonth === endMonth ? '' : endMonth

        const startYear = start.year
        const endYear = end.year
        const suffixYear = startYear === endYear ? '' : endYear

        const startDay = start.day + this.nth(start.day)
        const endDay = end.day + this.nth(end.day)

        switch (this.type) {
          case 'month':
            return `${startMonth} ${startYear}`
          case 'week':
          case '4day':
            return `${startMonth} ${startDay} ${startYear} - ${suffixMonth} ${endDay} ${suffixYear}`
          case 'day':
            return `${startMonth} ${startDay} ${startYear}`
        }
        return ''
        
      },
      monthFormatter () {
        return this.$refs.calendar.getFormatter({
          timeZone: 'UTC', month: 'long',
        })
      },

      clearData(){
      if(this.popup == false){
        while(this.datainpopup.length != 0){

          this.datainpopup.shift();
          }
        }
      },

      
      

      },


    mounted () {
      this.$refs.calendar.checkChange();
      this.getBookingdata();
      this.formatDatestart();
      this.formatDateend();
      this.getSubjectData();

      //var dddd = thisday.getDate();
      //console.log(dddd);
      //var mmmm = String(thisday.getMonth() + 1); //January is 0!
      //var yy = thisday.getFullYear();
     // this.thisday = moment(end).format('YYYY-MM-DD')
      

        let hour = 23;
        let hours = 0;
        let minute = 60;
        let minutes = 0;

      //this.check=this.rooms.id

        for (let i = 0; i <= hour;i++){
            hours = i;
            let result='';
            if (hours < 10){
            result = '0'+hours;
            this.hour.push(result);
            }
            else {
            result = hours;
            this.hour.push(result);
            }
        }

   
        axios.get('/api/buildings').then(response=>{
                this.se = response.data;})

        this.thisday = new Date();
        this.thistoday = moment(this.thisday,'YYYYMMDD').format('YYYY-MM-DD')
    },
    methods: {

      Popout(){
        while(this.datainpopup.length != 0){

          this.datainpopup.shift();
        }
      },

      formatDatestart(){
          let result = this.now+" "+this.choosestarthour+this.choosestartminute+'00';
          this.formatstart = moment(result,'YYYYMMDDhmmss').format('YYYY-MM-DD HH:mm:ss')
        },
      formatDateend(){
          let result = this.now+" "+this.chooseendhour+this.chooseendminute+'00';
          this.formatend = moment(result,'YYYYMMDDhmmss').format('YYYY-MM-DD HH:mm:ss')
      },

      viewDay ({ date }) {
        this.focus = date
        this.type = 'day'
      },
      getEventColor (event) {
        return event.color
      },
      setToday () {
        this.focus = this.today
      },
      prev () {
        this.$refs.calendar.prev()
      },
      next () {
        this.$refs.calendar.next()
      },

      intervalFormat(interval) {
        return interval.time
      },

      showEvent ({ nativeEvent, event }) {
        const open = () => {
          this.selectedEvent = event
          this.selectedElement = nativeEvent.target
          setTimeout(() => this.selectedOpen = true, 10)
        }

        if (this.selectedOpen) {
          this.selectedOpen = false
          setTimeout(open, 10)
        } else {
          open()
        }

        nativeEvent.stopPropagation()
      },
      updateRange ({ start, end }) {
        const events = []

        const min = new Date(`${start.date}T00:00:00`)
        const max = new Date(`${end.date}T23:59:59`)
        const days = (max.getTime() - min.getTime()) / 86400000
        const eventCount = this.rnd(days, days + 20)

        for (let i = 0; i < eventCount; i++) {
          const allDay = this.rnd(0, 3) === 0
          const firstTimestamp = this.rnd(min.getTime(), max.getTime())
          const first = new Date(firstTimestamp - (firstTimestamp % 900000))
          const secondTimestamp = this.rnd(2, allDay ? 288 : 8) * 900000
          const second = new Date(first.getTime() + secondTimestamp)

         /*events.push({
            name: this.names[this.rnd(0, this.names.length - 1)],
            start: this.formatDate(first, !allDay),
            end: this.formatDate(second, !allDay),
            color: this.colors[this.rnd(0, this.colors.length - 1)],
          })*/
        }

        this.start = start
        this.end = end
        //this.events = events
      },
      nth (d) {
        return d > 3 && d < 21
          ? 'th'
          : ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'][d % 10]
      },
      rnd (a, b) {
        return Math.floor((b - a + 1) * Math.random()) + a
      },
      formatDate (a, withTime) {
        return withTime
          ? `${a.getFullYear()}-${a.getMonth() + 1}-${a.getDate()} ${a.getHours()}:${a.getMinutes()}`
          : `${a.getFullYear()}-${a.getMonth() + 1}-${a.getDate()}`
      },
      getRoomdata(){
        axios.get('/api/rooms/').then(response=>{
                this.rooms = response.data.room;
        })
      },


      getBookingdata(){
        this.getRoomdata();
        axios.get('/api/bookings').then(response =>{
          this.bookings = response.data;

          for (let index = 0; index < this.bookings.length; index++) {
            let formatstart = moment(this.bookings[index].start,).format('YYYY-MM-DD');
            let formatend = moment(this.bookings[index].end,).format('YYYY-MM-DD');

            for (let i = 0; i < this.rooms.length; i++) {
              if(this.bookings[index].room_id == this.rooms[i].id){
                  this.events.push({
                  name:this.rooms[i].room_name,
                  start:formatstart,
                  color:'green',})  
              }
              
            }

                      
          }
        });
      },
      getDataReserve(){
        this.popup = true;
        axios.get('/api/users').then(response=>{
          this.users = response.data;
            for (let index = 0; index < this.bookings.length; index++) {
              
              let date = moment(this.bookings[index].start).format('YYYY-MM-DD');

              if (date == this.focus) {
              for (let i = 0; i < this.rooms.length; i++) {
                if (this.rooms[i].id == this.bookings[index].room_id){
                  for (let j = 0;j < this.users.length;j++){
                    if(this.bookings[index].user_id == this.users[j].id){
                      
                      let formatstart = moment(this.bookings[index].start,).format('HH:mm');
                      let formatend = moment(this.bookings[index].end,).format('HH:mm');

                      let status = '';
                      if(this.bookings[index].status == '0'){
                        status = 'กำลังดำเนินการ'
                      }
                      else if (this.bookings[index].status == '1'){
                        status = 'อนุมัติ'
                      }
                      else {
                        status = 'ไม่อนุมัติ'
                      }

                      this.datainpopup.push({
                        no:this.bookings[index].id,
                        name:this.users[j].name,
                        date:date,
                        start:formatstart,
                        end:formatend,
                        status:status,
                        describe:this.bookings[index].describe,
                        room:this.rooms[i].room_name
                      })

                   }
                  }
                }
                
              }
            }}
        });
        this.formatfocus = moment(this.focus).format('DD-MM-YYYY');
      },

      toBookingdetail(id){
        
      },

      formatdatefromfocus(){
        
      },

      //createPDF () {
    //let pdfName = 'test'; 
   /* var doc = new jsPDF();
    doc.text("Hello World", 10, 10);
    var col = ["รายการที่จอง","ชื่อผู้จอง","ห้อง","วันที่","เวลาเริ่ม","เวลาสิ้นสุด","หมายเหตุ","สถานะ"];
    var rows = [];

    this.datainpopup.forEach(element => {      
        var temp = [element.no,element.name,element.room,element.date,element.start,element.end,element.describe,element.status];
        rows.push(temp);
    });

    doc.autoTable(col, rows, { startY: 10 });
    doc.save(this.focus + '.pdf');

      },*/
    },
  }
</script>

<style scoped>
.controls {
  position: relative;
}
</style>

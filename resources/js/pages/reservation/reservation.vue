<template>
  <v-app class="container">
    
    <v-row class="fill-height">
    <v-col 
    sm="12"
    md="12"
    lg="12"
    xl="4"
    class="mb-4 controls">
        
    <card class="green"><div class="display-1" style="color:white;">{{rooms.room_name}}</div> </card>
    <card>
      <v-alert 
        type="error"
        :value="alert()">
        {{this.errors}}
      </v-alert>
    <v-input success disabled>{{user.name}}</v-input>
      <v-menu
        ref="nowMenu"
        v-model="nowMenu"
        :close-on-content-click="false"
        :nudge-right="40"
        :return-value.sync="now"
        transition="scale-transition"
        min-width="290px"
        offset-y
        color="green"

      >
        <template v-slot:activator="{ on }">
          <v-text-field
            v-model="now"
            class="mt-3"
            label="วันที่จอง"
            prepend-icon="event"
            dense
            readonly
            outlined
            hide-details
            v-on="on"
            color="green"
          ></v-text-field>
        </template>
        <v-date-picker
          v-model="now"
          no-title
          scrollable
          color="green"
          locale="th"
        >
          <v-spacer></v-spacer>
          <v-btn
            text
            color="green"
            @click="nowMenu = false"
          >
            Cancel
          </v-btn>
          <v-btn
            text
            color="green"
            @click="$refs.nowMenu.save(now)"
          >
            OK
          </v-btn>
        </v-date-picker>
      </v-menu>
      <br/>
      <!--<p class="text-justify">
        เวลาเริ่ม :
      </p>
      <v-overflow-btn
      class="my-2"
      :items="hour"
      label="0"
      target="#dropdown-example-1"
      style="width: 90px; height: 35px;"
      ></v-overflow-btn>
      :
      <v-overflow-btn
      class="my-2"
      :items="minute"
      label="0"
      target="#dropdown-example-1"
      style="width: 90px; height: 35px;"
      ></v-overflow-btn> -->

    <div>
            <b-row>
                
            <b-col md ='4' lg='3' xl="6">
               ตั้งแต่เวลา : <b-form-select v-model="picktimestart" :options="timechoose"></b-form-select>
            </b-col>
            </b-row>

            <b-row>
                
            <b-col md ='4' lg='3' xl="6">
               ถึงเวลา : <b-form-select v-model="picktimeend" :options="timechoose"></b-form-select>
            </b-col>
            </b-row>
            
    </div>
    
    <div>
      จำนวน
      <v-text-field
          v-model="count"
          type="number"
        ></v-text-field>
    </div>
    <div>
      <v-checkbox
      v-model="passcheckbox"
      :label="`Password`"
      ></v-checkbox>
    </div>
    <div v-if="passcheckbox == true">
      <v-text-field 
          v-model="Password"
          label="Password"
          type="password"
        ></v-text-field>
    </div>
    
    <!--<div>
      <v-checkbox
      v-model="checkbox"
      :label="`จองซ้ำ : ${checkbox.toString()}`"
      ></v-checkbox>
      <v-select v-if="checkbox == true"
                :items="hour"
                outlined
                v-model="choosestarthour"
                color="green"
                >00</v-select>
    </div>-->
    <br/>
    
     <v-textarea
          outlined
          v-model="describe"
          name="input-7-4"
          label="Describe"
          value="คำอธิบาย"
          color="green"
      ></v-textarea>

      <div class="my-2">
        <v-btn depressed color="success" @click="addBooking();" ><v-icon left>mdi-pencil</v-icon>จอง</v-btn>
        <v-dialog v-model="dialog" max-width="300px">
                <v-card >
                  <v-card-title>คุณต้องการจองห้อง {{rooms.room_name}} ใช่หรือไม่</v-card-title>
                  <v-card-actions >
                    <v-spacer></v-spacer>
                    <v-btn color="red darken-4" text @click="close" >ไม่</v-btn>
                    <v-btn color="blue darken-1" text @click="thisClick(); close(); ">ใช่</v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
        <v-btn depressed color="error" @click="Clear()">ยกเลิก</v-btn>
      </div>
    </card>
    </v-col>
    
    <v-col
    sm="12"
    lg="10"
    xl="8"
    class="pl-4">
      <v-sheet height="64">
        <v-toolbar flat color="green">
          <v-btn outlined class="mr-4" color="white darken-2" @click="setToday">
            Today
          </v-btn>
          <v-btn fab text small color="white darken-2" @click="prev">
            <v-icon small>mdi-chevron-left</v-icon>
          </v-btn>
          <v-btn fab text small color="white darken-2" @click="next">
            <v-icon small>mdi-chevron-right</v-icon>
          </v-btn>
          <v-toolbar-title color="black darken-2" style="color:white;">{{ title }}</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-menu bottom right>
            <template v-slot:activator="{ on }">
              <v-btn
                outlined
                color="white darken-2"
                v-on="on"
              >
                <span>{{ typeToLabel[type] }}</span>
                <v-icon right>mdi-menu-down</v-icon>
              </v-btn>
            </template>
            <v-list>
              <v-list-item @click="type = 'day'">
                <v-list-item-title>วัน</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = 'week'">
                <v-list-item-title>7 วัน</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = 'month'">
                <v-list-item-title>เดือน</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
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
          @click:more="viewDay"
          @click:date="viewDay"
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
    </v-col>
  </v-row>
  </v-app>
</template>

<script>
import axios from 'axios';
window.moment = require('moment');


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
      selectedEvent: {},
      selectedElement: null,
      selectedOpen: false,
      passcheckbox:false,
      Password:"",
      count:0,
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
      start:'',
      end:'',
      chstart:'',
      chend:'',
      bookingjaa:[],
      timestart:[],
      timeend:[],
      hour:[],
      minute:['00','15','30','45'],
      choosestarthour:'00',
      choosestartminute:'00',
      chooseendhour:'00',
      chooseendminute:'00',
      formatstart:'',
      formatend:'',
      describe:'',
      check:0,
      today:'',
      userid:'',
      subject:'',
      subjectall:'',
      datestart:Number,
      dateend:Number,
      datecheck:'',
      ds:Number,
      errors:'',
      thisday:'',
      thistoday:'',
      users:'',
      dialog:false,
      check2:0,
      checkbook:false,
      subjecttest:[],
      newdatestart:'',
      newdateend:'',
      timechoose:[
        { 
          value:'0800',
          text:'08:00'},
          { 
          value:'0900',
          text:'09:00'},
          { 
          value:'1000',
          text:'10:00'},
          { 
          value:'1100',
          text:'11:00'},
          { 
          value:'1200',
          text:'12:00'},
          { 
          value:'1300',
          text:'13:00'},
          { 
          value:'1400',
          text:'14:00'},
          { 
          value:'1500',
          text:'15:00'},
          { 
          value:'1600',
          text:'16:00'},
          { 
          value:'1700',
          text:'17:00'},],
      picktimestart:'',
      picktimeend:'',
      d: new Date(),
      tomorrow: null,
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
      mapGetters()
      {
        return 'auth/user'
      }
    },


    mounted () {
      /*var today = new Date();*/
      this.tomorrow = new Date(this.d.setDate(this.d.getDate()+1))
      this.$refs.calendar.checkChange();
      this.getRoomdata();
      this.getBookingdata();
      this.formatDatestart();
      this.formatDateend();
      this.getSubjectData();
      //this.Loopsubject();

      //var dddd = thisday.getDate();
      //console.log(dddd);
      //var mmmm = String(thisday.getMonth() + 1); //January is 0!
      //var yy = thisday.getFullYear();
     // this.thisday = moment(end).format('YYYY-MM-DD')
   
        axios.get('/api/buildings').then(response=>{
                this.se = response.data;})

        this.thisday = new Date();
        this.thistoday = moment(this.thisday,'YYYYMMDD').format('YYYY-MM-DD')
        console.log(this.thistoday);
        


        
    },
    methods: {

      formatDatestart(){
          let result = this.now+" "+this.picktimestart+'00';
          this.formatstart = moment(result,'YYYYMMDDhmmss').format('YYYY-MM-DD HH:mm:ss')
          this.newdatestart = new Date(this.formatstart);
        },
      formatDateend(){
          let result = this.now+" "+this.picktimeend+'00';
          this.formatend = moment(result,'YYYYMMDDhmmss').format('YYYY-MM-DD HH:mm:ss')
          this.newdateend = new Date(this.formatend);
          
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
        axios.get('/api/rooms/'+this.$route.params.id).then(response=>{
                this.rooms = response.data;
        })
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
      },

      addBookingdata(){
            this.formatDatestart();
            this.formatDateend();
            //let fstart = moment(this.formatstart.start).format('YYYYMMDDHH');
            /*if(!now){
              this.errors.push('กรุณาใส่วันที่ด้วย')
            }*/
            
           /* if(!choosestarthour){
              this.errors.push('กรุณาใส่วันที่ด้วย')
            }*/

            /*console.log(this.newdatestart)
            console.log(this.newdateend)*/
          
          if (!this.now){
            this.errors = "กรุณาใส่วันที่จอง"
          }
          else if (tomorrow > booktoday){
            this.errors = "กรุณาใส่วันที่จอง"
          }
          else if (this.now < this.thistoday){
            this.errors = "วันที่คุณจองผ่านมาแล้ว"
          }
          else if (this.formatend < this.formatstart){
            this.errors = "เวลาเริ่มน้อยกว่าเวลาสิ้นสุด"
          }
          else if (this.count > 50){
            this.errors = "จำกัดแค่ 50 คน"
          }
          else if (this.check == 0){
            for (let index = 0; index < this.events.length; index++) {
            let eday = moment(this.events[index].start).format('YYYY-MM-DD');
            if (this.now == eday){
              if ( this.formatstart == this.events[index].start && this.formatend == this.events[index].end){
              this.errors = "เวลานี้มีคนใช้งานแล้ว"
              this.check = 0;
              break;
              }
              else if (this.formatstart >= this.events[index].start && this.formatend <= this.events[index].end){
              this.errors = "เวลานี้มีคนใช้งานแล้ว"
              this.check = 0;
              break;
              }
              else if (this.formatstart < this.events[index].start && this.formatend > this.events[index].end){
              this.errors = "เวลานี้มีคนใช้งานแล้ว"
              this.check = 0;
              break;
              }
              else {
                this.check = 1;
                break;
              }
              /*else if (this.formatstart < this.events[index].start && this.formatend <= this.events[index].end){
              this.errors = "เวลานี้มีคนใช้งานแล้ว"
              }
              /*else if (this.formatstart >= this.events[index].start && this.formatend > this.events[index].end){
              this.errors = "เวลานี้มีคนใช้งานแล้ว"
              }
              /*else if (this.formatstart < this.events[index].start && this.formatend <= this.events[index].end){
              this.errors = "เวลานี้มีคนใช้งานแล้ว"
              }
              else if (this.formatstart >= this.events[index].start && this.formatend > this.events[index].end){
              this.errors = "เวลานี้มีคนใช้งานแล้ว"
              }
              /*else if (this.formatstart < this.events[index].start && this.formatend > this.events[index].end){
              this.errors = "เวลานี้มีคนใช้งานแล้ว"
              }*/
              }
              //this.formatDatestart();
              //this.formatDateend();
            else {
              this.check = 1;
              }
            }
          
          }
          else if (this.check = 1){
            this.dialog = true;
            this.checkbooking(this.check);
          }
      },
        checkbooking(check){
          if (check == 1){
            this.dialog = true;
          }
        },
        addBooking(){
            this.errors = ""
            this.formatDatestart();
            this.formatDateend();
            //this.tomorrow = "";
            //let fstart = moment(this.formatstart.start).format('YYYYMMDDHH');
            /*if(!now){
              this.errors.push('กรุณาใส่วันที่ด้วย')
            }*/
            
           /* if(!choosestarthour){
              this.errors.push('กรุณาใส่วันที่ด้วย')
            }*/
            
            var booktoday = new Date(this.now)
            console.log(this.tomorrow)
            console.log(booktoday)
           // console.log(this.newdatestart)
           // console.log(this.newdateend)
          if (!this.now){
            this.errors = "กรุณาใส่วันที่จอง"
          }
          else if (this.tomorrow > booktoday){
            this.errors = "จองก่อน 24 ชม. ขึ้นไป"
          }
          else if (this.now < this.thistoday){
            this.errors = "วันที่คุณจองผ่านมาแล้ว"
          }
          else if (this.formatend == this.formatstart){
            this.errors = "เวลาจองไม่ถูกต้อง"
          }
          else if (this.formatend < this.formatstart){
            this.errors = "เวลาจองไม่ถูกต้อง"
          }
          else if (this.count == 0){
            this.errors = "กรุณาใส่จำนวนคน"
          }
          else if (this.count > 50){
            this.errors = "จำกัดแค่ 50 คน"
          }
          else {
            for (let index = 0; index < this.events.length; index++) {
            let eday = moment(this.events[index].start).format('YYYY-MM-DD');
            var eventstart = new Date(this.events[index].start);;
            var eventend = new Date(this.events[index].end);
            //console.log(eventstart)
            //console.log(eventend)
            

            if (this.now == eday){
              /*if ( this.newdatestart == eventstart && this.newdateend == eventend ){
                this.errors = "เวลานี้มีคนใช้งานแล้ว"
                console.log('1')
              this.check = 0;
              break;
              }*/
              if (this.newdatestart < eventstart && this.newdateend < eventstart ){
                  //this.errors = "จองแล้ว"
                  console.log('343')
                  this.checkbook = true;
                  break;
              }

              else if (this.newdatestart > eventend && this.newdateend > eventend ){
                  //this.errors = "จองแล้ว"
                  console.log('353')
                  this.checkbook = true;
                  break;
              }

              else if (this.newdatestart >= eventstart && this.newdateend >= eventend ){
                  this.errors = "เวลานี้มีคนใช้งานแล้ว"
                  console.log('3xxz')
                  this.checkbook = false;
                  break;
              }

              else if (this.newdatestart <= eventstart && this.newdateend >= eventend ){
                  this.errors = "เวลานี้มีคนใช้งานแล้ว"
                  console.log('2')
                  this.checkbook = false;
                  break;
              }

              else if (this.newdatestart <= eventstart && this.newdateend <= eventend ){
                  this.errors = "เวลานี้มีคนใช้งานแล้ว"
                  console.log('3')
                  this.checkbook = false;
                  break;
              }

              
              else if(this.newdatestart >= eventstart && this.newdateend <= eventend){
                  this.errors = "เวลานี้มีคนใช้งานแล้ว"
                  console.log('พลาดแล้ว')
                  this.checkbook = false;
                  break;
              }

              
              
              /*else if (this.newdatestart >= eventstart && this.newdateend >= eventstart){
                  this.errors = "เวลานี้มีคนใช้งานแล้ว"
                  console.log('10')
                  this.checkbook = false;
                  break;
              }*/

              else if (this.newdatestart > eventend && this.newdateend > eventend){
                  //this.errors = "จองแล้ว"
                  console.log('333')
                  this.checkbook = true;
                  break;
              }
              
              else {
                //this.errors = "จองแล้ว"
                console.log('success')
                this.checkbook = true;
                break;
                /*let id = this.user.id;
                let roomid = this.rooms.id;
                let bookbik = this.rooms.book+1;
                
                
                let date = new Date();



                axios.post('/api/bookings',{
                    start:this.formatstart,
                    end:this.formatend,
                    describe:this.describe,
                    status:'0',
                    room_id:this.rooms.id,
                    user_id:id,
                    count:this.count,
                    password:this.Password   
                });

                axios.put('/api/rooms/book/'+this.$route.params.id,{
                book:bookbik
                //status:1,
                })

                /*axios.post('/api/bookings',{
                    booking_id:bookingidnow,
                    user_id:id,
                    jointime:date
                });*/

                


                 
                //debugger;
                  /*this.$router.push("/reserved");
                  break;*/
              }

            }
            else {
              //this.errors = "จองแล้ว"
              console.log('5')
              //this.dialog = true;
              this.checkbook = true;
              
            }
              //this.formatDatestart();
              //this.formatDateend();
              
              /*else{
                this.errors = ""
              }*/
              
              /*else {
                let id = this.user.id;
                let roomid = this.rooms.id;
                let bookbik = this.rooms.book+1;
                
                
                let date = new Date();



                axios.post('/api/bookings',{
                    start:this.formatstart,
                    end:this.formatend,
                    describe:this.describe,
                    status:'0',
                    room_id:this.rooms.id,
                    user_id:id,
                    count:this.count,
                    password:this.Password   
                });

                axios.put('/api/rooms/book/'+this.$route.params.id,{
                book:bookbik
                //status:1,
                })

                /*axios.post('/api/bookings',{
                    booking_id:bookingidnow,
                    user_id:id,
                    jointime:date
                });*/

                


                 
                //debugger;
                /*  this.$router.push("/reserved");
                  break;
              }*/


            }
          if(this.checkbook == true){
            this.dialog = true
          }
          //this.Null();
          console.log(this.checkbook)
          }
                          
        },

        Null(){
          this.tomorrow = null;
        },

        Clear(){
           this.$router.push("/home");
        },

      thisClick(){
        if(this.checkbook == true){
          let id = this.user.id;
                let roomid = this.rooms.id;
                let bookbik = this.rooms.book+1;
                
                
                let date = new Date();



                axios.post('/api/bookings',{
                    start:this.formatstart,
                    end:this.formatend,
                    describe:this.describe,
                    status:'0',
                    room_id:this.rooms.id,
                    user_id:id,
                    count:this.count,
                    password:this.Password   
                });

                axios.put('/api/rooms/book/'+this.$route.params.id,{
                book:bookbik
                //status:1,
                })

                /*axios.post('/api/bookings',{
                    booking_id:bookingidnow,
                    user_id:id,
                    jointime:date
                });*/

                


                 
              this.$router.push("/reserved");
        }
        
      },

      getBookingdata(){
        axios.get('/api/bookings').then(response=>{
                this.bookings = response.data;
                for (let index = 0; index < this.bookings.length; index++) {
                    /*const events = [];

                    const min = new Date(`${this.bookings[index].start}T00:00:00`)
                    const max = new Date(`${this.bookings[index].end}T23:59:59`)
                    const days = (max.getTime() - min.getTime()) / 86400000

                    const allDay = this.rnd(0, 3) === 0
                    const firstTimestamp = this.rnd(min.getTime(), max.getTime())
                    const first = new Date(firstTimestamp - (firstTimestamp % 900000))
                    const secondTimestamp = this.rnd(2, allDay ? 288 : 8) * 900000
                    const second = new Date(first.getTime() + secondTimestamp)*/

                    
                    //let changestart = this.bookings[index].start;
                    //this.chstart = moment(changestart,'hmm').format('HH:mm')
                    //let chengeend = this.bookings[index].end;
                    //this.chend = moment(chengeend,'hmm').format('HH:mm')
                  
                  
                  
                  
                  if (this.bookings[index].room_id == this.rooms.id && this.bookings[index].status == 1){
                    let chstart = moment(this.bookings[index].start).format('YYYY-MM-DD HH:mm')
                    let chend = moment(this.bookings[index].end).format('YYYY-MM-DD HH:mm')
                    
                    console.log(chstart),
                    console.log(chend),

                    this.events.push({
                      name:'จองห้อง : '+ this.rooms.room_name,
                      start: chstart,
                      end: chend,
                      color: 'green',
                      describe:this.bookings[index].describe/*this.colors[this.rnd(0, this.colors.length - 1)]*/,
                      })
                  }
                  //this.start = start
                  //this.end = end
                  //this.events = events
                }
        })
      },
      
      close(){
        this.check = 0;
        this.dialog = false;
      },
      getSubjectData(){
        axios.get('/api/subject').then(res =>{
          this.subject = res.data;
          for (let loop = 0; loop < this.subject.length; loop++) {
            if(this.subject[loop].room_id === this.rooms.id){
            var date = new Date(this.subject[loop].start)
          console.log(date);
          var ddd = date.getDate();
          var mmm = date.getMonth()+1;
          var yyy = date.getFullYear();
          console.log(date.toString());
          if (mmm < 10){
                if (ddd < 10){
                  var someFormattedDated = yyy+'-'+'0'+mmm+'-'+'0'+ddd;
                  //console.log(someFormattedDate);
                  this.subject[loop].start =  moment(someFormattedDated).format('YYYY-MM-DD')
                  //console.log(this.subject[index].start);
                  const start = this.subject[loop].start+" "+this.subject[loop].timestart;
                  const end = this.subject[loop].start+" "+this.subject[loop].timeend;
                  const tostart = moment(start).format('YYYY-MM-DD HH:MM')
                  const toend = moment(end).format('YYYY-MM-DD HH:MM')
                  this.events.push({
                      name:this.subject[loop].subject,
                      start: start,
                      end: end,
                      color: 'orange'/*this.colors[this.rnd(0, this.colors.length - 1)]*/,
                      })
                      //console.log(tostart)
                }
                else {
                  var someFormattedDated = yyy+'-'+'0'+mmm+'-'+'0'+ddd;
                  //console.log(someFormattedDate);
                  this.subject[loop].start =  moment(someFormattedDated).format('YYYY-MM-DD')
                  //console.log(this.subject[index].start);
                  const start = this.subject[loop].start+" "+this.subject[loop].timestart;
                  const end = this.subject[loop].start+" "+this.subject[loop].timeend;
                  const tostart = moment(start).format('YYYY-MM-DD HH:MM')
                  const toend = moment(end).format('YYYY-MM-DD HH:MM')
                  this.events.push({
                      name:this.subject[loop].subject,
                      start: start,
                      end: end,
                      color: 'orange'
                      })
                      //console.log(tostart)
                }
              }
              else {
                if (ddd < 10){
                  var someFormattedDated = yyy+'-'+'0'+mmm+'-'+'0'+ddd;
                  //console.log(someFormattedDate);
                  this.subject[loop].start =  moment(someFormattedDated).format('YYYY-MM-DD')
                  //console.log(this.subject[index].start);
                  const start = this.subject[loop].start+" "+this.subject[loop].timestart;
                  const end = this.subject[loop].start+" "+this.subject[loop].timeend;
                  const tostart = moment(start).format('YYYY-MM-DD HH:MM')
                  const toend = moment(end).format('YYYY-MM-DD HH:MM')
                  this.events.push({
                      name:this.subject[loop].subject,
                      start: start,
                      end: end,
                      color: 'orange',
                      })
                      //console.log(tostart)
                }
                else {
                  var someFormattedDated = yyy+'-'+'0'+mmm+'-'+'0'+ddd;
                  //console.log(someFormattedDate);
                  this.subject[loop].start =  moment(someFormattedDated).format('YYYY-MM-DD')
                  //console.log(this.subject[index].start);
                  const start = this.subject[loop].start+" "+this.subject[loop].timestart;
                  const end = this.subject[loop].start+" "+this.subject[loop].timeend;
                  const tostart = moment(start).format('YYYY-MM-DD HH:MM')
                  const toend = moment(end).format('YYYY-MM-DD HH:MM')
                  this.events.push({
                      name:this.subject[loop].subject,
                      start: start,
                      end: end,
                      color: 'orange',
                      })
                      //console.log(tostart)
                }
              }
          }
          }

          
          for (let index = 0; index < this.subject.length; index++) {

            
            if(this.subject[index].room_id === this.rooms.id){
              //const dateStr = this.subject[index].room_id;
              //const dates = dates_as_int.map(dateStr => new Date(dateStr).getTime())
              //let chstart = moment(this.bookings[index].start).format('YYYY-MM-DD HH:MM')
              //this.datestart = dates
              const datestart = moment(this.subject[index].start).format('DD');
              const monthstart = moment(this.subject[index].start).format('MM');
              const yearstart = moment(this.subject[index].start).format('YYYY');
              const starttotal = yearstart+monthstart+datestart;
              //this.datecheck = this.subject[index].end;
              const dateend = moment(this.subject[index].end).format('DD');
              const monthend = moment(this.subject[index].end).format('MM');
              const yearend = moment(this.subject[index].end).format('YYYY');
              const endtotal = yearend+monthend+dateend;
              const numstart = parseInt(starttotal)   
              const numend = parseInt(endtotal);

              
              var ds = parseInt(datestart);
              var ms = parseInt(monthstart);
              var ys = parseInt(yearstart);
              var de = parseInt(dateend);
              var me = parseInt(monthend);
              var ye = parseInt(yearend);
              ds+=7;
              this.dateend = numend;
              while(this.subject[index].start!=this.subject[index].end){
              var d = new Date(this.subject[index].start)


              //console.log(d.toString());
              var numberOfDaysToAdd = 7;
              d.setDate(d.getDate() + numberOfDaysToAdd); 

              
              /*const dateend = moment(this.subject[index].end).format('DD');
              const monthend = moment(this.subject[index].end).format('MM');
              const yearend = moment(this.subject[index].end).format('YYYY');*/
              var dd = d.getDate();
              var mm = d.getMonth()+1;
              var y = d.getFullYear();
              if (mm < 10){
                if (dd < 10){
                  var someFormattedDate = y+'-'+'0'+mm+'-'+'0'+dd;
                  //console.log(someFormattedDate);
                  this.subject[index].start =  moment(someFormattedDate).format('YYYY-MM-DD')
                  //console.log(this.subject[index].start);
                  const start = this.subject[index].start+" "+this.subject[index].timestart;
                  const end = this.subject[index].start+" "+this.subject[index].timeend;
                  const tostart = moment(start).format('YYYY-MM-DD HH:MM')
                  const toend = moment(end).format('YYYY-MM-DD HH:MM')
                  this.events.push({
                      name:this.subject[index].subject,
                      start: start,
                      end: end,
                      color: 'orange'/*this.colors[this.rnd(0, this.colors.length - 1)]*/,
                      })
                      //console.log(tostart)
                }
                else {
                  var someFormattedDate = y+'-'+'0'+mm+'-'+dd;
                  //console.log(someFormattedDate);
                  this.subject[index].start =  moment(someFormattedDate).format('YYYY-MM-DD')
                  //console.log(this.subject[index].start);
                  const start = this.subject[index].start+" "+this.subject[index].timestart;
                  const end = this.subject[index].start+" "+this.subject[index].timeend;
                  const tostart = moment(start).format('YYYY-MM-DD HH:MM')
                  const toend = moment(end).format('YYYY-MM-DD HH:MM')
                  this.events.push({
                      name:this.subject[index].subject,
                      start: start,
                      end: end,
                      color: 'orange'
                      })
                      //console.log(tostart)
                }
              }
              else {
                if (dd < 10){
                  var someFormattedDate = y+'-'+mm+'-'+'0'+dd;
                  //console.log(someFormattedDate);
                  this.subject[index].start =  moment(someFormattedDate).format('YYYY-MM-DD')
                  //console.log(this.subject[index].start);
                  const start = this.subject[index].start+" "+this.subject[index].timestart;
                  const end = this.subject[index].start+" "+this.subject[index].timeend;
                  const tostart = moment(start).format('YYYY-MM-DD HH:MM')
                  const toend = moment(end).format('YYYY-MM-DD HH:MM')
                  this.events.push({
                      name:this.subject[index].subject,
                      start: start,
                      end: end,
                      color: 'orange',
                      })
                      //console.log(tostart)
                }
                else {
                  var someFormattedDate = y+'-'+mm+'-'+dd;
                  //console.log(someFormattedDate);
                  this.subject[index].start =  moment(someFormattedDate).format('YYYY-MM-DD')
                  //console.log(this.subject[index].start);
                  const start = this.subject[index].start+" "+this.subject[index].timestart;
                  const end = this.subject[index].start+" "+this.subject[index].timeend;
                  const tostart = moment(start).format('YYYY-MM-DD HH:MM')
                  const toend = moment(end).format('YYYY-MM-DD HH:MM')
                  this.events.push({
                      name:this.subject[index].subject,
                      start: start,
                      end: end,
                      color: 'orange',
                      })
                      //console.log(tostart)
                }
              }
              }

              
              /*if(numstart!=numend){
                const
                var datecheck = moment(numstart).format('YYYY-MM-DD')
                this.datecheck = newDayAdd(datecheck,100);
              }*/

              
              /*if(ys < ye){
                if(ms < me){
                  ds+7;
                  if (ms == 1 || ms == 3 || ms == 5 || ms == 7 || ms == 8 || ms == 10 || ms == 10){
                    if(ds > 10){
                    var pys = ys.toString();
                    var pms = ms.toString();
                    var pds = ds.toString();
                    var ts = pys+pms+pds;
                    var pits = parseInt(ts)
                    this.datestart = pits; 
                  }
                  }
                }
                else {
                  ds+7;
                  if(ds > 10){
                    var pys = ys.toString();
                    var pms = ms.toString();
                    var pds = ds.toString();
                    var ts = pys+pms+pds;
                    var pits = parseInt(ts)
                    this.datestart = pits; 
                  }
                }
                //this.datecheck = total.toString();
              }
              //}*/

              //this.datestart = [...this.subject[index].start]
              //let daystart
              //let monthstart
              //let yearstart
              //this.datestart.push({index:datestart})
            }
          }
        })
      },
      alert(){
        if (this.errors){
          return true;
        }
        else {
          return false;
        }
      },

      Loopsubject(){
        this.subject.forEach((sub)=>{
           //this.subjecttest.push(sub.id)
           if(sub.room_id == this.rooms.id){
              var formatstart = new Date(sub.start)
              var formatend = new Date(sub.start)
              //formatstart < formatend
              var i = 0;
              while (i < 7){
                console.log(1);
                //formatstart.setDate(formatstart.getDate() + 7);
                //this.subjecttest.push({start : formatstart})
                i++;
              }
            }
        })
        debugger;
      }
    

    },
  }
</script>

<style scoped>
.controls {
  position: relative;
}

.fixpad {
  padding-right: 0%;
  padding-left: 4px;
}
</style>

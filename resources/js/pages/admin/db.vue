<template>
  <div id="app">
    <v-container class="grey lighten-5">
        <v-row
        class="mb-6"
        no-gutters
      >
        <!-- จำนวนผู้ใช้งาน -->
        <v-col
          cols="12"
          sm="4"
          offset-sm="0"
          md="4"
          offset-md="0"
          lg="2"
          offset-lg="0"
          xl="2"
          offset-xl="0"
        >
          <v-card
            class="pa-2"
            tile
            outlined
          >
            <v-card-title color="primary">จำนวนผู้ใช้งานทั้งหมด</v-card-title>
            <br/><br/>
            <h1 class="text-center">{{users}}</h1>
          </v-card>
        </v-col>

        <!-- จำนวนห้องที่เปิดใช้ -->
        <v-col
          cols="12"
          sm="4"
          offset-sm="0"
          md="4"
          offset-md="0"
          lg="2"
          offset-lg="0"
          xl="2"
          offset-xl="0"
        >
          <v-card
            class="pa-2"
            tile
            outlined
          >
            <v-card-title color="pink">จำนวนห้องเรียนทั้งหมด</v-card-title>
            <br/><br/>
            <h1 class="text-center">{{rooms.length}}</h1>
          </v-card>
        </v-col>

        <v-col
        cols="12"
        sm="12"
        offset-sm="0"
        md="12"
        offset-md="0"
        lg="7"
        offset-lg="0"
        xl="7"
        offset-xl="0"
      >
        <v-card>
          <v-card-title >จำนวนการใช้งานของห้อง</v-card-title>
          <canvas id="doughnut"  width="500" height="318"></canvas>
        </v-card>
      </v-col>
      </v-row>



    </v-container>

    <div>

    </div>
  
  
  </div>

  
</template>

<script>
import axios from 'axios';
window.moment = require('moment');
import { mapGetters } from 'vuex'
//import Chart from 'chart.js'
export default {
  middleware: 'admin',
  data(){
    return {
      date: new Date(),
      datepush:[],
      rooms:[],
      bookchart:[],
      roomnamechart:[],
      users:'',
      rndcolor:[],
      i:0,
      options: [
          { value: 'a', text: 'วัน' },
          { value: 'b', text: 'เดือน' },
          { value: 'c', text: 'ปี' },
      ],
      selected:'',
      showoption:'',
      month:'',

    }
  },

  

  watch:{
    

  },

  mounted() {
    
    //this.increasDay();
    //this.decreasDay();
   // this.callDate();
    
    this.getUserData();
    this.doughnut();
    this.line();
    
    /*var ctx = document.getElementById('graph').getContext('2d')
      var line = new Chart(ctx, {
      type: 'line',
      data: {
        labels: this.datepush,
        datasets: [
          {
            label: 'จำนวนผู้เข้าใช้งาน',
            data: [12,45,67]
          }
        ]
      }
    })*/

    console.log(line);
    console.log(doughnut);
    
  
  },

  computed:{
    /*getData(){
      switch (this.selected) {
        case 'a':
            while(this.datepush.length != 0){

            this.datepush.shift();
          }
          for (let i = 0; i < 7; i++) {
          if (i !== 0){
            this.date.setDate(this.date.getDate()+1);
            var formatDate = moment(this.date).format('YYYY-MM-DD');
            this.datepush.push(formatDate)
          }
          else {
            var formatDate = moment(this.date).format('YYYY-MM-DD');
            this.datepush.push(formatDate)
            }
          }
          this.showoption = 'วัน';
          return this.datepush;
          break;
          break;
      
        default:
          break;
      }

    },*/

    callDate:function(){
      //var date = new Date();
      switch (this.selected) {
        case 'a':
          while(this.datepush.length != 0){

            this.datepush.shift();
          }
          for (let i = 0; i < 7; i++) {
          if (i !== 0){
            this.date.setDate(this.date.getDate()+1);
            var formatDate = moment(this.date).format('YYYY-MM-DD');
            this.datepush.push(formatDate)
          }
          else {
            var formatDate = moment(this.date).format('YYYY-MM-DD');
            this.datepush.push(formatDate)
            }
          }
          this.showoption = 'วัน';
          var data = [
            {labels: this.datepush,
              datasets: [
                {
                  label: 'จำนวนผู้เข้าใช้งาน',
                  data: [12,45,67]
                }
              ]
            }
          ];
          return data;
          break;
        case 'b':
           var date = new Date ('')
           var month = date.getMonth
           console.log(month)
           this.showoption = 'เดือน';
          break;
        case 'c':
          this.showoption = 'ปี';
          break;
        default:
          break;
      }
      
    },

    line:function (){
      
    return line;
    },

    

  },

  methods:{
    doughnut(){
      this.getRoomData();
      var ctxx = document.getElementById('doughnut').getContext('2d')
      var doughnut = new Chart(ctxx, {
      type: 'doughnut',
      data : {
      datasets: [{
          data: this.bookchart,
          backgroundColor: this.rndcolor
          },
        ],
    

    // These labels appear in the legend and in the tooltips when hovering different arcs
        labels: this.roomnamechart,
    
    },
    })
    },


   


    increasDay(){
      this.i++;
      this.date.setDate(this.date.getDate()+7);
      while(this.datepush.length != 0){

          this.datepush.shift();
        }

      this.callDate();
    },

    decreasDay(){
      this.i--;
      this.date.setDate(this.date.getDate()-7);
      while(this.datepush.length != 0){

          this.datepush.shift();
        }
      this.callDate();
    },

    getRoomData(){
      axios.get('/api/rooms').then(response => {
        this.rooms = response.data.room;
        this.rooms.forEach((room)=>{
          var randomColor = Math.floor(Math.random()*16777215).toString(16);
          this.bookchart.push(room.book);
          this.roomnamechart.push(room.room_name);
          this.rndcolor.push('#'+randomColor);
        })

      })
    },

    getUserData(){
      axios.get('/api/users').then(response => {
        this.users = response.data.length;
      })
    }

  },



  
}

</script>
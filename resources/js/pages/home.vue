<template>
  
  <div>
    
    <br/>
    <br/>
    <v-app>
    <!--<card class="text-center" :title="$t('home')">
      {{ $t('you_are_logged_in') }}
    </card>-->
    <booktable/>

    
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
import booktable from './homecomponents/approvetable.vue'

export default {
  middleware: 'auth',

  metaInfo () {
    return { title: this.$t('home') }
  },

  components:{
    booktable,
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
            /*axios.get('api/bookings').then(response=>{
                this.booking = response.data;
            })*/

            
            
            axios.get('api/buildings').then(response=>{
                this.buildings = response.data.building;
            })

            /*axios.get('/api/rooms').then(response=>{
              this.rooms = response.data.room;
            })

            axios.get('/api/users').then(response=>{
              this.users = response.data;
            })

            axios.get('/api/joingroups').then(response=>{
              this.groupjoin = response.data;
            });

          this.totalData()*/

        },

        getBookingData(){
          axios.get('api/bookings').then(response=>{
            this.bookings = response.data;
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

        runCron(){
          var CronJob = require('cron').CronJob;
          var job = new CronJob('* 10 * * * *', function() {
            axios.get('api/bookings').then(response=>{
            this.booking = response.data;
            let timenow = new Date();
            for(let index = 0; index < this.booking.length;index++ ) {
              
              if(this.booking[index].status === 0){

                var bookingdate = new Date(this.booking[index].created_at+' UTC');
                
                bookingdate.setMinutes(bookingdate.getMinutes()+10);

                
                  if(timenow > bookingdate){
                    axios.put('/api/bookings/'+this.booking[index].id,{
                      status:2
                    });

                    
                  }


                
              }
              else {

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

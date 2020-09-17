<template>
    <div style="text-align: center font-family: arial; width: 50%; margin: auto;">  
        <card  :title="$t('เพิ่มวิชาที่สอน')">     
                <v-alert 
                  type="error"
                  :value="alert()">
                  {{this.error}}
                </v-alert>

                <!--<form class="form-inline">
                    <label class="col-md-2 col-form-label text-md-right">{{ $t('รหัสวิชา') }}</label>
                    <div class="form-group mb-3">
                        <input type="text" style="width: 500px; height: 35px;" class="form-control " aria-describedby="emailHelp" v-model="id"><br/>
                        <v-text-field class="col-md-10" label="รหัส" v-model="id" style="width: 1000px; height: 35px;"></v-text-field>
                    </div>
                </form> -->
                <form class="form-inline">
                    
                    <!--<label class="col-md-2 col-form-label text-md-right">{{ $t('ชื่อวิชา') }}</label>-->
                    <div class="form-group mb-3">
                        <!--<input type="text" style="width: 500px; height: 35px;" class="form-control " aria-describedby="emailHelp" v-model="subject"><br/>-->
                        <v-text-field class="col-md-10" label="ชื่อวิชา" v-model="subject" style="width: 900px; height: 35px;"></v-text-field>
                    </div>
                </form>
                 <br/>
                <form class="form-inline">
                
                    
                    <label class="col-md-2 col-form-label text-md-right">{{ $t('วันที่เริ่มเรียน') }}</label>
                    <div class="form-group mb-2">
                      <b-form-datepicker v-model="start"></b-form-datepicker>
                        <!--<v-menu
                            ref="startMenu"
                            v-model="startMenu"
                            :close-on-content-click="false"
                            :return-value.sync="start"
                            color="green"
                        >                 
                            <template v-slot:activator="{ on }">
                              <v-text-field
                                v-model="start"
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
                              v-model="start"
                              no-title
                              scrollable
                              color="green"
                            >
                              <v-spacer></v-spacer>
                              <v-btn
                                text
                                color="green"
                                @click="startMenu = false"
                              >
                                Cancel
                              </v-btn>
                              <v-btn
                                text
                                color="green"
                                @click="$refs.startMenu.save(start)"
                              >
                                OK
                              </v-btn>
                            </v-date-picker>
                        </v-menu>-->
                    </div>
                </form>

                <form class="form-inline">
                    
                    <label class="col-md-2 col-form-label text-md-right">{{ $t('วันที่สิ้นสุด') }}</label>
                    <div class="form-group mb-2">
                        <b-form-datepicker v-model="end"></b-form-datepicker>
                        <!--<v-menu
                            ref="endMenu"
                            v-model="endMenu"
                            :close-on-content-click="false"
                            :return-value.sync="end"
                            color="green"
                        >                 
                            <template v-slot:activator="{ on }">
                              <v-text-field
                                v-model="end"
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
                              v-model="end"
                              no-title
                              scrollable
                              color="green"
                            >
                              <v-spacer></v-spacer>
                              <v-btn
                                text
                                color="green"
                                @click="endMenu = false"
                              >
                                Cancel
                              </v-btn>
                              <v-btn
                                text
                                color="green"
                                @click="$refs.endMenu.save(end)"
                              >
                                OK
                              </v-btn>
                            </v-date-picker>
                        </v-menu>-->
                    </div>
                </form>

                <form class="form-inline">
                    
                    <label class="col-md-2 col-form-label text-md-right">{{ $t('เวลาเริ่มเรียน') }}</label>
                        <v-row align="center">
                          <v-col class="d-flex" cols="3" sm="2" xl="2">
                            <!--<v-select
                            :items="hour"
                            outlined
                            v-model="choosehourtimestart"
                            color="green"
                            >00</v-select>-->
                            <b-form-select style="width: 180px; height: 60px;" v-model="choosehourtimestart" :options="hour">0</b-form-select>
                          </v-col>
                            : 

                          <v-col class="d-flex" cols="3" sm="2" xl="2">
                            <!--<v-select
                            :items="minute"
                            outlined
                            v-model="chooseminutetimestart"
                            color="green"
                            >00</v-select>-->
                            <b-form-select style="width: 180px; height: 60px;" v-model="chooseminutetimestart" :options="minute">0</b-form-select>
                          </v-col>
                        </v-row>
                </form>

                <form class="form-inline">
                    
                    <label class="col-md-2 col-form-label text-md-right">{{ $t('เวลาที่สิ้นสุดการเรียน') }}</label>
                    <v-row align="center">
                          <v-col class="d-flex" cols="3" sm="2" xl="2">
                           <!-- <v-select
                            :items="hour"
                            outlined
                            v-model="choosehourtimeend"
                            color="green"
                            >00</v-select>-->
                          <b-form-select style="width: 180px; height: 60px;" v-model="choosehourtimeend" :options="hour">0</b-form-select>
                          </v-col>
                          :
                          <v-col class="d-flex" cols="3" sm="2" xl="2">
                            <!--<v-select
                            :items="minute"
                            outlined
                            v-model="chooseminutetimeend"
                            color="green"
                            >00</v-select>-->
                            <b-form-select style="width: 180px; height: 60px;" v-model="chooseminutetimeend" :options="minute">0</b-form-select>
                          </v-col>
                        </v-row>
                </form>

                <form class="form-inline">
                    
                    <label class="col-md-2 col-form-label text-md-right">{{ $t('อาคาร') }}</label>
                    <div class="form-group mb-2">
                        <!--<v-select
                            :items="buildingitem"
                            outlined
                            v-model="buildings"
                            color="green"
                        >00</v-select>-->
                        <b-form-select style="width: 400px; height: 60px;" v-model="buildings" :options="buildingitem">0</b-form-select>
                    </div>
                </form>

                <form class="form-inline">
                    
                    <label class="col-md-2 col-form-label text-md-right">{{ $t('ห้องที่เรียน') }}</label>
                    <div class="form-group mb-2">
                        <!--<v-select
                            :items="filteredData"
                            outlined
                            v-model="rooms"
                            color="green"
                            :disabled="selectdisables()"
                            >00</v-select>-->
                            <b-form-select style="width: 400px; height: 60px;" v-model="rooms" :options="filteredData" :disabled="selectdisables()">0</b-form-select>
                    </div>
                </form>

                <br/>
            <v-btn class="ma-2" color="primary" @click="addSubject()">
                <v-icon left>add </v-icon>เพิ่ม
            </v-btn>
    </card>
    </div>
</template>
<script>
import axios from 'axios';
window.moment = require('moment');
export default {
    middleware:'admin',
    name:'createsubject',
    data(){
        return {
            id:'',
            subject:'',
            day:'',
            start:'',
            end:'',
            choosehourtimestart:'00',
            chooseminutetimestart:'00',
            choosehourtimeend:'00',
            chooseminutetimeend:'00',
            buildings:'',
            rooms:'',
            startMenu: false,
            start:'',
            endMenu: false,
            end:'',
            items: ['Foo', 'Bar', 'Fizz', 'Buzz'],
            hour:[],
            minute:['00','30'],
            buildingitem:[],
            roomitem:[],
            room:'',
            building:'',
            check:true,
            daychange:'',
            error:'',
        }
    },
    computed:{
        filteredData(){
          return this.roomitem.filter(item => item.building === this.buildings)
      }
    },
    mounted(){
        this.getDatabuidling();
        this.getRoomData();
        this.selectdisables();
            let hour = 23;
            let hours = 0;
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
    methods:{
        selectdisables(){
            if (this.buildings){
               // this.roomitem.splice(10, 1);
                
                return false;
                //this.getRoomData();
            }
            else {
                return true;
            }
        },
        getDatabuidling(){
            axios.get('/api/buildings').then(response =>{
                this.building = response.data.building;
                for (let index = 0; index < this.building.length; index++) {
                    this.buildingitem.push({
                        value:this.building[index].id,
                        text:this.building[index].building_name,
                    })
                }
            })
        },
        getRoomData(){
            axios.get('/api/rooms').then(response =>{
                this.room = response.data.room;
                for (let index = 0; index < this.room.length; index++) {
                        const roomitem = [];
                        this.roomitem.push({
                            value:this.room[index].id,
                            text:this.room[index].room_name,
                            building:this.room[index].building_id
                        })
                }
            })
        },
        addSubject(){
          if(!this.subject){
            this.error = "กรุณาใส่ชื่อวิชา";
          }
          else if(!this.rooms){
            this.error = "กรุณาใส่ห้องที่เรียน";
          }
          else {
            const day = moment(this.start).format('dddd');
            if (day == 'Sunday') this.daychange='วันอาทิตย์';
            else if (day == 'Monday') this.daychange='วันจันทร์';
            else if (day == 'Tuesday') this.daychange='วันอังคาร';
            else if (day == 'Wednesday') this.daychange='วันพุทธ';
            else if (day == 'Thursday') this.daychange='วันพฤหัสบดี';
            else if (day == 'Friday') this.daychange='วันศุกร์';
            else if (day == 'Saturday') this.daychange='วันเสาร์';

            const timestart = this.choosehourtimestart+this.chooseminutetimestart+"00";
            const timestarttotal = moment(timestart, 'hmmss').format('HH:mm:ss')
            const timeend = this.choosehourtimeend+this.chooseminutetimeend+"00";
            const timeendtotal = moment(timeend, 'hmmss').format('HH:mm:ss')

            axios.post('/api/subjects',{
                id:this.id,
                subject:this.subject,
                day:this.daychange,
                start:this.start,
                end:this.end,
                timestart:timestarttotal,
                timeend:timeendtotal,
                room_id:this.rooms,
                }
            )
            this.$router.push("/admin/subject");
          }
        },
        daycheck(){
            const day = moment(this.start).format('dddd');

        },
        //roomcheck
        alert(){
        if (this.error){
          return true;
        }
        else {
          return false;
        }
      }
    }
}
</script>
<style scoped>
.selector-for-some-widget {
  box-sizing: content-box;
}
</style>
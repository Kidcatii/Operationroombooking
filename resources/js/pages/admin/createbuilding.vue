<template>
    <div>
        <card style="text-align: center font-family: arial; width: 70%; margin: auto;">
            <h2 class='text-center'>บันทึกข้อมูลอาคาร</h2>
                <br/>
                <v-alert 
                  type="error"
                  :value="alert()">
                  {{this.error}}
                </v-alert>
                <div>
                    <div class="col-md-3" v-if="image">
                              <img :src="image" class="img-responsive" height="70" width="90">
                    </div>
                    <b-form-file type="file" v-on:change="onImageChange" ></b-form-file>

        
                    <!--<b-form-file
                      v-model="image"
                      :state="Boolean(image)"
                      placeholder="Choose a file or drop it here..."
                      drop-placeholder="Drop file here..."
                      v-on:change="onImageChange"
                    ></b-form-file>-->
                </div>
                <br/>
                <!--<form class="form-inline">
                    <label class="col-md-1 col-form-label text-md-right">{{ $t('รหัสอาคาร') }}</label>
                    <div class="form-group mb-2">
                        <input type="text" style="width: 500px; height: 35px;" class="form-control " aria-describedby="emailHelp" v-model="id"><br/>
                    </div>
                </form>-->

                <form class="form-inline">
                    <label class="col-md-1 col-form-label text-md-right">{{ $t('ชื่ออาคาร') }}</label>
                    <div class="form-group mb-2">
                        <input type="text" style="width: 500px; height: 35px;" class="form-control " aria-describedby="emailHelp" v-model="buildingname"><br/>
                    </div>
                </form>
                

            <!--    <form class="form-inline">
                    <label class="col-md-1 col-form-label text-md-right">{{ $t('เวลาเปิด') }}</label>
                    <div class="form-group mb-2">
                        <v-select
                        :items="hour"
                        v-model="choosestarthour"
                        color="green"
                        right
                        >00</v-select>:
                        <v-select
                        :items="hour"
                        v-model="choosestarthour"
                        color="green"
                        >00</v-select>
                    </div>
                </form>-->
                <!--
                <div>
                    <b-input-group>
                        <template v-slot:prepend>
                            <b-input-group-text>รหัสอาคาร</b-input-group-text>
                        </template>
                        <br/>
                        <b-form-input v-model="id"></b-form-input>
                    </b-input-group>
                </div>
                <br/>
                <div>
                    <b-input-group>
                        <template v-slot:prepend>
                            <b-input-group-text >ชื่ออาคาร</b-input-group-text>
                        </template>
                        <b-form-input v-model="buildingname"></b-form-input>
                    </b-input-group>
                </div>
                <br/>
                <div>
                    <div class="col-md-3" v-if="image">
                              <img :src="image" class="img-responsive" height="70" width="90">
                    </div>
                    <b-form-file type="file" v-on:change="onImageChange" ></b-form-file>

        
                    <b-form-file
                      v-model="image"
                      :state="Boolean(image)"
                      placeholder="Choose a file or drop it here..."
                      drop-placeholder="Drop file here..."
                      v-on:change="onImageChange"
                    ></b-form-file>-->
                <!--</div>-->
                <div>
                    <div>
                        เวลาเปิด : 
                            <b-form-select style="width: 60px; height: 35px;" v-model="chooseopenhour" :options="hour" size="sm" class="mt-3">0</b-form-select>
                            :
                            <b-form-select style="width: 60px; height: 35px;" v-model="chooseopenminute" :options="minute" size="sm" class="mt-3 disabled" >0</b-form-select>
                    </div>
                    <br/>
                    <div>
                        เวลาเปิด : 
                            <b-form-select style="width: 60px; height: 35px;" v-model="chooseclosehour" :options="hour" size="sm" class="mt-3">0</b-form-select>
                            :
                            <b-form-select style="width: 60px; height: 35px;" v-model="choosecloseminute" :options="minute" size="sm" class="mt-3 disabled" >0</b-form-select>
                    </div>
                </div>
                <br/>
            <v-btn class="ma-2" color="primary" @click="addBuildingdata" >
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
    name:'createbuilding',
    data(){
        return {
            id:'',
            buildingname:'',
            chooseopenhour:'00',
            chooseopenminute:'00',
            chooseclosehour:'00',
            choosecloseminute:'00',
            hour:[''],
            minute:['00','30'],
            open:'',
            close:'',
            status:0,
            se:[],
            image: '',
            image2: '',
            image3: '',
            error:''
        }
    },
    mounted(){
        
    
        let hour = 23;
        let hours = 0;
        let minute = 60;
        let minutes = 0;



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
        
        /*for (let i = 0; i <= minute;i++){
            minutes = i;
            this.minute.push(minutes);
        }*/

    },

    methods:{
        timeopen(){
            let result = this.chooseopenhour+this.chooseopenminute+'00';
            this.open = moment(result,'hmmss').format('HH:mm:ss')
        },
        timeclose(){
            let result = this.chooseclosehour+this.choosecloseminute+'00';
            this.close = moment(result,'hmmss').format('HH:mm:ss')
        },
        addBuildingdata(){
            this.timeopen();
            this.timeclose();
            if (!this.buildingname){
                this.error = "กรุณากรอกชื่ออาคาร"
            }
            else if (this.chooseopenhour == '00'){
                this.error = "กรุณากรอกเวลาเปิด"
            }
            else if (this.open > this.close){
                this.error = "เวลาเปิดน้อยกว่าเวลาปิด"
            }
            else {
                axios.post('/api/buildings',{
                id:this.id,
                building_name:this.buildingname,
                image1:this.image,
                //image2:this.image,
                //image3:this.image,
                open:this.open,
                close:this.close,
            })
            this.$router.push('/admin/building')
            }
            
        },
        onImageChange(e) {
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.createImage(files[0]);
        },
        createImage(file) {
            let reader = new FileReader();
            let vm = this;
            reader.onload = (e) => {
                vm.image = e.target.result;
            };
            reader.readAsDataURL(file);
        },
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
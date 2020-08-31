<template>
    <div class='container'>
        <card style="text-align: center font-family: arial; width: 70%; margin: auto;">
            <h2 class='text-center'>บันทึกข้อมูลอาคาร</h2>
                <br/>
               <!--  <div>
                   <b-input-group>
                        <template v-slot:prepend>
                            <b-input-group-text >รหัสอาคาร</b-input-group-text>
                        </template>
                        <br/>
                        <b-form-input disabled v-model="id"></b-form-input>
                    </b-input-group>
                </div>-->
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
                    <!--<b-form-file
                      v-model="image"
                      :state="Boolean(image)"
                      placeholder="Choose a file or drop it here..."
                      drop-placeholder="Drop file here..."
                      v-on:change="onImageChange"
                    ></b-form-file>-->
                </div>
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
            <b-button  to='/admin/building/' @click="updating()" variant="primary">อัพเดทข้อมูล</b-button>
            <b-button  to='/admin/building/' variant="danger">ยกเลิก</b-button>
        </card>
      
    </div>
</template>

<script>
import axios from 'axios';
window.moment = require('moment');
export default {
    middleware:'admin',
    name:'editbuilding',
    data(){
        return {
            id:'',
            buildingname:'',
            image:'',
            chooseopenhour:'',
            chooseopenminute:'',
            chooseclosehour:'',
            choosecloseminute:'',
            hour:[],
            minute:['30','00'],
            open:'',
            close:'',
            buildings:[],

        }
    },
    mounted(){
        //this.getData();
        this.getBuildingData()
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
    methods:{
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
        updateBuilding(){
            const resultopen = this.chooseopenhour+":"+this.chooseopenminute+":"+"00";
            this.open = moment(resultopen,'hhmmss').format('HH:mm:ss');
            const resultclose = this.chooseclosehour+":"+this.choosecloseminute+":"+"00";
            this.close = moment(resultclose,'hhmmss').format('HH:mm:ss')
            axios.put('/api/buildings/'+this.$route.params.id,{
                id:this.id,
                building_name:this.buildingname,
                image1:this.image,
                image2:"",
                image3:"",
                open:this.open,
                close:this.close,
            })
        },

        getData(){
            axios.get('api/buildings/'+this.$route.params.id).then(response=>{
                this.buildings = response.data.building;
                this.id = buildings.id;
                this.buildingname = buildings.building_name;
                this.image = buildings.image1;
                this.chooseopenhour = moment(buildings.open,'h').format('HH');
                this.chooseopenminute = moment(buildings.open,'hhmm').format('mm');
                this.chooseclosehour = moment(buildings.close,'h').format('HH')
                this.choosecloseminute = moment(buildings.close,'hhmm').format('mm');
        });
        },

        getBuildingData(){
            axios.get('/api/buildings/'+this.$route.params.id).then(response=>{
                this.buildings = response.data;
                this.id = this.buildings.id;
                this.buildingname = this.buildings.building_name;
                this.image = this.buildings.image1;
                this.chooseopenhour = moment(this.buildings.open,'h').format('HH');
                this.chooseopenminute = moment(this.buildings.open,'hhmm').format('mm');
                this.chooseclosehour = moment(this.buildings.close,'h').format('HH')
                this.choosecloseminute = moment(this.buildings.close,'hhmm').format('mm');
            })
        },

        updating(){
            const resultopen = this.chooseopenhour+":"+this.chooseopenminute+":"+"00";
            this.open = moment(resultopen,'hhmmss').format('HH:mm:ss');
            const resultclose = this.chooseclosehour+":"+this.choosecloseminute+":"+"00";
            this.close = moment(resultclose,'hhmmss').format('HH:mm:ss');
            var idd = this.id
            axios.put('/api/buildings/'+idd,{
                id:this.id,
                building_name:this.buildingname,
                image1:this.image,
                image2:"",
                image3:"",
                open:this.open,
                close:this.close,
            })
            this.$router.push('/admin/building')
        }
    }

}
</script>
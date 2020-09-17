<template>
    <v-app>
        <card>
            <h2 class='text-center'>เพิ่มห้องเรียน</h2>
                
                <v-alert 
                  type="error"
                  :value="alert()">
                  {{this.error}}
                </v-alert>

                <br/>
                <div>
                    <div class="col-md-1" v-if="image">
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
                <v-btn @click="clearimage">เคลียร์</v-btn>
                </div>
                <br/>
                <form class="form-inline">
                    <label class="col-md-1 col-form-label text-md-right">{{ $t('รหัสห้อง') }}</label>
                    <div class="form-group mb-2">
                        <input type="text" style="width: 500px; height: 35px;" class="form-control " aria-describedby="emailHelp" v-model="id"><br/>
                    </div>
                </form>

                <form class="form-inline">
                    *
                    <label class="col-md-1 col-form-label text-md-right">{{ $t('ชื่อห้องเรียน') }}</label>
                    <div class="form-group mb-2">
                        <input type="text" style="width: 500px; height: 35px;" class="form-control " aria-describedby="emailHelp" v-model="name"><br/>
                    </div>
                </form>

                <form class="form-inline">
                    *
                    <label class="col-md-1 col-form-label text-md-right">{{ $t('อาคารเรียน') }}</label>
                    <div class="form-group mb-2">
                        <b-form-select style="width: 500px; height: 35px;" v-model="choosebuilding" :options="buildingvalue" size="sm" class="mt-3 disabled" >0</b-form-select>
                    </div>
                </form>
                <br/>
            <v-btn class="ma-2" color="primary" @click="addRoom">
                <v-icon left>add </v-icon>เพิ่ม
            </v-btn>
    </card>
    </v-app>
</template>
<script>
import axios from 'axios';
export default {
    middleware:'admin',
    name:'createroom',
    data(){
        return {
            buildings:'',
            buildingvalue:[],
            choosebuilding:'',
            image:'',
            id:'',
            name:'',
            error:'',
        }
    },
    mounted(){
        this.getValueinbuilding();
    },
    methods:{
        getValueinbuilding(){
            axios.get('/api/buildings').then(response => {
                this.buildings = response.data.building;
                for (let index = 0; index < this.buildings.length; index++) {

                    this.buildingvalue.push({
                        value:this.buildings[index].id,
                        text:this.buildings[index].building_name
                        })
                }  
            })
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
        clearimage(){
            this.image='';
        },
        addRoom(){
            if(!this.name){
                this.error = 'กรุณากรอกชื่อห้องเรียน';
            }
            else if(!this.choosebuilding){
                this.error = 'กรุณาเลือกอาคารเรียน';
            }
            else{
            axios.post('/api/rooms',{
                image1:this.image,
                id:this.id,
                room_name:this.name,
                building_id:this.choosebuilding,
                book:0,
                status:1
            })
            this.$router.push('/admin/room')
            }
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
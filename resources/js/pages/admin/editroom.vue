<template>
    <v-app>
        <card>
            <h2 class='text-center'>เพิ่มห้องเรียน</h2>
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
                <!--<form class="form-inline">
                    <label class="col-md-1 col-form-label text-md-right">{{ $t('รหัสห้อง') }}</label>
                    <div class="form-group mb-2">
                        <input type="text" style="width: 500px; height: 35px;" class="form-control " aria-describedby="emailHelp" v-model="id"><br/>
                    </div>
                </form>-->

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
            <v-btn class="ma-2" color="warning" @click="updateRoom()" >
                <v-icon left>edit </v-icon>แก้ไข
            </v-btn>
             <v-btn class="ma-2" color="error" href="/admin/room">
                <v-icon left>cancel</v-icon>ยกเลิก
            </v-btn>
    </card>
    </v-app>
</template>
<script>//href="../admin/room"
import axios from 'axios';
export default {
    middleware:'admin',
    name:'editroom',
    data(){
        return{
            room:'',
            choosebuilding:'',
            buildings:'',
            buildingvalue:[],
            image:'',
            id:'',
            name:'',
        }
    },
    mounted(){
        this.getValueinbuilding();
        this.getRoomdata();
    },
    methods:{
        getRoomdata(){
            axios.get('/api/rooms/'+this.$route.params.id).then(response => {
                this.room = response.data;
                this.id = this.room.id;
                this.image = this.room.image1;
                this.name = this.room.room_name;
                this.choosebuilding = this.room.building_id;
            })
        },
        getValueinbuilding(){
            axios.get('/api/buildings').then(response => {
                this.buildings = response.data.building;
                for (let index = 0; index < this.buildings.length; index++) {
                    console.log(this.buildings.length);
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
        updateRoom(){
            axios.put('/api/rooms/'+this.$route.params.id,{
                id:this.id,
                room_name:this.name,
                image1:this.image,
                building_id:this.choosebuilding,
                //status:1,
                //book:this.room.book
            })
            this.$router.push("/admin/room");
        }
        
    }
}
</script>
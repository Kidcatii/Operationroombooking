<template>

    <v-app>
      <card style="text-align: center font-family: arial; width: 100%; margin: auto;">
        <v-container class="grey lighten-5">
          
          <v-row>
            <v-col
              cols="6"
              xs="15"
              sm="12"
              md="12"
              lg="4"
              xl="3"
              v-for="(rooms,index) in totalroomid"
                :key="index"
            >
              <v-card
                class="mx-auto"
                max-width="400"
              >
              <v-card-text class="text--primary green">
                <div class="headline text-center"><span class="white--text">{{rooms.room_name}}</span></div>
              </v-card-text>
                <v-img v-if="!rooms.image"
                  class="white--text align-end"
                  height="200px"
                  src="https://upload.wikimedia.org/wikipedia/commons/0/0a/No-image-available.png"
                >
                </v-img>
                <v-img v-else
                  class="white--text align-end"
                  height="200px"
                  :src="rooms.image"
                >
                </v-img>
                
                <v-btn class="ma-2" tile outlined color="green" :to="'reservation/'+rooms.room_id">
                  <v-icon left>mdi-pencil</v-icon> จอง
                </v-btn>
                    
                <v-btn class="ma-2" tile outlined color="blue">
                    <v-icon left>view_headline</v-icon>รายละเอียด
                </v-btn>
              </v-card>
            </v-col>
          </v-row>
        </v-container>
        </card>

        
    </v-app>
</template>

<script>
import axios from 'axios';

export default {
    middleware: 'auth',
    props:['app'],
    name:'building',
    data(){
        return {
            ida:this.$route.params.id,
            room:[],
            buildings:[],
            rooms:[],
            roomid:[],
            roominbuilding:[],
            roomidinbuilding:[],
            roomnotinbuilding:[],
            roomidnotinbuilding:[],
            totalroomid:[],
            check:'',
            click:0
        }
    },
    mounted(){
      this.getRoomdata()  
    },
    methods:{
        getRoomdatajaa(){
            axios('api/buildings/'+this.$route.params.id ).then(response => {
                this.building = response.data;
            })
        },

        getRoomdata(){
           axios.get('/api/rooms? where building_id = '+this.ida).then(response=>{
                this.rooms = response.data.room;
                for (let index = 0; index < this.rooms.length; index++) {
                    if (this.rooms[index].building_id == this.ida) {
                        //this.roominbuilding = this.rooms[index].room_name;
                        //this.roomidinbuilding.push(this.rooms[index].id);
                        //this.roominbuilding.push(this.rooms[index].room_name);
                        this.totalroomid.push({room_id:this.rooms[index].id,room_name:this.rooms[index].room_name,image:this.rooms[index].image1})
                    }
                    else if (this.rooms[index].building_id != this.ida){
                        this.roomidnotinbuilding.push(this.rooms[index].id)
                        this.roomnotinbuilding.push(this.rooms[index].room_name)
                    }
                }
            })
        }
    }
}
</script>

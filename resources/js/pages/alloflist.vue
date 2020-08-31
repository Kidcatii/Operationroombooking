<template>
    <v-app>
      <v-data-table
        :headers="headers"
        :items="items"
        sort-by="calories"
        class="elevation-1"
      >
        <template v-slot:top>
          <v-toolbar flat color="white">

            <v-spacer></v-spacer>
    
    
    
          </v-toolbar>
        </template>
        <template v-slot:item.actions="{ item }">
          
          <v-btn @click='gotoBookingdetail(item.id)' color="primary" text >รายละเอียด</v-btn>

        </template>

    </v-data-table>
    </v-app>
</template>

<script>
import axios from 'axios';
window.moment = require('moment');
import { mapGetters } from 'vuex'
export default {

  middleware: 'auth',

    data: () => ({
      dialog: false,
      headers: [
        {
          text: 'รหัส',
          align: 'start',
          sortable: false,
          value: 'id',
        },
        { text: 'อาคาร', value: 'building' ,sortable: false},
        { text: 'ห้อง', value: 'room' ,sortable: false},
        { text: 'ชื่อผู้จอง', value: 'name' ,sortable: false},
        { text: 'วัน', value: 'date' ,sortable: false},
        { text: 'เวลาเริ่ม', value: 'start', sortable: false },
        { text: 'เวลาสิ้นสุด', value: 'end', sortable: false },
        { text: 'จำนวน', value: 'count', sortable: false },
        { text: '', value: 'actions', sortable: false },
        
      ],
      items:[],
      bookings:[],
      users:[],
      buildings:[],
      rooms:[],
      groupjoin:[],
      member:[],
      /*headers: [
        {
          text: 'id',
          align: 'start',
          sortable: false,
          value: 'id',
        },
        { text: 'อาคาร', value: 'buildingname' },
        { text: 'ห้อง', value: 'roomname' },
        { text: 'วัน', value: 'day' },
        { text: 'เวลาเริ่ม', value: 'start' },
        { text: 'เวลาสิ้นสุด', value: 'end', sortable: false },
        /*id:this.booking[index].id,
                    roomname:this.rooms[roomloop].room_name,
                    buildingname:this.building[buildingloop].building_name,
                    day:day,
                    start:start,
                    end:end,
                    userid:this.booking[index].user_id,
                    status:this.booking[index].status
      ],*/
      desserts: [],
      editedIndex: -1,
      editedItem: {
        name: '',
        calories: 0,
        fat: 0,
        carbs: 0,
        protein: 0,
      },
      defaultItem: {
        name: '',
        calories: 0,
        fat: 0,
        carbs: 0,
        protein: 0,
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
      },
    },

    mounted(){
        this.getData();
        
    },

    watch: {
      dialog (val) {
        val || this.close()
      },
    },

    created () {
      this.initialize()
    },

    methods: {
      initialize () {
        this.desserts = [
          {
            name: 'Frozen Yogurt',
            calories: 159,
            fat: 6.0,
            carbs: 24,
            protein: 4.0,
          },
          {
            name: 'Ice cream sandwich',
            calories: 237,
            fat: 9.0,
            carbs: 37,
            protein: 4.3,
          },
          {
            name: 'Eclair',
            calories: 262,
            fat: 16.0,
            carbs: 23,
            protein: 6.0,
          },
          {
            name: 'Cupcake',
            calories: 305,
            fat: 3.7,
            carbs: 67,
            protein: 4.3,
          },
          {
            name: 'Gingerbread',
            calories: 356,
            fat: 16.0,
            carbs: 49,
            protein: 3.9,
          },
          {
            name: 'Jelly bean',
            calories: 375,
            fat: 0.0,
            carbs: 94,
            protein: 0.0,
          },
          {
            name: 'Lollipop',
            calories: 392,
            fat: 0.2,
            carbs: 98,
            protein: 0,
          },
          {
            name: 'Honeycomb',
            calories: 408,
            fat: 3.2,
            carbs: 87,
            protein: 6.5,
          },
          {
            name: 'Donut',
            calories: 452,
            fat: 25.0,
            carbs: 51,
            protein: 4.9,
          },
          {
            name: 'KitKat',
            calories: 518,
            fat: 26.0,
            carbs: 65,
            protein: 7,
          },
        ]
      },

      editItem (item) {
        this.editedIndex = this.desserts.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },

      deleteItem (item) {
        const index = this.desserts.indexOf(item)
        confirm('Are you sure you want to delete this item?') && this.desserts.splice(index, 1)
      },

      close () {
        this.dialog = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      save () {
        if (this.editedIndex > -1) {
          Object.assign(this.desserts[this.editedIndex], this.editedItem)
        } else {
          this.desserts.push(this.editedItem)
        }
        this.close()
      },

      getData(){
          axios.get('/api/bookings').then(response=>{
              this.bookings = response.data;
          })

          axios.get('/api/rooms').then(response=>{
              this.rooms = response.data.room;

               

         

          axios.get('/api/users').then(response=>{
              this.users = response.data;
          })

           axios.get('/api/buildings').then(response=>{
              this.buildings = response.data.building;
          })

          axios.get('/api/joingroups').then(response=>{
              this.groupjoin = response.data;

            this.bookings.forEach((book)=>{
                this.Member(book.id)
              var date = moment(book.start).format('YYYY-MM-DD HH:mm');
              var start = moment(book.start).format('HH:mm');
              var end = moment(book.end).format('HH:mm');
              console.log('1')
              this.rooms.forEach((room)=>{
                  if (book.room_id == room.id){
                      this.users.forEach((user)=>{
                          if (book.user_id == user.id){
                              this.buildings.forEach((building)=>{
                                  if (room.building_id == building.id){
                                      this.items.push({
                                        id:book.id,
                                        room:room.room_name,
                                        date:date,
                                        start:start,
                                        end:end,
                                        name:user.name,
                                        building:building.building_name,
                                        count:this.member.length+'/'+book.count,
                                    })
                                  }
                              })
                              
                          }
                      })
                      
                  }
              })
              this.member = [];
          })
                this.items.sort((a, b) => b.id - a.id)
          })
            

          })

          //this.bookingslist();
        
      },

      //bookingslist(){
          
         
      
      Member(bookingid){
          this.groupjoin.forEach((join)=>{
            if (join.booking_id == bookingid){
              this.member.push({
                book:join.booking_id,
                user:join.user_id
                })
            }
          })
      },
       gotoBookingdetail(id){
          this.$router.push('bookingdetail/'+id)
        },

    },
  }
</script>
<template>
    <v-app>
    <!-- บน -->
    <!--<table class="table table-bordered ">
            <thead>
              <tr>
                <th scope="col">ลำดับ</th>
                <th scope="col">ชื่อ-นามสกุล</th>
                <th scope="col">อีเมล</th>
                <th scope="col">สถานะ</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="users in user"
              :key="users.id">
                <th scope="row">{{users.id}}</th>
                <td>{{users.name}}</td>
                <td>{{users.email}}</td>
                <td>{{users.role}}</td>
              </tr>
            </tbody>
        </table>-->

        <!-- ใช้ไม่เป็น -->
        <!--
        <ul class="pagination justify-content-center">
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1">Previous</a>
            </li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
                <a class="page-link" href="#">Next</a>
            </li>
        </ul>-->

        <!-- ล่าง -->
        <!--<v-data-table
          :headers="headers"
          :items="isusers"
          :sort-by="['calories', 'fat']"
          :sort-desc="[false, true]"
          :multi-sort='false'
          class="elevation-1"
        ></v-data-table> -->



        <v-data-table
          :headers="headers"
          :items="isusers"
          :sort-by="['calories', 'fat']"
          :sort-desc="[false, true]"
          :multi-sort='false'
          class="elevation-1"
        >
        </v-data-table>
    
    </v-app>
</template></v-data-table>




<script>
import axios from 'axios';
export default {
    middleware:'admin',
    name:'member',
    data(){
        return {
            user:[],
            headers: [
            {
              text: 'ลำดับ',
              align: 'start',
              sortable: true,
              value: 'number',
            },
            //{ text: 'ลำดับ', value: 'id' },
            { text: 'ชื่อ-นามสกุล', value: 'name' },
            { text: 'อีเมล', value: 'email' },
            { text: 'สถานะ', value: 'role' },
            ],
            isusers: [
              /*{
                id: '',
                name: '',
                email:'',
                role:'',
              },*/
            ],    
            num:0,

        }
    },

    mounted(){
        this.getUser();
    },

    methods: {
        getUser(){
            axios.get('/api/users').then(response =>{
                this.user = response.data;
                for (let i = 0; i < this.user.length;i++){
                    let id = this.user[i].id;
                    let name = this.user[i].name;
                    let email = this.user[i].email;
                    let role = this.user[i].role;
                    let status = '';

                    if(role === 'admin'){
                        status = 'แอดมิน'
                    }
                    else {
                        status = 'สมาชิก'
                    }
                    this.num++;
                    this.isusers.push({
                        number:this.num,
                        id:id,
                        name:name,
                        email:email,
                        role:status
                    })
                }
            })
        },

        editItem (item) {
            this.editedIndex = this.isuser.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialog = true
        },
    }
}
</script>
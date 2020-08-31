<template>
  <v-app>
  <v-data-table
    :headers="headers"
    :items="admin"
    sort-by="calories"
    class="elevation-1"
  >
    <template v-slot:top>
      <v-toolbar flat color="white">
        <v-toolbar-title>Admin</v-toolbar-title>
        <v-divider
          class="mx-4"
          inset
          vertical
        ></v-divider>
        
        <v-spacer></v-spacer>
        
        <v-btn absolute
              dark
              top
              right
              medium
              color="blue"
              @click="dialogOn()">
            <v-icon large left >add</v-icon>
            เพิ่มAdmin
        </v-btn>

        <v-dialog
          v-model="dialog"
          
        >
            <v-card height='1000px' >
                <v-card-title>
                  <span class="headline"><v-icon>search</v-icon> Search</span>
                </v-card-title>
                
                <v-row>
                    <v-col cols="9">
                        <v-text-field
                            label="Search"
                        ></v-text-field>
                    </v-col>
                </v-row>
                <v-data-table
                  :headers="headers"
                  :items="admin"
                  sort-by="calories"
                  class="elevation-1"
                  height="auto"
                >
                </v-data-table>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="green darken-1" text @click="dialog = false">Disagree</v-btn>
                  <v-btn color="green darken-1" text @click="dialog = false">Agree</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
  </v-data-table>
  </v-app>
</template>

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
              value: 'num',
            },
            //{ text: 'ลำดับ', value: 'id' },
            { text: 'ชื่อ-นามสกุล', value: 'name' },
            { text: 'อีเมล', value: 'email' },
            { text: 'สถานะ', value: 'role' },
            ],
            admin: [
              /*{
                id: '',
                name: '',
                email:'',
                role:'',
              },*/
            ],
            dialog:false, 
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
                          this.num++;
                            status = 'แอดมิน'
                            this.admin.push({
                            num:this.num,
                            id:id,
                            name:name,
                            email:email,
                            role:'แอดมิน'
                        })

                    }
                }
            })
        },

        dialogOn(){
            this.dialog = true;
        }

    }
}
</script>

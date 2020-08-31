<template>
    <v-app>

    <!--:color="[user.role===admin ? '#0066ff' : '#33cc33']"
    :class="[user.role === 'admin' ? 'bg-primary': 'bg-success']"
    background-color:#0066ff-->
    <div  v-if="user">
    <v-app-bar :id="[user.role === 'admin' ? 'p1': 'p2']"
    dark
    app 
    >
    
      <!--<v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>-->

      <v-toolbar-title>UTK Booking</v-toolbar-title>

      <v-spacer></v-spacer>

      <v-toolbar-title>{{user.name}}</v-toolbar-title>
      <v-btn  large style="background-color:#F08080" @click.prevent="logout">
        <v-icon large left>power_settings_new</v-icon>
          Logout
      </v-btn>

      <!-- <v-menu
        bot
        left
      >
        <template v-slot:activator="{ on, attrs }">
          <v-btn
            icon
            color="yellow"
            v-bind="attrs"
            v-on="on"
          >
            <v-icon>mdi-dots-vertical</v-icon>
          </v-btn>
        </template>

        <v-list>
          <v-list-item
            v-for="(item, i) in items"
            :key="i"
          >
            <v-list-item-title>{{ item.title }}</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>-->

      <template v-slot:extension>
        <v-tabs right align-with-title fixed-tabs
        hide-slider
        color="yellow"
        v-model="activeTab">
          <v-tab  v-for="items in it" :key='items.name'
          :to="{name:items.route}">
            <v-icon large >{{items.icon}}</v-icon>
            {{items.name}}
          </v-tab>

          <v-menu 
          offset-y
          :open-on-hover="openOnHover"
          :close-on-click="closeOnClick"
          :close-on-content-click="closeOnContentClick"
          v-model="click">
              <template v-slot:activator="{ on,attrs }">
          <v-tab
            v-if="user.role === 'admin'"
            v-on="on" 
            v-bind="attrs"
            
          >
          <v-icon large right
          >settings</v-icon>
          Menagement
          <v-icon small right v-if="!click"
          >keyboard_arrow_right
          </v-icon>

          <v-icon small right v-else
          >keyboard_arrow_down
          </v-icon>
          
          </v-tab>  
              </template>
              <v-list>
                <v-list-item
                  v-for="item in items"
                  :key="item.title"
                  :to="{name:item.route }"
                  @click="tabActive()"
                >
                <v-icon large>{{item.icon}}</v-icon>
                  <v-list-item-title>{{ item.title }}</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>

          </v-tabs>
      </template>


      <!--<template v-slot:extension>
        <v-tabs right align-with-title
        color="yellow"
        v-model="activeTab">

          <v-tab @click="gotoReserve()">
            <v-icon large >home</v-icon> 
            Home
          </v-tab>
          
          <v-tab >
            <v-icon large>mdi-pencil</v-icon> 
            Booking</v-tab>
          
          <v-tab @click="gotoReservelist()">
            <v-icon large>event</v-icon>
            Calendar</v-tab>
          
          <v-tab @click="gotoHistory()">
            <v-icon large>assessment</v-icon>
            History</v-tab>
          
          <v-tab>
            <v-icon large>how_to_reg</v-icon> 
            Permission</v-tab>
          
          <v-tab>
            <v-icon large>person</v-icon>
            User</v-tab>
          
          <v-tab>
            <v-icon large>people_alt</v-icon>
            Staff Member
          </v-tab>
          
          <v-menu 
          offset-y
          :open-on-hover="openOnHover"
          :close-on-click="closeOnClick"
          :close-on-content-click="closeOnContentClick">
              <template v-slot:activator="{ on,attrs }">
          <v-tab
            v-on="on" 
            v-bind="attrs"
          >
          <v-icon large right
          >settings</v-icon>
          Menagement
          <v-icon small right
          >keyboard_arrow_right
          </v-icon>
          
          </v-tab>  

              </template>
              <v-list>
                <v-list-item
                  v-for="(item, index) in items"
                  :key="index"
                >
                <v-icon large>{{item.icon}}</v-icon>
                  <v-list-item-title>{{ item.title }}</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>

        </v-tabs>
        
      </template>-->
      
    </v-app-bar >
    
    </div>


  <div v-else>
    <v-app-bar

      absolute
      color="#33cc33"
      dark
      dense="true"
    >
  UTK Booking
  <v-spacer></v-spacer>
  <template>
    <v-btn color="#0099ff" align-with-title> Login </v-btn>
    <v-btn color="#e60000" align-with-title> Register </v-btn>
  </template>
  </v-app-bar>
  </div>
    </v-app>

</template>

<script>

import { mapGetters } from 'vuex'
import LocaleDropdown from './LocaleDropdown'



export default {
  components: {
    LocaleDropdown
  },
  
  data: () => ({
    appName: window.config.appName,
    drawer: false,
    group: null,
    tab:null,
    activeTab:null,
    click:false,
    it:[
      {
        name: 'Home',
        icon : 'home',
        route: 'home',},
      /*{
        name: 'Calendar',
        icon : 'event',
        route: '',
      },*/
      {
        name: 'History',
        icon : 'assessment',
        route: 'settings.history',
      },
      /*{
        name: 'Permission',
        icon : 'how_to_reg',
        route: '',
      },
      {
        name: 'User',
        icon : 'person',
        route: '',
      },
      {
        name: 'Staff Member',
        icon : 'people_alt',
        route: '',
      },*/
      {
        name: 'Calendar',
        icon : 'event',
        route: 'calendar',
      },
    ],
    items: [
      { title: 'Resevrelist',
          icon : 'assignment_turned_in',
          route: 'admin.reservelist' },
        { title: 'Member',
          icon : 'person',
          route: 'admin.member' },
        { title: 'Staff Member',
          icon : 'people_alt',
          route: 'admin.staff' },
        { title: 'Building',
          icon : 'business',
          route: 'admin.building' },
        { title: 'Room' ,
          icon : 'meeting_room',
          route: 'admin.room'},
        { title: 'Subject',
          icon : 'import_contacts',
          route: 'admin.subject'},
        { title: 'Report',
          icon : 'description',
          route: 'admin.report'},
        { title : 'Dashborad',
          icon : 'assessment',
          route : 'admin.db'

        }
      ],
    openOnHover:true,
    closeOnClick: true,
    closeOnContentClick: true,
  }),

  watch: {
      group () {
        this.drawer = false
    },
  },

  computed: {...mapGetters({
    user : 'auth/user',  
  }),

  changeColor(){
      if (mapGetters().role = admin) {
        this.color = "#6A76AB"
      }
      else {
        this.color = "#00FF00"
      }
    },


  },

  mounted(){
    this.CSS();
  },


  methods: {
    async logout () {
      // Log out the user.
      await this.$store.dispatch('auth/logout')

      // Redirect to login.
      this.$router.push({ name: 'welcome' })
    },

    drawerOff(){
      this.drawer = false;
    },

    gotoHome(){
      this.$router.push("/");
    },

    gotoReserve(){
      this.$router.push("/home")
    },

    gotoReservelist(){
      this.$router.push("/admin/reservelist");
    },

    gotoHistory(){
      this.$router.push("/settings/history");
    },

    gotoSubject(){
      this.$router.push("/admin/subject");
    },

    gotoRoom(){
      this.$router.push("/admin/room");
    },

    gotoBuilding(){
      this.$router.push("/admin/building");
    },

    tabActive(){
      this.tabActive='2';
    },

    CSS(){
      var all = document.getElementsByClassName('v-application--wrap');
      for (var i = 0; i < all.length; i++) {
      all[i].style.minHeight = '10vh';
      }
    }

    
  },
  
}



</script>

<style scoped>
.profile-photo {
  width: 2rem;
  height: 2rem;
  margin: -.375rem 0;
}

#navbar {
  overflow: hidden;
  position: static;
  width: 100%;
  height: 20px;
}

#p1 {
  overflow: hidden;
  position: fixed;
  width: 100%;
  height: 200px;
  background-color:#2a1e97;
  }
#p2 {
  overflow: hidden;
  position: fixed;
  width: 100%;
  height: 20px;
  background-color:#33cc33;
  }

</style>

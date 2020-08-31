import Vue from 'vue'
import store from '~/store'
import router from '~/router'
import i18n from '~/plugins/i18n'
import App from '~/components/App'
import vuetify from '~/vuetify/vuetify.js'
import './style.scss';
import VueAxios from 'vue-axios'
import axios from 'axios';

import '../sass/app.scss'
import '~/plugins'
import '~/components'



//Vue.config.productionTip = false
//Vue.use(VueAxios,axios)
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

//import 'bootstrap/dist/css/bootstrap.css'
//import 'bootstrap-vue/dist/bootstrap-vue.css'
Vue.use(BootstrapVue)
//Vue.use(IconsPlugin)

/* eslint-disable no-new */



new Vue({
  i18n,
  store,
  router,
  vuetify,
  ...App,
  
 

  
})

{
  buildModules: [
    // Simple usage
    '@nuxtjs/vuetify',

    // With options
    ['@nuxtjs/vuetify', { /* module options */ }]
  ]
}

import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import '../../sass/app.scss'
import '@mdi/font/css/materialdesignicons.css' 
import 'material-design-icons-iconfont/dist/material-design-icons.css'

Vue.use(Vuetify)

const opts = {}

export default new Vuetify(opts,
    {
        icons: {
          iconfont: 'mdiSvg', //'mdiSvg', // 'mdi' || 'mdiSvg' || 'md' || 'fa' || 'fa4' || 'faSvg'
        },
        /*breakpoint: {
          thresholds: {
            xs: 340,
            sm: 540,
            md: 800,
            lg: 1280,
          },
          scrollBarWidth: 24,
        },*/
      })

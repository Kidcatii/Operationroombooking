<!--<template>
  <div>
    <div class="top-right links">
      <template v-if="authenticated">
        <router-link :to="{ name: 'home' }">
          {{ $t('home') }}
        </router-link>
      </template>
      <template v-else>
        <router-link :to="{ name: 'login' }">
          {{ $t('login') }}
        </router-link>
        <router-link :to="{ name: 'register' }">
          {{ $t('register') }}
        </router-link>
      </template>
    </div>
<div>
  <b-button v-b-modal.modal-1>Launch demo modal</b-button>
  <b-modal id="modal-1" title="BootstrapVue">
    <p class="my-4">Hello from modal!</p>
  </b-modal>
</div>




    <div class="text-center">
      <div class="title mb-4">
        {{ title }}
      </div>

      <div class="links">
        <a href="https://github.com/cretueusebiu/laravel-vue-spa">github.com/cretueusebiu/laravel-vue-spa</a>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  layout: 'basic',

  metaInfo () {
    return { title: this.$t('home') }
  },

  data: () => ({
    title: window.config.appName
  }),

  computed: mapGetters({
    authenticated: 'auth/check'
  }),
  
}
</script>

<style scoped>
.top-right {
  position: absolute;
  right: 10px;
  top: 18px;
}

.title {
  font-size: 85px;
}
</style>-->

<template>
  <div>
    <div class="top-right links">
      <template v-if="authenticated">
        <router-link :to="{ name: 'home' }">
          {{ $t('home') }}
        </router-link>
      </template>
      <template v-else>
         <a href="#" id="show-btn" @click="showLogin"> {{ $t('test-login') }} </a> 
          <a href="#" id="show-btn" @click="showRegistor"> {{ $t('test-register') }} </a> 

        <!--<router-link :to="{ name: 'login' }">
          {{ $t('login') }}
        </router-link>
        <router-link :to="{ name: 'register' }">
          {{ $t('register') }}
        </router-link>-->
      <!--POPUP LOGIN --->    

        
      </template>
    </div>
    <div class="text-center">
      <div class="title">
        <a>ระบบจองห้องปฏิบัติการออนไลน์</a>
      </div>
      <div class="belowname">
        <a>มหาวิทยาลัยเทคโนโลยีราชมงคลกรุงเทพ</a>
      </div>
      <div class="links">
        <a>Copyright © 2004 - 2020 Rajamangala University of Technology Krungthep. All rights reserved.</a>
      </div>
      <!--HIDEN LOGIN -->
      <div>
          <b-modal ref="my-login" hide-footer title="เข้าสู่ระบบ">
            <form @submit.prevent="login" @keydown="form.onKeydown($event)">
            <div class="d-block text-center">
              <!-- email -->
              <input placeholder="อีเมลผู้ใข้: Email" v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') } " class="form-control mb-2" type="email" name="email">
              <has-error :form="form" field="email" />
              <!-- password -->         
              <input placeholder="รหัสผ่านผู้ใช้: Password" v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" class="form-control mb-3" type="password" name="password">
              <has-error :form="form" field="password" />
              <v-button class="form-control mb-3" :loading="form.busy">
                {{ $t('login เข้าสู่ระบบ') }}
              </v-button>          
              <LoginWithGithub/>
            </div>
            </form>
          </b-modal>
      </div>
      <!--HIDEN REGISTOR -->
      <div>
          <b-modal ref="my-registor" hide-footer title="ลงทะเบียนใช้ระบบ">
            <form @submit.prevent="register" @keydown="form.onKeydown($event)">
            <div class="d-block text-center">
              <div class="txtl">ชื่อ-นามสกุล</div> 
              <b-row>
                <b-col cols="4">
                  <select v-model="form.postname" class="custom-select" required>
                    <option value="" disabled selected>คำนำหน้า</option>
                    <option value="1">นาย</option>
                    <option value="2">นาง</option>
                    <option value="3">นางสาว</option>
                  </select>
                </b-col>
                <b-col cols="8">
                    <input  v-model="form.name" :class="{ 'is-invalid': form.errors.has('name') }" class="form-control mb-1" type="text" name="name">
                    <has-error :form="form" field="name" />
                </b-col>
              </b-row>
              <!-- STATUS-->
              <div class="txtl">ตำแหน่ง</div>
              <input  v-model="form.position" :class="{ 'is-invalid': form.errors.has('position') }" class="form-control mb-3" type="text" name="position">
              <has-error :form="form" field="position" />
              <!-- NAME -->
              <div class="txtl">ชื่อผู้ใช้งาน</div>
              <input v-model="form.username" :class="{ 'is-invalid': form.errors.has('username') }" class="form-control mb-3" type="text" name="username">
              <has-error :form="form" field="username" />
              <!-- Email -->
              <div class="txtl">อีเมลมหาลัย/อื่นๆ</div>        
              <input  v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control mb-3" type="email" name="email">
              <has-error :form="form" field="email" />
              <!-- Password --> 
              <div class="txtl">รหัสผ่าน</div> 
              <input  v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" class="form-control mb-3" type="password" name="password">
              <has-error :form="form" field="password" />
              <!-- Password Confirmation -->
              <div class="txtl">ยืนยันรหัสผ่าน</div>
              <input  v-model="form.password_confirmation" :class="{ 'is-invalid': form.errors.has('password_confirmation') }" class="form-control mb-3" type="password" name="password_confirmation">
              <has-error :form="form" field="password_confirmation" />
              <v-button class="form-control mb-3" :loading="form.busy">
                {{ $t('Register ลงทะเบียน') }}
              </v-button> 
            </div>
            </form>
          </b-modal>
      </div>
    </div>
  </div>


</template>



<script>
import Form from 'vform'
import { mapGetters } from 'vuex'
import LoginWithGithub from '~/components/LoginWithGithub'
export default {
  layout: 'basic',
  components: {
    LoginWithGithub
  },
  metaInfo () {
    return { title: this.$t('login') } 
  },
  metaInfo () {
    return { title: this.$t('register') }
  },
  data: () => ({
    form: new Form({
      //log-in
      email: '',
      password: '',
      //register
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
      username: '',
      position: '',
      postname: '',
    }),
    remember: false,
    mustVerifyEmail: false
  }),
  computed: mapGetters({
    authenticated: 'auth/check'
  }),
  methods: {
      //POP-UP of tap ber
        showLogin() {
          this.$refs['my-login'].show()
        }, 
        showRegistor() {
          this.$refs['my-registor'].show()
        },     
      //LOGIN-MENU
        async login () {
        // Submit the form.
        const { data } = await this.form.post('/api/login')
        // Save the token.
        this.$store.dispatch('auth/saveToken', {
          token: data.token,
          remember: this.remember
        })
        // Fetch the user.
        await this.$store.dispatch('auth/fetchUser')
        // Redirect home.
        this.$router.push({ name: 'home' })
        },
      //REGISTER-MENU
        async register () {
          // Register the user.
          const { data } = await this.form.post('/api/register')
          // Must verify email fist.
          if (data.status) {
            this.mustVerifyEmail = true
          } else {
            // Log in the user.
            const { data: { token } } = await this.form.post('/api/login')
            // Save the token.
            this.$store.dispatch('auth/saveToken', { token })
            // Update the user.
            await this.$store.dispatch('auth/updateUser', { user: data })
            // Redirect home.
            this.$router.push({ name: 'home' })
          }
        },
    },
}
</script>
<style scoped>
.top-right {
  position: absolute;
  right: 10px;
  top: 18px;
}
.title {
  font-size: 75px;
}
.belowname {
  font-size: 45px;
}
.txtl {
  text-align: left;
}
</style>

<template>
<v-app>
  <div class="row">
    <!--<div class="col-md-3">
      <card :title="$t('Admin')" class="settings-card">
        <ul class="nav flex-column nav-pills">
          <li v-for="tab in tabs" :key="tab.route" class="nav-item">
            <router-link :to="{ name: tab.route }" class="nav-link" active-class="active">
              {{ tab.name }}
            </router-link>
          </li>
        </ul>
      </card>
    </div> -->

    <div class="col-md-12">
      <transition name="fade" mode="out-in">
        <router-view></router-view>
      </transition>
    </div>
  </div>
</v-app>
</template>

<script>
export default {
  middleware: 'auth',

  computed: {
    tabs () {
      return [
        /*{
          name: this.$t('อาคาร'),
          route: '#'
        },
        {
          name: this.$t('ห้องเรียน'),
          route: '#'
        },*/
        {
          name: this.$t('รายการการจอง'),
          route: 'admin.reservelist'
        },
        {
          name: this.$t('อาคารเรียน'),
          route: 'admin.building'
        },
        {
          name: this.$t('ห้องเรียน'),
          route: 'admin.room'
        },
        {
          name: this.$t('วิชาที่สอน'),
          route: 'admin.subject'
        }
      ]
    }
  }
}
</script>

<style>
.settings-card .card-body {
  padding: 0;
}
</style>

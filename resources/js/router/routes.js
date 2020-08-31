function page (path) {
  return () => import(/* webpackChunkName: '' */ `~/pages/${path}`).then(m => m.default || m)
}

export default [
  { path: '/', name: 'welcome', component: page('welcome.vue') },
  { path: '/wrongurl', name: 'wrongurl', component: page('/wrongurl.vue') },

  { path: '/login', name: 'login', component: page('auth/login.vue') },
  { path: '/register', name: 'register', component: page('auth/register.vue') },
  { path: '/password/reset', name: 'password.request', component: page('auth/password/email.vue') },
  { path: '/password/reset/:token', name: 'password.reset', component: page('auth/password/reset.vue') },
  { path: '/email/verify/:id', name: 'verification.verify', component: page('auth/verification/verify.vue') },
  { path: '/email/resend', name: 'verification.resend', component: page('auth/verification/resend.vue') },
  

  { path: '/home', name: 'home', component: page('home.vue') },
  { path: '/building/:id', name: 'room', component: page('reservation/building.vue') },
  { path: '/building/reservation/:id', name: 'reservation', component: page('reservation/reservation.vue') },
  { path: '/calendar', name: 'calendar', component: page('calendar.vue') },
  { path: '/reserved', name: 'reserved', component: page('reserved.vue') },
  { path: '/bookingdetail/:id', name: 'bookingdetail', component: page('bookingdetail.vue') },
  { path: '/alloflist', name: 'alloflist', component: page('alloflist.vue') },
  { path: '/admin', 
  component: page('admin/dashboard.vue'), 
  children: [
    {path: '', redirect:{name: 'admin.reservelist'}},
    {path: 'db', name: 'admin.db', component: page('admin/db.vue')},
    {path: 'reservelist', name: 'admin.reservelist', component: page('admin/reservelist.vue')},
    {path: 'reservelistdetail/:id', name: 'admin.reservelistdetail', component: page('admin/reservelistdetail.vue')},
    {path: 'building', name: 'admin.building', component: page('admin/building.vue')},
    {path: 'createbuilding', name: 'admin.createbuilding', component: page('admin/createbuilding.vue')},
    {path: 'editbuilding/:id', name: 'admin.editbuilding', component: page('admin/editbuilding.vue')},
    {path: 'room', name: 'admin.room', component: page('admin/room.vue')},
    {path: 'createroom', name: 'admin.createroom', component: page('admin/createroom.vue')},
    {path: 'editroom/:id', name: 'admin.editroom', component: page('admin/editroom.vue')},
    {path: 'subject', name: 'admin.subject', component: page('admin/subject.vue')},
    {path: 'createsubject', name: 'admin.create', component: page('admin/createsubject.vue')},
    {path: 'editsubject/:id', name: 'admin.editsubject', component: page('admin/editsubject.vue')},
    {path: 'member', name: 'admin.member', component: page('admin/member.vue')},
    {path: 'staff', name: 'admin.staff', component: page('admin/staffmember.vue')},
    {path: 'report', name: 'admin.report', component: page('admin/report.vue')},
    
  ]},
  { path: '/settings',
    component: page('settings/index.vue'),
    children: [
      { path: '', redirect: { name: 'settings.profile' } },
      { path: 'profile', name: 'settings.profile', component: page('settings/profile.vue') },
      { path: 'password', name: 'settings.password', component: page('settings/password.vue') },
      { path: 'history', name: 'settings.history', component: page('settings/history.vue') }
    ] },

  { path: '*', component: page('errors/404.vue') }
]

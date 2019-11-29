require('./bootstrap');

//window.Vue = require('vue');
import Vue from 'vue';

import { Form, HasError, AlertError } from 'vform'

window.Form = Form

import Snotify, { SnotifyPosition } from 'vue-snotify'

const snotifyOptions = {
  toast: {
    position: SnotifyPosition.rightTop
  }
}

Vue.use(Snotify, snotifyOptions)

import VueProgressBar from 'vue-progressbar'

const VueProgressBaroptions = {
  color: '#50d38a',
  failedColor: '#874b4b',
  thickness: '5px',
  transition: {
    speed: '0.2s',
    opacity: '0.6s',
    termination: 300
  },
  autoRevert: true,
  location: 'top',
  inverse: false
}
Vue.use(VueProgressBar, VueProgressBaroptions)

Vue.component('user-component', require('./components/UserComponent.vue').default);
Vue.component('pagination-component', require('./components/partials/PaginationComponent.vue').default);

//V-Form Component

Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

const app = new Vue({
    el: '#app'
});

window.Popper = require('popper.js');
require('./bootstrap');
window.Vue = require('vue');

import VModal from 'vue-js-modal';
Vue.use(VModal , { componentName: 'modal'});

$(function() {
    $(".todos-task-wrapper").niceScroll();
});

Vue.component('app-todo-list', require('./components/todolist.vue'));
import draggable from 'vuedraggable';
Vue.component('draggable', draggable);

import  App from './components/app.vue';

const app = new Vue({
    el: '#app',
    render: h => h(App)
});
Vue.config.devtools = false;

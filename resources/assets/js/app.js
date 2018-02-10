
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
window.Vue.use(VueRouter);

//import Auth from './auth.js'
//window.Vue.use(Auth);

import TaskIndex from './components/TaskIndex.vue';
import TaskLogin from './components/TaskLogin.vue';
import TaskRegister from './components/TaskRegister.vue';

const routes = [
    {path: '/index', component: TaskIndex, name: 'index', props : true},
    {path: '/register', component: TaskRegister, name: 'register'},
    {path: '/', components:{
        taskLogin : TaskLogin
    } },
]
 
const router = new VueRouter({ routes })

const app = new Vue({ router }).$mount('#app')

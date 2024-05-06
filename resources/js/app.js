/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('./bootstrap');

window.Vue = require('vue').default;
window.axios = require('axios');

//import Vue from 'vue'
import Buefy from 'buefy'

// import 'buefy/dist/buefy.css'
//import LeafletMachine from 'leaflet-routing-machine'

//QR Scanner
import VueQrcodeReader from "vue-qrcode-reader";
//for QR CODE Generation
import VueQrcode from '@chenfengyuan/vue-qrcode';

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

//Vue.component('navbar-component', require('./components/Administrator/NavbarComponent.vue').default);
//Vue.component('home-page', require('./components/HomePage.vue').default);
//Vue.component('register-components', require('./components/SignupComponent.vue').default);


//USER
//Vue.component('user-page', require('./components/Administrator/User/UserPage.vue').default);






/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//import VueQuill from '@vueup/vue-quill';



Vue.use(Buefy)
//Vue.use(LeafletMachine);

Vue.use(VueQrcodeReader); //https://gruhn.github.io/vue-qrcode-reader/demos/CustomTracking.html

Vue.component(VueQrcode.name, VueQrcode);

//Vue.component('QuillEditor', VueQuill.QuillEditor);


import VueQuillEditor from 'vue-quill-editor'

import 'quill/dist/quill.core.css' // import styles
import 'quill/dist/quill.snow.css' // for snow theme
import 'quill/dist/quill.bubble.css' // for bubble theme

Vue.use(VueQuillEditor, /* { default global options } */)


const app = new Vue({
    el: '#app',
});

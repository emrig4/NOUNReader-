require('./bootstrap');

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


window.Vue = require('vue').default;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Register Vue Components
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('vue-pdf-embed', require('./components/VuePdfEmbedComponent.vue').default);
Vue.component('vue-pdf-air', require('./components/VuePdfAppComponent.vue').default);
// Vue.component('vue-pdf-app', require('./vendor/vue-pdf-app').default);
// Vue.component('vue-pdf-app', require('./vendor/dist').default);





// Initialize Vue
const app = new Vue({
    el: '#app',
});
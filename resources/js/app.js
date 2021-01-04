require('./bootstrap');

window.Vue = require('vue');

Vue.component('find-doctors', require('./components/FindDoctors.vue').default);
Vue.component('input-field', require('./components/InputField.vue').default);

const app = new Vue({
    el: '#app',
});

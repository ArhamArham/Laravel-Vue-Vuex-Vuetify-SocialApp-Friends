require('./bootstrap');
window.Vue = require('vue');
import store from './Vuex';
import router from './router'
import vuetify from './vuetify';
import App from './components/AppComponent'
import VueRx from 'vue-rx'
// install vue-rx
Vue.use(VueRx)
import day from './day';

Vue.use(day);

const app = new Vue({
    el: '#app',
    store,
    router,
    vuetify,
    components:{
        'App':App
    }
});

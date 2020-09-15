

require('./bootstrap');

window.Vue = require('vue');
import router from './router';
import vuetify from './vuetify';

import App from './components/AppComponent';



const app = new Vue({
    el: '#app',
    router,
    vuetify,
    components: {
    'app-component': App
    },
});

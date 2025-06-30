import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import router from './routes';
import clickOutside from './directives/clickOutside';

import './style.css';
const app = createApp(App);
const pinia = createPinia();

app.directive('click-outside', clickOutside);
app.use(router);
app.use(pinia);
app.mount('#app');

import 'styles/map.scss';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from '@/pages/leaflet-map';

createApp(App).use(createPinia()).mount('#map');

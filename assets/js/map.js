import 'styles/map.scss';
import { createApp } from 'vue';
import App from '@/pages/leaflet-map';
import Bubble from '@/plugins/bubble';

createApp(App).use(Bubble).mount('#map');

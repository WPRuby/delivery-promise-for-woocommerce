import { createApp } from 'vue';
import App from './App.vue';
import './styles.css';

const mountEl = document.getElementById('delivery-promise-lite-admin');

if (mountEl) {
  mountEl.classList.add('delivery-promise-lite-admin');
  mountEl.innerHTML = '';
  createApp(App).mount(mountEl);
}

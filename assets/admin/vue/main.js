import { createApp } from 'vue';
import App from './App.vue';
import './styles.css';

const mountEl = document.getElementById('eddd-admin');

if (mountEl) {
  mountEl.classList.add('eddd-admin');
  mountEl.innerHTML = '';
  createApp(App).mount(mountEl);
}

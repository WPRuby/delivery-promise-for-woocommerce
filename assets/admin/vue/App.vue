<template>
  <div class="dpl-shell">
    <AppHeader
      :dirty="isDirty"
      :saving="state.savingSettings"
      @save="saveSettings"
    />

    <nav class="dpl-tabs" :aria-label="tabsLabel">
      <button
        v-for="tab in tabs"
        :key="tab.id"
        type="button"
        class="dpl-tab"
        :class="{ 'dpl-tab--active': activeTab === tab.id }"
        :aria-current="activeTab === tab.id ? 'page' : undefined"
        @click="setActiveTab(tab.id)"
      >
        <Icon :name="tab.icon" />
        <span class="dpl-tab__label">{{ tab.label }}</span>
      </button>
    </nav>

    <div class="dpl-shell__body">
      <div v-if="state.notice" style="margin-bottom: 18px;">
        <Notice :notice="state.notice" @close="clearNotice" />
      </div>

      <div v-if="!state.ready && !state.loadError" class="wpruby-dp-skeleton">
        {{ loadingLabel }}
      </div>

      <div v-else-if="state.loadError" class="wpruby-dp-view">
        <div class="wpruby-dp-notice wpruby-dp-notice--error">
          <span>{{ state.loadError }}</span>
        </div>
        <div>
          <button type="button" class="wpruby-dp-btn" @click="loadAll">{{ retryLabel }}</button>
        </div>
      </div>

      <component v-else :is="currentView" />
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import AppHeader from './components/AppHeader.vue';
import Notice from './components/Notice.vue';
import Icon from './components/Icon.vue';
import GeneralSettings from './views/GeneralSettings.vue';
import EstimateSettings from './views/EstimateSettings.vue';
import DisplaySettings from './views/DisplaySettings.vue';
import { state, isDirty, loadAll, saveSettings, clearNotice } from './store.js';
import { __ } from './api/client.js';

const tabs = [
  { id: 'general', label: __('General'), icon: 'settings', view: GeneralSettings },
  { id: 'estimate', label: __('Estimate'), icon: 'clock', view: EstimateSettings },
  { id: 'display', label: __('Display'), icon: 'eye', view: DisplaySettings },
];
const tabIds = tabs.map((tab) => tab.id);
const activeTab = ref(tabFromHash());

const tabsLabel = __('Estimated Delivery sections');
const loadingLabel = __('Loading…');
const retryLabel = __('Try again');

const currentView = computed(() => {
  const tab = tabs.find((t) => t.id === activeTab.value);
  return tab ? tab.view : GeneralSettings;
});

function beforeUnload(e) {
  if (isDirty.value) {
    e.preventDefault();
    e.returnValue = '';
  }
}

function tabFromHash() {
  const hash = window.location.hash.replace(/^#/, '');
  const id = hash.split('?')[0];
  return tabIds.includes(id) ? id : 'general';
}

function setActiveTab(id) {
  activeTab.value = id;
  window.location.hash = id;
}

function onHashChange() {
  activeTab.value = tabFromHash();
}

onMounted(() => {
  loadAll();
  window.addEventListener('beforeunload', beforeUnload);
  window.addEventListener('hashchange', onHashChange);
});

onUnmounted(() => {
  window.removeEventListener('beforeunload', beforeUnload);
  window.removeEventListener('hashchange', onHashChange);
});
</script>

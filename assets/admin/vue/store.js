import { reactive, computed } from 'vue';
import { api, ApiError, __ } from './api/client.js';

const boot = window.deliveryPromiseLite || {};

export const state = reactive({
  ready: false,
  loadError: '',
  settings: null,
  savedSnapshot: '',
  savingSettings: false,
  data: boot.data || {},
  notice: null,
});

let noticeTimer = null;

export function setNotice(type, message) {
  state.notice = { type, message };
  if (noticeTimer) {
    clearTimeout(noticeTimer);
  }
  if (type === 'success') {
    noticeTimer = setTimeout(() => {
      state.notice = null;
    }, 4000);
  }
}

export function clearNotice() {
  state.notice = null;
}

export const isDirty = computed(() => {
  if (!state.settings) {
    return false;
  }
  return JSON.stringify(state.settings) !== state.savedSnapshot;
});

function snapshot() {
  state.savedSnapshot = JSON.stringify(state.settings);
}

export async function loadAll() {
  state.ready = false;
  state.loadError = '';
  try {
    const settings = await api.getSettings();
    state.settings = settings;
    snapshot();
    state.ready = true;
  } catch (err) {
    state.loadError =
      err instanceof ApiError ? err.message : __('Unable to load settings.');
  }
}

export async function saveSettings() {
  if (!state.settings || state.savingSettings) {
    return;
  }

  state.savingSettings = true;
  try {
    const response = await api.saveSettings(state.settings);
    state.settings = response.settings || state.settings;
    snapshot();
    setNotice('success', __('Settings saved.'));
  } catch (err) {
    setNotice(
      'error',
      err instanceof ApiError ? err.message : __('Unable to save settings.')
    );
  } finally {
    state.savingSettings = false;
  }
}

export async function previewMessage(overrides = {}) {
  const payload = {
    ...(state.settings || {}),
    ...overrides,
  };
  return api.preview(payload);
}

<template>
  <div class="wpruby-dp-view">
    <SettingsSection :title="sections.title" :description="sections.desc">
      <SettingsCard :title="cards.template" icon="message">
        <div class="wpruby-dp-field">
          <label class="wpruby-dp-field__label" for="dp-lite-message-template">{{ labels.template }}</label>
          <textarea
            id="dp-lite-message-template"
            class="dpl-input wpruby-dp-input"
            rows="3"
            v-model="settings.message_product"
            @blur="refreshPreview"
          ></textarea>
        </div>
        <div class="wpruby-dp-chiprow" style="margin-top: 12px;">
          <span class="wpruby-dp-chiprow__label">{{ labels.placeholders }}</span>
          <button
            v-for="ph in placeholders"
            :key="ph.token"
            type="button"
            class="wpruby-dp-quickadd__btn"
            :title="ph.desc"
            @click="insertPlaceholder(ph.token)"
          >
            {{ ph.token }}
          </button>
        </div>
      </SettingsCard>

      <SettingsCard :title="cards.placement" icon="pin">
        <RadioCards
          v-model="settings.product_placement"
          :options="placements"
          :label="labels.placement"
        />
      </SettingsCard>

      <SettingsCard :title="cards.style" icon="eye">
        <RadioCards
          v-model="settings.display_style"
          :options="styleOptions"
          :label="labels.style"
        />
      </SettingsCard>

      <SettingsCard :title="cards.preview" icon="truck">
        <DisplayPreview :message="previewMessage" :style="settings.display_style" :show-icon="settings.show_icon" />
        <p v-if="previewError" class="wpruby-dp-field__error">{{ previewError }}</p>
        <p v-if="previewLoading" class="wpruby-dp-field__help">{{ labels.previewLoading }}</p>
      </SettingsCard>
    </SettingsSection>
  </div>
</template>

<script setup>
import { computed, ref, watch, onMounted } from 'vue';
import SettingsSection from '../components/SettingsSection.vue';
import SettingsCard from '../components/SettingsCard.vue';
import RadioCards from '../components/RadioCards.vue';
import DisplayPreview from '../components/DisplayPreview.vue';
import { state, previewMessage as fetchPreview } from '../store.js';
import { __ } from '../api/client.js';

const settings = computed(() => state.settings || {});
const data = computed(() => state.data || {});
const placeholders = computed(() => data.value.placeholders || []);
const placements = computed(() => data.value.placements || []);

const styleOptions = computed(() =>
  (data.value.styles || []).map((opt) => ({
    ...opt,
    desc: opt.value === 'highlighted'
      ? __('Soft highlighted box on the product page.')
      : __('Simple text without extra styling.'),
  }))
);

const previewMessage = ref('');
const previewError = ref('');
const previewLoading = ref(false);

const sections = {
  title: __('Display'),
  desc: __('Customize how the estimate appears on product pages.'),
};

const cards = {
  template: __('Message template'),
  placement: __('Placement'),
  style: __('Style'),
  preview: __('Live preview'),
};

const labels = {
  template: __('Message template'),
  placeholders: __('Insert placeholder'),
  placement: __('Placement on product page'),
  style: __('Display style'),
  previewLoading: __('Updating preview…'),
};

async function refreshPreview() {
  if (!state.settings) {
    return;
  }
  previewLoading.value = true;
  previewError.value = '';
  try {
    const result = await fetchPreview();
    previewMessage.value = result.message || '';
  } catch (e) {
    previewError.value = __('Unable to generate preview.');
  } finally {
    previewLoading.value = false;
  }
}

function insertPlaceholder(token) {
  const current = settings.value.message_product || '';
  settings.value.message_product = current + token;
  refreshPreview();
}

watch(
  () => [
    settings.value.message_product,
    settings.value.processing_min,
    settings.value.processing_max,
    settings.value.transit_min,
    settings.value.transit_max,
    settings.value.working_days,
    settings.value.cutoff_time,
    settings.value.holidays,
    settings.value.display_style,
    settings.value.show_icon,
    settings.value.product_placement,
  ],
  () => refreshPreview(),
  { deep: true }
);

onMounted(() => refreshPreview());
</script>

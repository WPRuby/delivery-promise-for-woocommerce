<template>
  <div class="wpruby-dp-view">
    <SettingsSection :title="sections.title" :description="sections.desc">
      <div class="wpruby-dp-grid-2">
        <SettingsCard :title="cards.lite" icon="check">
          <ul class="wpruby-dp-feature-list">
            <li v-for="item in liteFeatures" :key="item">{{ item }}</li>
          </ul>
        </SettingsCard>

        <SettingsCard :title="cards.pro" icon="sparkles">
          <ul class="wpruby-dp-feature-list wpruby-dp-feature-list--pro">
            <li v-for="item in proFeatures" :key="item">{{ item }}</li>
          </ul>
          <div style="margin-top: 18px;">
            <a
              :href="proUrl"
              class="dpl-button dpl-button--primary wpruby-dp-btn wpruby-dp-btn--primary"
              target="_blank"
              rel="noopener noreferrer"
            >
              {{ labels.upgrade }}
            </a>
          </div>
        </SettingsCard>
      </div>

      <Callout v-if="proActive" variant="warning" style="margin-top: 18px;">
        {{ labels.proActiveNotice }}
      </Callout>
    </SettingsSection>
  </div>
</template>

<script setup>
import SettingsSection from '../components/SettingsSection.vue';
import SettingsCard from '../components/SettingsCard.vue';
import Callout from '../components/Callout.vue';
import { bootData, __ } from '../api/client.js';

const proUrl = bootData.proUrl || 'https://wpruby.com/plugin/delivery-promise-for-woocommerce/';
const proActive = !!bootData.proActive;

const sections = {
  title: __('Upgrade to Pro'),
  desc: __('Compare Lite with Delivery Promise Pro and unlock advanced delivery rules and storewide display.'),
};

const cards = {
  lite: __('Lite (this plugin)'),
  pro: __('Pro'),
};

const labels = {
  upgrade: __('Upgrade to Pro'),
  proActiveNotice: __(
    'Delivery Promise Pro is active. Please deactivate this Lite plugin to avoid duplicate delivery estimates.'
  ),
};

const liteFeatures = [
  __('Product page estimate'),
  __('Processing and transit days'),
  __('Business days'),
  __('Message template'),
  __('Up to 5 excluded dates'),
];

const proFeatures = [
  __('Advanced delivery rules'),
  __('Cutoff countdown'),
  __('Product and category lead times'),
  __('Shipping method estimates'),
  __('Cart, checkout, order, and email display'),
  __('Delivery Promise Tester'),
  __('Rule presets'),
  __('Saved promise on order'),
];
</script>

<style scoped>
.wpruby-dp-feature-list {
  margin: 0;
  padding-left: 1.2em;
  line-height: 1.7;
}
.wpruby-dp-feature-list--pro {
  color: #0f172a;
}
</style>

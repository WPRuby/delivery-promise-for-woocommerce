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
              class="wpruby-dp-btn wpruby-dp-btn--primary"
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
  desc: __('Delivery Promise Pro adds advanced rules, countdown timers, and storewide delivery promises.'),
};

const cards = {
  lite: __('Lite (this plugin)'),
  pro: __('Pro'),
};

const labels = {
  upgrade: __('Learn about Delivery Promise Pro'),
  proActiveNotice: __(
    'Delivery Promise Pro is active. Please deactivate this Lite plugin to avoid duplicate delivery estimates.'
  ),
};

const liteFeatures = [
  __('Product page estimate'),
  __('Simple processing and transit days'),
  __('Basic working days'),
  __('Simple message template'),
  __('Up to 5 excluded dates'),
];

const proFeatures = [
  __('Advanced delivery rules'),
  __('Cutoff countdown timers'),
  __('Product and category lead times'),
  __('Shipping method estimates'),
  __('Cart, checkout, order, and email display'),
  __('Order promise snapshot'),
  __('Delivery promise tester'),
  __('Unlimited holidays and rule presets'),
];
</script>

<style scoped>
.wpruby-dp-feature-list {
  margin: 0;
  padding-left: 1.2em;
  line-height: 1.7;
}
.wpruby-dp-feature-list--pro {
  color: #1d2327;
}
</style>

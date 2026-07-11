<template>
  <div class="wpruby-dp-view">
    <SettingsSection :title="sections.main" :description="sections.mainDesc">
      <SettingsCard>
        <div class="wpruby-dp-togglebox">
          <label class="wpruby-dp-toggle">
            <input
              type="checkbox"
              :checked="settings.enabled"
              @change="settings.enabled = $event.target.checked"
            />
            <span class="wpruby-dp-toggle__track" aria-hidden="true"></span>
          </label>
          <div class="wpruby-dp-togglebox__main">
            <div class="wpruby-dp-togglebox__title">{{ labels.enable }}</div>
            <div class="wpruby-dp-togglebox__desc">{{ labels.enableHelp }}</div>
          </div>
          <StatusBadge :variant="settings.enabled ? 'on' : 'off'" dot>
            {{ settings.enabled ? labels.on : labels.off }}
          </StatusBadge>
        </div>
      </SettingsCard>

      <SettingsCard>
        <ToggleField
          v-model="settings.display_product"
          :label="labels.productPage"
          :help="labels.productPageHelp"
        />
      </SettingsCard>

      <SettingsCard>
        <ToggleField
          v-model="settings.in_stock_only"
          :label="labels.inStockOnly"
          :help="labels.inStockOnlyHelp"
        />
      </SettingsCard>
    </SettingsSection>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import SettingsSection from '../components/SettingsSection.vue';
import SettingsCard from '../components/SettingsCard.vue';
import ToggleField from '../components/ToggleField.vue';
import StatusBadge from '../components/StatusBadge.vue';
import { state } from '../store.js';
import { __ } from '../api/client.js';

const settings = computed(() => state.settings || {});

const sections = {
  main: __('General'),
  mainDesc: __('Enable delivery estimates and choose where they appear on product pages.'),
};

const labels = {
  enable: __('Enable Delivery Promise'),
  enableHelp: __('Turn delivery promise estimates on or off across your store.'),
  on: __('On'),
  off: __('Off'),
  productPage: __('Show estimate on product pages'),
  productPageHelp: __('Display a delivery or dispatch estimate on WooCommerce single product pages.'),
  inStockOnly: __('Only show for in-stock products'),
  inStockOnlyHelp: __('Hide the estimate when a product is out of stock or on backorder.'),
};
</script>

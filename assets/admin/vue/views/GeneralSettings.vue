<template>
  <div class="wpruby-dp-view">
    <SettingsSection :title="sections.main" :description="sections.mainDesc">
      <SettingsCard :title="cards.status" icon="settings">
        <ToggleField
          v-model="settings.enabled"
          row
          :label="labels.enable"
          :help="labels.enableHelp"
        />
        <div style="margin-top: 14px;">
          <StatusBadge :variant="settings.enabled ? 'on' : 'off'" dot>
            {{ settings.enabled ? labels.on : labels.off }}
          </StatusBadge>
        </div>
      </SettingsCard>

      <SettingsCard :title="cards.productPage" icon="eye">
        <ToggleField
          v-model="settings.display_product"
          row
          :label="labels.productPage"
          :help="labels.productPageHelp"
        />
      </SettingsCard>

      <SettingsCard :title="cards.availability" icon="check">
        <ToggleField
          v-model="settings.in_stock_only"
          row
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

const cards = {
  status: __('Plugin status'),
  productPage: __('Product page display'),
  availability: __('Product availability'),
};

const labels = {
  enable: __('Enable estimated delivery dates'),
  enableHelp: __('Turn delivery promise estimates on or off across your store.'),
  on: __('On'),
  off: __('Off'),
  productPage: __('Show estimate on product pages'),
  productPageHelp: __('Display a delivery or dispatch estimate on WooCommerce single product pages.'),
  inStockOnly: __('Only show for in-stock products'),
  inStockOnlyHelp: __('Hide the estimate when a product is out of stock or on backorder.'),
};
</script>

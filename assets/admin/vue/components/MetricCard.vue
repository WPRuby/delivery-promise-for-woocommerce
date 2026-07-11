<template>
  <div class="wpruby-dp-metric">
    <span class="wpruby-dp-metric__icon" :class="iconClass">
      <Icon :name="icon" />
    </span>
    <div class="wpruby-dp-metric__body">
      <div class="wpruby-dp-metric__value">{{ value }}</div>
      <div class="wpruby-dp-metric__label">{{ label }}</div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import Icon from './Icon.vue';

const props = defineProps({
  icon: { type: String, default: 'box' },
  value: { type: [String, Number], default: '' },
  label: { type: String, default: '' },
  tone: { type: String, default: '' }, // '' | 'on' | 'off'
});

const iconClass = computed(() =>
  props.tone ? `wpruby-dp-metric__icon--${props.tone}` : ''
);
</script>

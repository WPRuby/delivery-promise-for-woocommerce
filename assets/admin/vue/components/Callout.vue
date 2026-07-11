<template>
  <div class="wpruby-dp-callout" :class="`wpruby-dp-callout--${variant}`" role="note">
    <span class="wpruby-dp-callout__icon" aria-hidden="true">
      <Icon :name="iconName" />
    </span>
    <div>
      <div v-if="title" class="wpruby-dp-callout__title">{{ title }}</div>
      <div><slot>{{ message }}</slot></div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import Icon from './Icon.vue';

const props = defineProps({
  variant: { type: String, default: 'info' }, // info | warning | error | success
  title: { type: String, default: '' },
  message: { type: String, default: '' },
});

const iconName = computed(() => {
  if (props.variant === 'warning') return 'warning';
  if (props.variant === 'error') return 'error';
  if (props.variant === 'success') return 'check';
  return 'info';
});
</script>

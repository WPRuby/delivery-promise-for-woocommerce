<template>
  <span class="wpruby-dp-badge" :class="`wpruby-dp-badge--${variant}`">
    <span v-if="dot" class="wpruby-dp-badge__dot" aria-hidden="true"></span>
    <slot>{{ label }}</slot>
  </span>
</template>

<script setup>
defineProps({
  variant: { type: String, default: 'info' },
  label: { type: String, default: '' },
  dot: { type: Boolean, default: false },
});
</script>

<template>
  <div class="wpruby-dp-empty">
    <span v-if="icon" class="wpruby-dp-empty__icon">
      <Icon :name="icon" />
    </span>
    <p class="wpruby-dp-empty__title">{{ title }}</p>
    <p v-if="description" class="wpruby-dp-empty__desc">{{ description }}</p>
    <div v-if="$slots.actions" class="wpruby-dp-empty__actions">
      <slot name="actions" />
    </div>
  </div>
</template>

<script setup>
import Icon from './Icon.vue';

defineProps({
  icon: { type: String, default: '' },
  title: { type: String, required: true },
  description: { type: String, default: '' },
});
</script>

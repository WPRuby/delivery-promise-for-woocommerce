<template>
  <section class="wpruby-dp-card" :class="{ 'wpruby-dp-card--pad-sm': compact }">
    <header v-if="title || description || $slots.actions" class="wpruby-dp-card__head">
      <span v-if="icon" class="wpruby-dp-card__icon" aria-hidden="true">
        <Icon :name="icon" />
      </span>
      <div class="wpruby-dp-card__head-main">
        <h2 v-if="title" class="wpruby-dp-card__title">{{ title }}</h2>
        <p v-if="description" class="wpruby-dp-card__desc">{{ description }}</p>
      </div>
      <div v-if="$slots.actions" style="margin-left: auto;">
        <slot name="actions" />
      </div>
    </header>
    <slot />
  </section>
</template>

<script setup>
import Icon from './Icon.vue';

defineProps({
  title: { type: String, default: '' },
  description: { type: String, default: '' },
  icon: { type: String, default: '' },
  compact: { type: Boolean, default: false },
});
</script>

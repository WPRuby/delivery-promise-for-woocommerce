<template>
  <section class="wpruby-dp-section">
    <header v-if="title || $slots.actions" class="wpruby-dp-section__head">
      <div>
        <p v-if="eyebrow" class="wpruby-dp-section__eyebrow">{{ eyebrow }}</p>
        <h2 v-if="title" class="wpruby-dp-section__title">{{ title }}</h2>
        <p v-if="description" class="wpruby-dp-section__desc">{{ description }}</p>
      </div>
      <div v-if="$slots.actions" class="wpruby-dp-actionbar">
        <slot name="actions" />
      </div>
    </header>
    <slot />
  </section>
</template>

<script setup>
defineProps({
  eyebrow: { type: String, default: '' },
  title: { type: String, default: '' },
  description: { type: String, default: '' },
});
</script>

<template>
  <div v-if="row" class="dpl-card-row">
    <div class="dpl-card-row__content">
      <div class="dpl-card-row__title">{{ label }}</div>
      <p v-if="help" class="dpl-card-row__help">{{ help }}</p>
    </div>
    <label class="dpl-switch wpruby-dp-toggle">
      <input
        type="checkbox"
        :checked="modelValue"
        :disabled="disabled"
        @change="$emit('update:modelValue', $event.target.checked)"
      />
      <span class="dpl-switch__track wpruby-dp-toggle__track" aria-hidden="true"></span>
      <span class="wpruby-dp-sr-only">{{ label }}</span>
    </label>
  </div>

  <div v-else class="wpruby-dp-field">
    <label class="dpl-switch wpruby-dp-toggle">
      <input
        type="checkbox"
        :checked="modelValue"
        :disabled="disabled"
        @change="$emit('update:modelValue', $event.target.checked)"
      />
      <span class="dpl-switch__track wpruby-dp-toggle__track" aria-hidden="true"></span>
      <span class="wpruby-dp-toggle__label">{{ label }}</span>
    </label>
    <p v-if="help" class="wpruby-dp-field__help">{{ help }}</p>
  </div>
</template>

<script setup>
defineProps({
  modelValue: { type: Boolean, default: false },
  label: { type: String, default: '' },
  help: { type: String, default: '' },
  row: { type: Boolean, default: false },
  disabled: { type: Boolean, default: false },
});
defineEmits(['update:modelValue']);
</script>

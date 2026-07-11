<template>
  <div class="wpruby-dp-field">
    <label class="wpruby-dp-toggle">
      <input
        type="checkbox"
        :checked="modelValue"
        @change="$emit('update:modelValue', $event.target.checked)"
      />
      <span class="wpruby-dp-toggle__track" aria-hidden="true"></span>
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
});
defineEmits(['update:modelValue']);
</script>

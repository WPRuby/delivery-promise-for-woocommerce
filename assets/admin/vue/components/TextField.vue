<template>
  <div class="wpruby-dp-field">
    <label v-if="label" class="wpruby-dp-field__label" :for="fieldId">{{ label }}</label>
    <input
      :id="fieldId"
      type="text"
      :value="modelValue"
      :placeholder="placeholder"
      :class="inputClass"
      @input="$emit('update:modelValue', $event.target.value)"
    />
    <p v-if="error" class="wpruby-dp-field__error">{{ error }}</p>
    <p v-else-if="help" class="wpruby-dp-field__help">{{ help }}</p>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  modelValue: { type: String, default: '' },
  label: { type: String, default: '' },
  placeholder: { type: String, default: '' },
  help: { type: String, default: '' },
  error: { type: String, default: '' },
  inputClass: { type: String, default: '' },
});
defineEmits(['update:modelValue']);

const fieldId = computed(
  () => 'wpruby-dp-' + (props.label || 'text').toLowerCase().replace(/[^a-z0-9]+/g, '-') + '-' + Math.random().toString(36).slice(2, 7)
);
</script>

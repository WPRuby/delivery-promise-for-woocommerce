<template>
  <div class="wpruby-dp-field">
    <label v-if="label" class="wpruby-dp-field__label" :for="fieldId">{{ label }}</label>
    <input
      :id="fieldId"
      type="time"
      :value="modelValue"
      class="wpruby-dp-input--sm"
      @input="$emit('update:modelValue', $event.target.value)"
    />
    <p v-if="help" class="wpruby-dp-field__help">{{ help }}</p>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  modelValue: { type: String, default: '' },
  label: { type: String, default: '' },
  help: { type: String, default: '' },
});
defineEmits(['update:modelValue']);

const fieldId = computed(
  () => 'wpruby-dp-time-' + Math.random().toString(36).slice(2, 7)
);
</script>

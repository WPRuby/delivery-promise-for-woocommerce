<template>
  <div class="wpruby-dp-field wpruby-dp-field--select">
    <label
      v-if="label"
      class="wpruby-dp-field__label"
      :class="{ 'wpruby-dp-sr-only': hideLabel }"
      :for="fieldId"
    >{{ label }}</label>
    <div
      class="wpruby-dp-select-wrapper"
      :class="{ 'wpruby-dp-select-wrapper--disabled': disabled }"
    >
      <select
        :id="fieldId"
        class="wpruby-dp-select"
        :value="modelValue"
        :disabled="disabled"
        @change="$emit('update:modelValue', $event.target.value)"
      >
        <option v-if="placeholder" value="">{{ placeholder }}</option>
        <option v-for="opt in options" :key="opt.value" :value="opt.value">
          {{ opt.label }}
        </option>
      </select>
    </div>
    <p v-if="help" class="wpruby-dp-field__help">{{ help }}</p>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  modelValue: { type: [String, Number], default: '' },
  options: { type: Array, default: () => [] },
  label: { type: String, default: '' },
  help: { type: String, default: '' },
  placeholder: { type: String, default: '' },
  disabled: { type: Boolean, default: false },
  hideLabel: { type: Boolean, default: false },
});
defineEmits(['update:modelValue']);

const fieldId = computed(
  () => 'wpruby-dp-select-' + (props.label || 's').toLowerCase().replace(/[^a-z0-9]+/g, '-') + '-' + Math.random().toString(36).slice(2, 7)
);
</script>

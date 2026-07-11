<template>
  <fieldset class="wpruby-dp-field" style="border: 0; padding: 0; margin: 0;">
    <legend v-if="label" class="wpruby-dp-field__label">{{ label }}</legend>
    <div class="wpruby-dp-daypills" role="group" :aria-label="label || groupLabel">
      <button
        v-for="opt in options"
        :key="opt.value"
        type="button"
        class="wpruby-dp-daypill"
        :class="{ 'wpruby-dp-daypill--active': isChecked(opt.value) }"
        :aria-pressed="isChecked(opt.value)"
        @click="toggle(opt.value)"
      >
        {{ shortLabel(opt.label) }}
      </button>
    </div>
    <p v-if="help" class="wpruby-dp-field__help">{{ help }}</p>
  </fieldset>
</template>

<script setup>
import { __ } from '../api/client.js';

const props = defineProps({
  modelValue: { type: Array, default: () => [] },
  options: { type: Array, default: () => [] },
  label: { type: String, default: '' },
  help: { type: String, default: '' },
  numeric: { type: Boolean, default: true },
});
const emit = defineEmits(['update:modelValue']);

const groupLabel = __('Working days');

function normalize(v) {
  return props.numeric ? Number(v) : v;
}

function isChecked(value) {
  return props.modelValue.map(normalize).includes(normalize(value));
}

function toggle(value) {
  const v = normalize(value);
  const set = props.modelValue.map(normalize);
  const next = set.includes(v) ? set.filter((x) => x !== v) : [...set, v];
  emit('update:modelValue', next);
}

function shortLabel(label) {
  return String(label).slice(0, 3);
}
</script>

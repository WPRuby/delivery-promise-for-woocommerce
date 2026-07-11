<template>
  <div class="wpruby-dp-field">
    <label v-if="label" class="wpruby-dp-field__label" :for="fieldId">{{ label }}</label>
    <input
      :id="fieldId"
      type="number"
      :min="min"
      :step="1"
      :value="displayValue"
      :placeholder="placeholder"
      class="wpruby-dp-input--xs"
      @input="onInput"
    />
    <p v-if="error" class="wpruby-dp-field__error">{{ error }}</p>
    <p v-else-if="help" class="wpruby-dp-field__help">{{ help }}</p>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  modelValue: { type: [Number, String, null], default: 0 },
  label: { type: String, default: '' },
  min: { type: Number, default: 0 },
  help: { type: String, default: '' },
  error: { type: String, default: '' },
  placeholder: { type: String, default: '' },
  nullable: { type: Boolean, default: false },
});
const emit = defineEmits(['update:modelValue']);

const displayValue = computed(() =>
  props.modelValue === null || props.modelValue === undefined ? '' : props.modelValue
);

function onInput(event) {
  const raw = event.target.value;
  if (raw === '') {
    emit('update:modelValue', props.nullable ? null : 0);
    return;
  }
  emit('update:modelValue', Math.max(props.min, parseInt(raw, 10) || 0));
}

const fieldId = computed(
  () => 'wpruby-dp-num-' + (props.label || 'n').toLowerCase().replace(/[^a-z0-9]+/g, '-') + '-' + Math.random().toString(36).slice(2, 7)
);
</script>

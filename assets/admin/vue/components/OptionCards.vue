<template>
  <div class="wpruby-dp-optgrid" role="group" :aria-label="label">
    <label
      v-for="opt in options"
      :key="opt.value"
      class="wpruby-dp-optcard"
      :class="{ 'wpruby-dp-optcard--active': isChecked(opt.value) }"
    >
      <input
        type="checkbox"
        :checked="isChecked(opt.value)"
        @change="toggle(opt.value)"
      />
      <span class="wpruby-dp-optcard__check" aria-hidden="true">
        <Icon name="check" />
      </span>
      <span>
        <span class="wpruby-dp-optcard__title">{{ opt.label }}</span>
        <span v-if="opt.desc" class="wpruby-dp-optcard__desc">{{ opt.desc }}</span>
      </span>
    </label>
  </div>
</template>

<script setup>
import Icon from './Icon.vue';

const props = defineProps({
  modelValue: { type: Array, default: () => [] },
  options: { type: Array, default: () => [] },
  label: { type: String, default: '' },
});
const emit = defineEmits(['update:modelValue']);

function isChecked(value) {
  return props.modelValue.includes(value);
}

function toggle(value) {
  const next = props.modelValue.includes(value)
    ? props.modelValue.filter((v) => v !== value)
    : [...props.modelValue, value];
  emit('update:modelValue', next);
}
</script>

<template>
  <div class="wpruby-dp-field">
    <span v-if="label" class="wpruby-dp-field__label">{{ label }}</span>
    <div class="wpruby-dp-optgrid dpl-radio-cards" role="radiogroup" :aria-label="label">
      <label
        v-for="opt in options"
        :key="opt.value"
        class="wpruby-dp-optcard dpl-radio-card"
        :class="{ 'wpruby-dp-optcard--active dpl-radio-card--active': modelValue === opt.value }"
      >
        <input
          type="radio"
          :name="groupName"
          :value="opt.value"
          :checked="modelValue === opt.value"
          @change="$emit('update:modelValue', opt.value)"
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
    <p v-if="help" class="wpruby-dp-field__help">{{ help }}</p>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import Icon from './Icon.vue';

const props = defineProps({
  modelValue: { type: [String, Number], default: '' },
  options: { type: Array, default: () => [] },
  label: { type: String, default: '' },
  help: { type: String, default: '' },
  name: { type: String, default: '' },
});
defineEmits(['update:modelValue']);

const groupName = computed(
  () => props.name || 'dpl-radio-' + (props.label || 'group').toLowerCase().replace(/[^a-z0-9]+/g, '-')
);
</script>

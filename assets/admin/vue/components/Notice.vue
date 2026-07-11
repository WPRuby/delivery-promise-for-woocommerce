<template>
  <div
    class="wpruby-dp-notice"
    :class="`wpruby-dp-notice--${notice.type}`"
    role="status"
    aria-live="polite"
  >
    <span>{{ notice.message }}</span>
    <button
      type="button"
      class="wpruby-dp-notice__close"
      :aria-label="closeLabel"
      @click="$emit('close')"
    >
      ×
    </button>
  </div>
</template>

<script setup>
import { __ } from '../api/client.js';

defineProps({
  notice: { type: Object, required: true },
});
defineEmits(['close']);

const closeLabel = __('Dismiss');
</script>

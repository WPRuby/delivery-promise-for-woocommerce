<template>
  <div
    class="eddd eddd--preview"
    :class="`eddd--${style}`"
  >
    <span v-if="showIcon" class="eddd__icon" aria-hidden="true">&#128666;</span>
    <span class="eddd__message" v-html="safeMessage"></span>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { __ } from '../api/client.js';

const props = defineProps({
  message: { type: String, default: '' },
  style: { type: String, default: 'highlighted' },
  showIcon: { type: Boolean, default: true },
});

const safeMessage = computed(() => {
  return props.message || __('Order today and get it between July 15 – July 18.');
});
</script>

<style scoped>
.eddd--preview {
  margin: 0;
}
.eddd--plain {
  padding: 0;
  background: transparent;
  border: 0;
}
.eddd--highlighted {
  padding: 0.75em 1em;
  border-radius: 10px;
  background: #fce7f3;
  border: 1px solid #f9a8d4;
}
.eddd {
  display: flex;
  align-items: flex-start;
  gap: 0.5em;
  font-size: 0.95em;
  line-height: 1.5;
}
</style>

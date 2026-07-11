<template>
  <div
    class="delivery-promise-lite delivery-promise-lite--preview delivery-promise-lite--"
    :class="`delivery-promise-lite--${style}`"
  >
    <span v-if="showIcon" class="delivery-promise-lite__icon" aria-hidden="true">&#128666;</span>
    <span class="delivery-promise-lite__message" v-html="safeMessage"></span>
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
.delivery-promise-lite--preview {
  margin: 0;
}
.delivery-promise-lite--plain {
  padding: 0;
  background: transparent;
  border: 0;
}
.delivery-promise-lite--highlighted {
  padding: 0.75em 1em;
  border-radius: 10px;
  background: #fce7f3;
  border: 1px solid #f9a8d4;
}
.delivery-promise-lite {
  display: flex;
  align-items: flex-start;
  gap: 0.5em;
  font-size: 0.95em;
  line-height: 1.5;
}
</style>

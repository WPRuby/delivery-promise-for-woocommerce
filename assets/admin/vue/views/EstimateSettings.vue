<template>
  <div class="wpruby-dp-view">
    <div class="wpruby-dp-metrics">
      <MetricCard icon="clock" :value="processingSummary" :label="metrics.processing" />
      <MetricCard icon="truck" :value="transitSummary" :label="metrics.transit" />
      <MetricCard icon="clock" :value="settings.cutoff_time || '—'" :label="metrics.cutoff" />
      <MetricCard icon="pin" :value="`${holidays.length} / ${maxHolidays}`" :label="metrics.excluded" />
    </div>

    <SettingsSection :title="sections.timing" :description="sections.timingDesc">
      <div class="wpruby-dp-grid-2">
        <SettingsCard :title="cards.processing" icon="clock">
          <div class="wpruby-dp-range">
            <NumberField v-model="settings.processing_min" :label="labels.min" :min="0" />
            <span class="wpruby-dp-range__sep" aria-hidden="true">–</span>
            <NumberField v-model="settings.processing_max" :label="labels.max" :min="0" />
          </div>
        </SettingsCard>

        <SettingsCard :title="cards.transit" icon="truck">
          <div class="wpruby-dp-range">
            <NumberField v-model="settings.transit_min" :label="labels.min" :min="0" />
            <span class="wpruby-dp-range__sep" aria-hidden="true">–</span>
            <NumberField v-model="settings.transit_max" :label="labels.max" :min="0" />
          </div>
        </SettingsCard>
      </div>
    </SettingsSection>

    <SettingsSection :title="sections.schedule" :description="sections.scheduleDesc">
      <div class="wpruby-dp-grid-2">
        <SettingsCard :title="cards.working" icon="calendar">
          <DayPills v-model="settings.working_days" :options="data.weekdays || []" numeric />
        </SettingsCard>

        <SettingsCard :title="cards.cutoff" icon="clock">
          <TimeField v-model="settings.cutoff_time" :label="labels.cutoff" :help="labels.cutoffHelp" />
        </SettingsCard>
      </div>
    </SettingsSection>

    <SettingsSection :title="sections.excluded" :description="sections.excludedDesc">
      <SettingsCard :title="cards.addExcluded" icon="plus">
        <div class="wpruby-dp-holiday-add">
          <div class="wpruby-dp-holiday-add__field">
            <label :for="dateId">{{ labels.date }}</label>
            <input :id="dateId" type="date" class="wpruby-dp-input--sm" v-model="newDate" />
          </div>
          <div class="wpruby-dp-holiday-add__field" style="flex: 1 1 220px;">
            <label :for="labelId">{{ labels.labelOptional }}</label>
            <input
              :id="labelId"
              type="text"
              v-model="newLabel"
              :placeholder="labels.labelPlaceholder"
              @keyup.enter="addHoliday"
            />
          </div>
          <button
            type="button"
            class="wpruby-dp-btn wpruby-dp-btn--primary"
            :disabled="holidays.length >= maxHolidays"
            @click="addHoliday"
          >
            <Icon name="plus" /> {{ labels.add }}
          </button>
        </div>
        <p v-if="error" class="wpruby-dp-field__error" style="margin-top: 8px;">{{ error }}</p>
        <p v-if="holidays.length >= maxHolidays" class="wpruby-dp-field__help" style="margin-top: 8px;">
          {{ labels.limitReached }}
        </p>
      </SettingsCard>

      <SettingsCard :title="cards.excludedList" icon="calendar">
        <EmptyState
          v-if="!holidays.length"
          icon="calendar"
          :title="emptyTitle"
          :description="emptyDesc"
        />
        <table v-else class="wpruby-dp-holiday-table">
          <thead>
            <tr>
              <th>{{ labels.date }}</th>
              <th>{{ labels.labelOptional }}</th>
              <th style="text-align:right;">{{ labels.actions }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(holiday, index) in holidays" :key="holiday.date">
              <td>{{ holiday.date }}</td>
              <td>{{ holiday.label || '—' }}</td>
              <td style="text-align:right;">
                <button type="button" class="wpruby-dp-btn wpruby-dp-btn--ghost" @click="removeHoliday(index)">
                  {{ labels.remove }}
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </SettingsCard>
    </SettingsSection>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import SettingsSection from '../components/SettingsSection.vue';
import SettingsCard from '../components/SettingsCard.vue';
import MetricCard from '../components/MetricCard.vue';
import NumberField from '../components/NumberField.vue';
import TimeField from '../components/TimeField.vue';
import DayPills from '../components/DayPills.vue';
import EmptyState from '../components/EmptyState.vue';
import Icon from '../components/Icon.vue';
import { state } from '../store.js';
import { __ } from '../api/client.js';

const settings = computed(() => state.settings || {});
const data = computed(() => state.data || {});
const maxHolidays = computed(() => data.value.maxHolidays || 5);
const holidays = computed(() => settings.value.holidays || []);

const newDate = ref('');
const newLabel = ref('');
const error = ref('');

const dateId = 'dp-lite-excluded-date';
const labelId = 'dp-lite-excluded-label';

const processingSummary = computed(() => {
  const min = settings.value.processing_min ?? 0;
  const max = settings.value.processing_max ?? 0;
  return min === max ? String(min) : `${min}–${max}`;
});

const transitSummary = computed(() => {
  const min = settings.value.transit_min ?? 0;
  const max = settings.value.transit_max ?? 0;
  return min === max ? String(min) : `${min}–${max}`;
});

const sections = {
  timing: __('Processing & transit'),
  timingDesc: __('Set how many working days you need to process orders and ship them.'),
  schedule: __('Working days & cutoff'),
  scheduleDesc: __('Choose which days count as working days and when same-day processing ends.'),
  excluded: __('Excluded dates'),
  excludedDesc: __('Optional dates when you do not dispatch or deliver (up to 5).'),
};

const cards = {
  processing: __('Processing days'),
  transit: __('Transit days'),
  working: __('Working days'),
  cutoff: __('Cutoff time'),
  addExcluded: __('Add excluded date'),
  excludedList: __('Excluded dates'),
};

const metrics = {
  processing: __('Processing'),
  transit: __('Transit'),
  cutoff: __('Cutoff'),
  excluded: __('Excluded dates'),
};

const labels = {
  min: __('Min'),
  max: __('Max'),
  cutoff: __('Cutoff time'),
  cutoffHelp: __('Orders placed after this time start processing on the next working day.'),
  date: __('Date'),
  labelOptional: __('Label (optional)'),
  labelPlaceholder: __('Holiday name'),
  add: __('Add date'),
  remove: __('Remove'),
  actions: __('Actions'),
  limitReached: __('Lite supports up to 5 excluded dates.'),
};

const emptyTitle = __('No excluded dates');
const emptyDesc = __('Add dates when your store does not dispatch or deliver.');

function addHoliday() {
  error.value = '';
  if (!newDate.value) {
    error.value = __('Please choose a date.');
    return;
  }
  if (holidays.value.length >= maxHolidays.value) {
    error.value = labels.limitReached;
    return;
  }
  if (holidays.value.some((h) => h.date === newDate.value)) {
    error.value = __('That date is already excluded.');
    return;
  }
  if (!settings.value.holidays) {
    settings.value.holidays = [];
  }
  settings.value.holidays.push({ date: newDate.value, label: newLabel.value.trim() });
  settings.value.holidays.sort((a, b) => a.date.localeCompare(b.date));
  newDate.value = '';
  newLabel.value = '';
}

function removeHoliday(index) {
  settings.value.holidays.splice(index, 1);
}
</script>

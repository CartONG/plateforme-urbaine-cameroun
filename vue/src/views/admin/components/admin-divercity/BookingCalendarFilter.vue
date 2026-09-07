<template>
  <v-menu :close-on-content-click="false" location="bottom end">
    <template v-slot:activator="{ props: menuProps }">
      <v-btn icon variant="text" v-bind="menuProps" class="ml-2">
       <v-icon icon="$calendar" :color="modelValue ? 'main-blue' : undefined" />
      </v-btn>
    </template>

    <div class="BookingCalendarFilter">
      <div class="BookingCalendarFilter__header">
        <v-btn icon variant="text" size="small" @click="goToPreviousMonth">
            <v-icon icon="$chevronLeft" />
        </v-btn>
        <span class="BookingCalendarFilter__label">{{ monthLabel }}</span>
        <v-btn icon variant="text" size="small" @click="goToNextMonth">
            <v-icon icon="$chevronRight" />
        </v-btn>
      </div>

      <div class="BookingCalendarFilter__weekdays">
        <span v-for="day in weekdayLabels" :key="day">{{ day }}</span>
      </div>

      <div class="BookingCalendarFilter__grid">
        <div
          v-for="(cell, index) in calendarCells"
          :key="index"
          class="BookingCalendarFilter__cell"
          :class="{
            'BookingCalendarFilter__cell--empty': !cell,
            'BookingCalendarFilter__cell--partial': cell && occupancy[cell.iso] === 'partial',
            'BookingCalendarFilter__cell--full': cell && occupancy[cell.iso] === 'full',
            'BookingCalendarFilter__cell--selected': cell && modelValue === cell.iso
          }"
          @click="cell && toggleDate(cell.iso)"
        >
          {{ cell?.day }}
        </div>
      </div>

      <div class="BookingCalendarFilter__legend">
        <span>
          <i class="BookingCalendarFilter__dot BookingCalendarFilter__dot--partial"></i>
          {{ $t('divercity.admin.calendar.partial') }}
        </span>
        <span>
          <i class="BookingCalendarFilter__dot BookingCalendarFilter__dot--full"></i>
          {{ $t('divercity.admin.calendar.full') }}
        </span>
      </div>

      <v-btn v-if="modelValue" variant="text" size="small" color="main-red" @click="clear">
        {{ $t('divercity.admin.calendar.clearFilter') }}
      </v-btn>
    </div>
  </v-menu>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'

const props = defineProps<{
  occupancy: Record<string, 'partial' | 'full'>
  modelValue: string | null
}>()
const emit = defineEmits(['update:modelValue'])

const today = new Date()
const viewYear = ref(today.getFullYear())
const viewMonth = ref(today.getMonth()) // 0-indexé

const weekdayLabels = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim']

const monthLabel = computed(() =>
  new Date(viewYear.value, viewMonth.value, 1).toLocaleDateString('fr-FR', {
    month: 'long',
    year: 'numeric'
  })
)

function toIso(year: number, month: number, day: number): string {
  return `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`
}

const calendarCells = computed(() => {
  const firstOfMonth = new Date(viewYear.value, viewMonth.value, 1)
  // getDay(): 0=dimanche -> décalage pour commencer la semaine le lundi
  const leadingBlanks = (firstOfMonth.getDay() + 6) % 7
  const daysInMonth = new Date(viewYear.value, viewMonth.value + 1, 0).getDate()

  const cells: ({ day: number; iso: string } | null)[] = []
  for (let i = 0; i < leadingBlanks; i++) cells.push(null)
  for (let day = 1; day <= daysInMonth; day++) {
    cells.push({ day, iso: toIso(viewYear.value, viewMonth.value, day) })
  }
  return cells
})

function goToPreviousMonth() {
  if (viewMonth.value === 0) {
    viewMonth.value = 11
    viewYear.value -= 1
  } else {
    viewMonth.value -= 1
  }
}

function goToNextMonth() {
  if (viewMonth.value === 11) {
    viewMonth.value = 0
    viewYear.value += 1
  } else {
    viewMonth.value += 1
  }
}

function toggleDate(iso: string) {
  emit('update:modelValue', props.modelValue === iso ? null : iso)
}

function clear() {
  emit('update:modelValue', null)
}
</script>

<style lang="scss">
.BookingCalendarFilter {
  padding: 1rem;
  width: 280px;
  background-color: white;

  &__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.5rem;
  }

  &__label {
    font-weight: 600;
    text-transform: capitalize;
  }

  &__weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    text-align: center;
    font-size: 0.75rem;
    color: rgb(var(--v-theme-main-grey));
    margin-bottom: 0.25rem;
  }

  &__grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    row-gap: 0.25rem;
  }

  &__cell {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 0.85rem;
    cursor: pointer;

    &--empty {
      cursor: default;
    }

    &--partial {
      background-color: rgb(var(--v-theme-light-yellow));
    }

    &--full {
      background-color: rgb(var(--v-theme-main-red));
      color: white;
    }

    &--selected {
      border: 2px solid rgb(var(--v-theme-main-blue));
    }
  }

  &__legend {
    display: flex;
    gap: 1rem;
    margin-top: 0.75rem;
    font-size: 0.75rem;
    align-items: center;
  }

  &__dot {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 4px;

    &--partial {
      background-color: rgb(var(--v-theme-light-yellow));
    }

    &--full {
      background-color: rgb(var(--v-theme-main-red));
    }
  }
}
</style>
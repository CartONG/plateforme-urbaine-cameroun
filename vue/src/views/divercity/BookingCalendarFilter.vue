<template>
  <v-menu :close-on-content-click="false" location="bottom start">
    <template v-slot:activator="{ props: menuProps }">
        <v-text-field
            density="compact"
            variant="outlined"
            readonly
            :model-value="displayValue"
            append-inner-icon="$calendar"
            v-bind="menuProps"
        />
    </template>

    <div class="BookingCalendarField">
      <div class="BookingCalendarField__header">
        <v-btn icon variant="text" size="small" @click="goToPreviousMonth">
          <v-icon icon="$chevronLeft" />
        </v-btn>
        <span class="BookingCalendarField__label">{{ monthLabel }}</span>
        <v-btn icon variant="text" size="small" @click="goToNextMonth">
          <v-icon icon="$chevronRight" />
        </v-btn>
      </div>

      <div class="BookingCalendarField__weekdays">
        <span v-for="day in weekdayLabels" :key="day">{{ day }}</span>
      </div>

      <div class="BookingCalendarField__grid">
        <div
          v-for="(cell, index) in calendarCells"
          :key="index"
          class="BookingCalendarField__cell"
          :class="{
            'BookingCalendarField__cell--empty': !cell,
            'BookingCalendarField__cell--past': cell && cell.isPast,
            'BookingCalendarField__cell--partial': cell && occupancy[cell.iso] === 'partial',
            'BookingCalendarField__cell--full': cell && occupancy[cell.iso] === 'full',
            'BookingCalendarField__cell--selected': cell && modelValue === cell.iso
          }"
          @click="cell && !cell.isPast && select(cell.iso)"
        >
          {{ cell?.day }}
        </div>
      </div>

      <div class="BookingCalendarField__legend">
        <span>
          <i class="BookingCalendarField__dot BookingCalendarField__dot--partial"></i>
          {{ $t('divercity.admin.calendar.partial') }}
        </span>
        <span>
          <i class="BookingCalendarField__dot BookingCalendarField__dot--full"></i>
          {{ $t('divercity.admin.calendar.full') }}
        </span>
      </div>
    </div>
  </v-menu>
</template>

<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import { SpacesService } from '@/services/divercity/SpacesService'

const props = defineProps<{
  spaceId: string
  modelValue: string | null
}>()
const emit = defineEmits(['update:modelValue'])

const today = new Date()
const viewYear = ref(today.getFullYear())
const viewMonth = ref(today.getMonth())

const weekdayLabels = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim']

const monthLabel = computed(() =>
  new Date(viewYear.value, viewMonth.value, 1).toLocaleDateString('fr-FR', {
    month: 'long',
    year: 'numeric'
  })
)

const displayValue = computed(() => {
  if (!props.modelValue) return ''
  const [year, month, day] = props.modelValue.split('-')
  return `${day}-${month}-${year}`
})

function toIso(year: number, month: number, day: number): string {
  return `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`
}

const calendarCells = computed(() => {
  const firstOfMonth = new Date(viewYear.value, viewMonth.value, 1)
  const leadingBlanks = (firstOfMonth.getDay() + 6) % 7
  const daysInMonth = new Date(viewYear.value, viewMonth.value + 1, 0).getDate()
  const todayIso = toIso(today.getFullYear(), today.getMonth(), today.getDate())

  const cells: ({ day: number; iso: string; isPast: boolean } | null)[] = []
  for (let i = 0; i < leadingBlanks; i++) cells.push(null)
  for (let day = 1; day <= daysInMonth; day++) {
    const iso = toIso(viewYear.value, viewMonth.value, day)
    cells.push({ day, iso, isPast: iso < todayIso })
  }
  return cells
})

const WORK_DAY_MINUTES = 540

function timeToMinutes(time: string): number {
  const [h, m] = time.split(':').map(Number)
  return h * 60 + m
}

const occupancy = ref<Record<string, 'partial' | 'full'>>({})

async function loadMonthOccupancy() {
  const monthStart = toIso(viewYear.value, viewMonth.value, 1)
  const daysInMonth = new Date(viewYear.value, viewMonth.value + 1, 0).getDate()
  const monthEnd = toIso(viewYear.value, viewMonth.value, daysInMonth)

  const slots = await SpacesService.getSpaceAvailability(props.spaceId, monthStart, monthEnd)

  const totalsByDate: Record<string, number> = {}
  for (const slot of slots) {
    const date = (slot as any).date ?? monthStart // adapte selon la forme réelle du retour
    const duration = timeToMinutes(slot.endTime) - timeToMinutes(slot.startTime)
    totalsByDate[date] = (totalsByDate[date] || 0) + Math.max(duration, 0)
  }

  const result: Record<string, 'partial' | 'full'> = {}
  for (const [date, minutes] of Object.entries(totalsByDate)) {
    result[date] = minutes >= WORK_DAY_MINUTES ? 'full' : 'partial'
  }
  occupancy.value = result
}

function select(iso: string) {
  emit('update:modelValue', iso)
}

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

watch([viewYear, viewMonth], loadMonthOccupancy, { immediate: true })
</script>

<style lang="scss">
.BookingCalendarField {
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

    &--empty { cursor: default; }
    &--past { opacity: 0.35; cursor: not-allowed; }
    &--partial { background-color: rgb(var(--v-theme-light-yellow)); }
    &--full { background-color: rgb(var(--v-theme-main-red)); color: white; }
    &--selected { border: 2px solid rgb(var(--v-theme-main-blue)); }
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

    &--partial { background-color: rgb(var(--v-theme-light-yellow)); }
    &--full { background-color: rgb(var(--v-theme-main-red)); }
  }
}
</style>
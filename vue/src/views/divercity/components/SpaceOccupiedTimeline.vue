<template>
  <div class="SpaceOccupiedTimeline">
    <template v-if="slots.length">
      <div class="SpaceOccupiedTimeline__track">
        <span
          v-for="slot in slots"
          :key="slot.id"
          class="SpaceOccupiedTimeline__slot"
          :class="`SpaceOccupiedTimeline__slot--${slot.type}`"
          :style="slotStyle(slot)"
          :title="slotTooltip(slot)"
        />
      </div>
      <div class="SpaceOccupiedTimeline__hours">
        <span v-for="h in hourMarks" :key="h">{{ h }}</span>
      </div>

      <ul class="SpaceOccupiedTimeline__details">
        <li
          v-for="slot in slots"
          :key="slot.id"
          class="SpaceOccupiedTimeline__detailItem"
          :class="`SpaceOccupiedTimeline__detailItem--${slot.type}`"
        >
          <span class="SpaceOccupiedTimeline__detailTime">{{ slot.startTime }} - {{ slot.endTime }}</span>
          <span class="SpaceOccupiedTimeline__detailLabel">
            {{ slotLabel(slot) }}
          </span>
        </li>
      </ul>

      <ul class="SpaceOccupiedTimeline__legend">
        <li class="SpaceOccupiedTimeline__legendItem SpaceOccupiedTimeline__legendItem--booking">
          {{ $t('divercity.availability.legend.booking') }}
        </li>
        <li class="SpaceOccupiedTimeline__legendItem SpaceOccupiedTimeline__legendItem--blocked_period">
          {{ $t('divercity.availability.legend.blockedPeriod') }}
        </li>
      </ul>
    </template>
    <p v-else class="SpaceOccupiedTimeline__empty">
      {{ $t('divercity.availability.dayFree') }}
    </p>
  </div>
</template>

<script setup lang="ts">
import type { SpaceAvailability } from '@/models/interfaces/divercity/Booking'
import { i18n } from '@/plugins/i18n'
import { computed } from 'vue'

const props = withDefaults(
  defineProps<{
    slots: SpaceAvailability[]
    dayStartHour?: number
    dayEndHour?: number
  }>(),
  {
    dayStartHour: 8,
    dayEndHour: 18
  }
)

const totalMinutes = computed(() => (props.dayEndHour - props.dayStartHour) * 60)

function toMinutes(time: string): number {
  const [h, m] = time.split(':').map(Number)
  return h * 60 + m
}

function slotStyle(slot: SpaceAvailability) {
  const rangeStart = props.dayStartHour * 60
  const start = Math.min(Math.max(toMinutes(slot.startTime) - rangeStart, 0), totalMinutes.value)
  const end = Math.min(Math.max(toMinutes(slot.endTime) - rangeStart, 0), totalMinutes.value)
  const left = (start / totalMinutes.value) * 100
  const width = Math.max(((end - start) / totalMinutes.value) * 100, 1)
  return { left: `${left}%`, width: `${width}%` }
}

function typeLabel(slot: SpaceAvailability): string {
  return slot.type === 'blocked_period'
    ? i18n.t('divercity.availability.legend.blockedPeriod')
    : i18n.t('divercity.availability.legend.booking')
}

// Titre/motif si renseigné, sinon on retombe sur le libellé générique du type
function slotLabel(slot: SpaceAvailability): string {
  if (slot.type === 'booking' && slot.eventActivityTypeLabel) {
    return slot.title ? `${slot.title} — ${slot.eventActivityTypeLabel}` : slot.eventActivityTypeLabel
  }
  return slot.title || typeLabel(slot)
}

function slotTooltip(slot: SpaceAvailability): string {
  return `${slotLabel(slot)} : ${slot.startTime} - ${slot.endTime}`
}

const hourMarks = computed(() => {
  const marks: string[] = []
  const step = props.dayEndHour - props.dayStartHour > 10 ? 3 : 2
  for (let h = props.dayStartHour; h <= props.dayEndHour; h += step) {
    marks.push(`${h.toString().padStart(2, '0')}h`)
  }
  return marks
})
</script>

<style lang="scss" scoped>
.SpaceOccupiedTimeline {
  margin-top: 1rem;

  &__track {
    position: relative;
    height: 2.5rem;
    background: rgb(var(--v-theme-main-grey), 0.15);
    border-radius: $dim-radius;
    overflow: hidden;
  }

  &__slot {
    position: absolute;
    top: 0.35rem;
    bottom: 0.35rem;
    border-radius: 0.25rem;
    cursor: default;

    &--booking {
      background: rgb(var(--v-theme-main-red));
    }

    &--blocked_period {
      background: rgb(var(--v-theme-main-blue));
    }
  }

  &__hours {
    display: flex;
    justify-content: space-between;
    margin-top: 0.35rem;
    font-size: $font-size-xs;
    color: rgb(var(--v-theme-main-grey));
  }

  &__details {
    display: flex;
    flex-flow: column nowrap;
    gap: 0.5rem;
    margin-top: 1rem;
    padding: 0;
    list-style: none;
  }

  &__detailItem {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: $font-size-sm;
    padding-left: 0.75rem;
    border-left: 3px solid transparent;

    &--booking {
      border-left-color: rgb(var(--v-theme-main-red));
    }

    &--blocked_period {
      border-left-color: rgb(var(--v-theme-main-blue));
    }
  }

  &__detailTime {
    font-weight: 700;
    white-space: nowrap;
  }

  &__detailLabel {
    color: rgb(var(--v-theme-main-grey-dark));
  }

  &__legend {
    display: flex;
    gap: 1rem;
    margin-top: 0.75rem;
    font-size: $font-size-xs;
    list-style: none;
    padding: 0;
  }

  &__legendItem {
    display: flex;
    align-items: center;
    gap: 0.35rem;

    &::before {
      content: '';
      display: inline-block;
      width: 0.6rem;
      height: 0.6rem;
      border-radius: 0.15rem;
    }

    &--booking::before {
      background: rgb(var(--v-theme-main-red));
    }

    &--blocked_period::before {
      background: rgb(var(--v-theme-main-blue));
    }
  }

  &__empty {
    font-size: $font-size-sm;
    color: rgb(var(--v-theme-main-grey));
  }
}
</style>
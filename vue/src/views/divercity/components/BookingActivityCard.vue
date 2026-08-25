<template>
  <GenericInfoCard
    :id="booking.id"
    :title="booking.title"
    :description="booking.organization"
    :type-label="activityTypeLabel"
    hide-actions
    hide-action-icon
    disable-hover-effect
    class="BookingActivityCard"
    :class="{ 'BookingActivityCard--compact': compact }"
  >
    <template #image>
      <div class="BookingActivityCard__dateBanner">
        <span class="BookingActivityCard__date">{{ date }}</span>
        <span class="BookingActivityCard__month">{{ month }}</span>
      </div>
    </template>

    <template #description>
      <span class="InfoCard__title">{{ booking.title }}</span>
      <span class="InfoCard__subTitle">
        <span>
          <v-icon icon="$calendar" />
          <span>{{ formattedDate }}</span>
        </span>
        <span>
          <v-icon icon="$clockOutline" />
          <span>{{ booking.startTimeFormat }} - {{ booking.endTimeFormat }}</span>
        </span>
        <span v-if="booking.participantCount">
          <v-icon icon="$accountGroup" />
          <span>{{ booking.participantCount }}</span>
        </span>
      </span>
      <span class="InfoCard__description">
        <div v-if="!compact && booking.organization">{{ booking.organization }}</div>
      </span>
    </template>

    <!-- <template #footer-right v-if="activityTypeLabel">
      <span :title="fullActivityTypeLabel">{{ activityTypeLabel }}</span>
    </template> -->
  </GenericInfoCard>
</template>

<script setup lang="ts">
import GenericInfoCard from '@/components/global/GenericInfoCard.vue'
import type { PublicBooking } from '@/models/interfaces/divercity/Booking'
import { localizeDate } from '@/services/utils/UtilsService'
import { computed } from 'vue'


const props = withDefaults(
  defineProps<{
    booking: PublicBooking
    compact?: boolean
  }>(),
  {
    compact: false
  }
)
const formattedDate = computed(() => localizeDate(props.booking.date))
const date = computed(() => new Date(props.booking.date).getDate())
const month = computed(() => localizeDate(props.booking.date, { month: 'short' }))
const fullActivityTypeLabel = computed(() => {
  const type = props.booking.eventActivityType as any
  return type?.label ?? null
})
const activityTypeLabel = computed(() => {
  const label = fullActivityTypeLabel.value
  if (!label) return null
  return label.length > 21 ? label.slice(0, 21) + '...' : label
})
</script>

<style lang="scss">
.BookingActivityCard {
  .InfoCard__subTitle {
    font-weight: 500 !important;
  }
  .InfoCard__description {
    display: flex;
    flex-flow: column nowrap;
    gap: 0.5rem;
    padding-bottom: 0.5rem;
  }
  .BookingActivityCard__dateBanner {
    background: rgb(var(--v-theme-main-yellow));
    color: rgb(var(--v-theme-main-blue));
    display: flex;
    flex-flow: column nowrap;
    align-items: center;
    padding: 1rem;
    position: absolute;
    min-width: 5.25rem;
    top: 0;
    right: 1rem;
    border-radius: 0 0 $dim-radius $dim-radius;

    .BookingActivityCard__date {
      font-weight: 700;
      font-size: 2.25rem;
      line-height: 2.25rem;
    }
    .BookingActivityCard__month {
      font-size: 1.25rem;
      text-transform: uppercase;
    }
  }
  .BookingActivityCard__badge {
    background: rgb(var(--v-theme-main-blue));
    color: white;
    font-size: $font-size-xs;
    font-weight: 700;
    text-transform: uppercase;
    padding: 0.25rem 0.75rem;
    border-radius: 999px;
  }

  &--compact {
    .InfoCard__title {
      font-size: $font-size-h6;
    }
  }
}
</style>
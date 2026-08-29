<template>
  <div class="SpaceAvailabilityView" v-if="space">
    <PageTitle :title="$t('divercity.availability.title')" />

    <div class="SpaceAvailabilityView__layout">
      <div class="SpaceAvailabilityView__calendar">
        <v-date-picker
          v-model="selectedDate"
          :attributes="calendarAttributes"
          @update:model-value="onDaySelected"
          hide-header
          :min="today"
        />
        <div class="SpaceAvailabilityView__legend">
          <span class="SpaceAvailabilityView__legendItem SpaceAvailabilityView__legendItem--free">
            {{ $t('divercity.availability.legend.free') }}
          </span>
          <span class="SpaceAvailabilityView__legendItem SpaceAvailabilityView__legendItem--partial">
            {{ $t('divercity.availability.legend.partial') }}
          </span>
          <span class="SpaceAvailabilityView__legendItem SpaceAvailabilityView__legendItem--full">
            {{ $t('divercity.availability.legend.full') }}
          </span>
        </div>
        <div class="SpaceAvailabilityView__timelineCtn" v-if="selectedDate">
            <p class="SpaceAvailabilityView__timelineLabel">
                {{ $t('divercity.availability.timeline.title') }}
            </p>
            <v-progress-circular v-if="isLoadingDay" indeterminate color="main-blue" size="20" />
            <SpaceOccupiedTimeline v-else :slots="selectedDaySlots" />
        </div>
      </div>

      <div class="SpaceAvailabilityView__dayDetail" v-if="selectedDate">
        <h3>{{ formattedSelectedDate }}</h3>

        <v-progress-circular v-if="isLoadingDay" indeterminate color="main-blue" />

        <template v-else>
          <p v-if="!selectedDaySlots.length" class="text-success">
            {{ $t('divercity.availability.dayFree') }}
          </p>
          <ul v-else class="SpaceAvailabilityView__occupiedList">
            <li v-for="slot in selectedDaySlots" :key="slot.id">
              {{ slot.startTime }} - {{ slot.endTime }}
              <span v-if="slot.type === 'blocked_period'">
                ({{ $t('divercity.availability.legend.blockedPeriod') }})
              </span>
            </li>
          </ul>

          <v-divider class="my-4" />

          <div class="SpaceAvailabilityView__slotPickerCtn">
            <p class="Form__label">{{ $t('divercity.availability.pickSlot') }}</p>
            <div class="SpaceAvailabilityView__slotPicker">
              <v-text-field
                type="time"
                density="compact"
                variant="outlined"
                v-model="pickedStartTime"
                :label="$t('divercity.booking.fields.startTime')"
              />
              <v-text-field
                type="time"
                density="compact"
                variant="outlined"
                v-model="pickedEndTime"
                :label="$t('divercity.booking.fields.endTime')"
              />
            </div>
            <v-alert v-if="pickedSlotConflict" type="warning" variant="tonal" density="compact">
              {{ $t('divercity.booking.availability.conflict') }}
            </v-alert>
            <v-btn
              color="main-red"
              :disabled="!pickedStartTime || !pickedEndTime || pickedSlotConflict"
              @click="bookThisSlot"
            >
              {{ $t('divercity.availability.bookThisSlot') }}
            </v-btn>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import PageTitle from '@/components/text-elements/PageTitle.vue'
import type { SpaceAvailability } from '@/models/interfaces/divercity/Booking'
import { SpacesService } from '@/services/divercity/SpacesService'
import { useApplicationStore } from '@/stores/applicationStore'
import { useSpacesStore } from '@/stores/divercity/spacesStore'
import { i18n } from '@/plugins/i18n'
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import SpaceOccupiedTimeline from '@/views/divercity/components/SpaceOccupiedTimeline.vue'

const applicationStore = useApplicationStore()
const spacesStore = useSpacesStore()
const router = useRouter()

const space = computed(() => spacesStore.mainSpace)
const today = new Date()

const selectedDate = ref<Date | null>(null)
const isLoadingDay = ref(false)
const selectedDaySlots = ref<SpaceAvailability[]>([])

// Cache mensuel : toutes les indisponibilités du mois affiché, pour colorer le calendrier
const monthAvailability = ref<SpaceAvailability[]>([])

const pickedStartTime = ref('')
const pickedEndTime = ref('')

onMounted(async () => {
  await loadMonth(today)
  applicationStore.isLoading = false
})

async function loadMonth(reference: Date) {
  if (!space.value) return
  const from = new Date(reference.getFullYear(), reference.getMonth(), 1)
  const to = new Date(reference.getFullYear(), reference.getMonth() + 1, 0)
  monthAvailability.value = await SpacesService.getSpaceAvailability(
    space.value.id,
    toISODate(from),
    toISODate(to)
  )
}

function toISODate(date: Date): string {
  const year = date.getFullYear()
  const month = String(date.getMonth() + 1).padStart(2, '0')
  const day = String(date.getDate()).padStart(2, '0')
  return `${year}-${month}-${day}`
}

// Regroupe les indisponibilités par jour, pour déterminer la couleur du calendrier
const availabilityByDay = computed(() => {
  const map = new Map<string, SpaceAvailability[]>()
  for (const slot of monthAvailability.value) {
    const key = slot.date.split('T')[0]
    if (!map.has(key)) map.set(key, [])
    map.get(key)!.push(slot)
  }
  return map
})

// Seuil arbitraire : au-delà de 6h cumulées d'indisponibilité sur un jour, on le considère "complet"
const FULL_DAY_THRESHOLD_MINUTES = 360

function slotMinutes(slot: SpaceAvailability): number {
  const [sh, sm] = slot.startTime.split(':').map(Number)
  const [eh, em] = slot.endTime.split(':').map(Number)
  return eh * 60 + em - (sh * 60 + sm)
}

function dayStatus(dateKey: string): 'free' | 'partial' | 'full' {
  const slots = availabilityByDay.value.get(dateKey)
  if (!slots || slots.length === 0) return 'free'
  const totalMinutes = slots.reduce((sum, s) => sum + slotMinutes(s), 0)
  return totalMinutes >= FULL_DAY_THRESHOLD_MINUTES ? 'full' : 'partial'
}

// Attributs de couleur pour v-date-picker (Vuetify Labs)
const calendarAttributes = computed(() => {
  return Array.from(availabilityByDay.value.keys()).map((dateKey) => ({
    dates: [new Date(dateKey)],
    dot: {
      color: dayStatus(dateKey) === 'full' ? 'main-red' : 'main-yellow'
    }
  }))
})

async function onDaySelected(date: Date | null) {
  if (!date || !space.value) {
    selectedDaySlots.value = []
    return
  }
  isLoadingDay.value = true
  pickedStartTime.value = ''
  pickedEndTime.value = ''
  try {
    const iso = toISODate(date)
    selectedDaySlots.value = await SpacesService.getSpaceAvailability(space.value.id, iso, iso)
  } finally {
    isLoadingDay.value = false
  }
}

const formattedSelectedDate = computed(() => {
  if (!selectedDate.value) return ''
  return selectedDate.value.toLocaleDateString('fr-FR', {
    weekday: 'long',
    day: 'numeric',
    month: 'long'
  })
})

const pickedSlotConflict = computed(() => {
  if (!pickedStartTime.value || !pickedEndTime.value) return false
  return selectedDaySlots.value.some(
    (slot) => pickedStartTime.value < slot.endTime && slot.startTime < pickedEndTime.value
  )
})

function bookThisSlot() {
  if (!selectedDate.value) return
  spacesStore.preselectedSlot = {
    date: toISODate(selectedDate.value),
    startTime: pickedStartTime.value,
    endTime: pickedEndTime.value
  }
  router.push({ name: 'divercitySpaceBooking' })
}
</script>

<style lang="scss">
.SpaceAvailabilityView {
  max-width: $dim-container-w;
  margin: 4rem auto;

  &__timelineCtn {
    margin-top: 1.5rem;
  }

  &__timelineLabel {
    font-weight: 700;
    font-size: $font-size-sm;
    margin-bottom: 0.5rem;
 }

  &__layout {
    display: grid;
    grid-template-columns: minmax(20rem, 24rem) 1fr;
    gap: 2rem;
    align-items: start;
  }

  &__legend {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
    font-size: $font-size-xs;
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
      border-radius: 50%;
    }

    &--free::before {
      background: rgb(var(--v-theme-main-grey));
    }
    &--partial::before {
      background: rgb(var(--v-theme-main-yellow));
    }
    &--full::before {
      background: rgb(var(--v-theme-main-red));
    }
  }

  &__dayDetail {
    border: 1px solid rgb(var(--v-theme-main-grey));
    border-radius: $dim-radius;
    padding: 1.5rem;
  }

  &__occupiedList {
    display: flex;
    flex-flow: column nowrap;
    gap: 0.5rem;
  }

  &__slotPickerCtn {
    display: flex;
    flex-flow: column nowrap;
    gap: 1rem;
  }

  &__slotPicker {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;

    > * {
      flex: 1 1 10rem;
      min-width: 9rem;
    }
  }
}

@media (max-width: $bp-xl) {
  .SpaceAvailabilityView__layout {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 400px) {
  .SpaceAvailabilityView__slotPicker > * {
    flex-basis: 100%;
  }
}
</style>
<template>
  <v-date-input
    density="compact"
    variant="outlined"
    v-model="date"
    :value="dateValueLabel"
    prepend-icon=""
    hide-details="auto"
    prepend-inner-icon="$calendar"
    multiple="range"
    @blur="handleDateChange"
  ></v-date-input>
</template>

<script lang="ts" setup>
import { getDateDiff, getDateRangeLabel } from '@/services/utils/UtilsService'
import { computed, onMounted, ref, watch, type Ref } from 'vue'

const startAt = defineModel<Date | null | undefined | any>('startAt')
const endAt = defineModel<Date | null | undefined | any>('endAt')

const date: Ref<Date[]> = ref([])

const dateValueLabel = computed(() => {
  if (date.value.length === 0) return ''
  return getDateRangeLabel(date.value[0], date.value[date.value.length - 1])
})

onMounted(async () => {
  initDateValue()
})

watch([startAt, endAt], () => {
  initDateValue()
})

const initDateValue = () => {
  if (!startAt.value) return
  date.value = [startAt.value ? new Date(startAt.value) : null].filter((d) => d != null)
  const dateToAdd = new Date(startAt.value)
  if (endAt.value) {
    do {
      dateToAdd.setDate(dateToAdd.getDate() + 1)
      date.value.push(new Date(dateToAdd))
    } while (getDateDiff(dateToAdd, new Date(endAt.value)) < 0)
  }
}
const handleDateChange = () => {
  startAt.value = date.value[0] ?? null
  endAt.value = date.value.length > 1 ? (date.value[date.value.length - 1] ?? null) : null
}
</script>

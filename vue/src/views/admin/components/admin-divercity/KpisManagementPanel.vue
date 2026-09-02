<template>
  <div class="KpisManagementPanel">
    <div class="KpisManagementPanel__header">
      

      <div class="KpisManagementPanel__filters">
        <v-text-field
          type="date"
          v-model="from"
          :label="$t('divercity.admin.kpis.from')"
          density="compact"
          variant="outlined"
          hide-details
          class="KpisManagementPanel__dateField"
        />
        <span class="KpisManagementPanel__arrow">→</span>
        <v-text-field
          type="date"
          v-model="to"
          :label="$t('divercity.admin.kpis.to')"
          density="compact"
          variant="outlined"
          hide-details
          class="KpisManagementPanel__dateField"
        />
      </div>
    </div>

    <transition name="fade" mode="out-in">
      <div class="KpisManagementPanel__content" v-if="kpisStore.kpis" key="content">
        <section
          class="KpisManagementPanel__section"
          v-for="group in kpiGroups"
          :key="group.key"
        >
          <SectionBanner :text="$t(group.titleKey)" />
          <div class="KpisManagementPanel__grid">
            <DiverCityStaticKpi
              v-for="kpi in group.items"
              :key="kpi.key"
              :label="$t(kpi.labelKey)"
              :value="kpi.value"
              :icon="kpi.icon"
            />
          </div>
        </section>
      </div>

      <div class="KpisManagementPanel__loading" v-else-if="kpisStore.isLoading" key="loading">
        <v-progress-circular indeterminate color="main-blue" size="36" width="3" />
        <span class="KpisManagementPanel__loadingText">{{ $t('divercity.admin.kpis.loading') }}</span>
      </div>

      <div class="KpisManagementPanel__empty" v-else key="empty">
        {{ $t('divercity.admin.kpis.empty') }}
      </div>
    </transition>
  </div>
</template>

<script setup lang="ts">
import SectionTitle from '@/components/text-elements/SectionTitle.vue'
import SectionBanner from '@/components/banners/SectionBanner.vue'
import DiverCityStaticKpi from '@/components/content/DiverCityStaticKpi.vue'
import { useKpisStore } from '@/stores/divercity/kpisStore'
import { useSpacesStore } from '@/stores/divercity/spacesStore'
import { computed, onMounted, ref, watch } from 'vue'

const kpisStore = useKpisStore()
const spacesStore = useSpacesStore()

const from = ref(new Date(Date.now() - 30 * 86400000).toISOString().slice(0, 10))
const to = ref(new Date().toISOString().slice(0, 10))

const load = async () => {
  if (spacesStore.mainSpace) {
    await kpisStore.getKpis(spacesStore.mainSpace.id, from.value, to.value)
  }
}

onMounted(load)
watch([from, to], load)

const formatPercent = (value: number) => `${Math.round(value * 100)}%`

// Regroupement en 3 blocs thématiques plutôt qu'une grille plate de 9
// cartes, pour une lecture plus rapide et hiérarchisée.
const kpiGroups = computed(() => {
  const k = kpisStore.kpis
  if (!k) return []

  return [
    {
      key: 'bookings',
      titleKey: 'divercity.admin.kpis.groups.bookings',
      items: [
        { key: 'bookingsCount', labelKey: 'divercity.admin.kpis.bookingsCount', value: k.bookingsCount, icon: '$calendar' },
        { key: 'acceptedBookingsCount', labelKey: 'divercity.admin.kpis.acceptedBookingsCount', value: k.acceptedBookingsCount, icon: '$check' },
        { key: 'pendingBookingsCount', labelKey: 'divercity.admin.kpis.pendingBookingsCount', value: k.pendingBookingsCount, icon: '$clockOutline' }
      ]
    },
    {
      key: 'rates',
      titleKey: 'divercity.admin.kpis.groups.rates',
      items: [
        { key: 'occupancyRate', labelKey: 'divercity.admin.kpis.occupancyRate', value: formatPercent(k.occupancyRate), icon: '$chartBar' },
        { key: 'cancelledRate', labelKey: 'divercity.admin.kpis.cancelledRate', value: formatPercent(k.cancelledRate), icon: '$close' },
        { key: 'averageLeadTimeHours', labelKey: 'divercity.admin.kpis.averageLeadTimeHours', value: `${k.averageLeadTimeHours ?? '-'}h`, icon: '$clockOutline' }
      ]
    },
    {
      key: 'users',
      titleKey: 'divercity.admin.kpis.groups.users',
      items: [
        { key: 'activeUsersCount', labelKey: 'divercity.admin.kpis.activeUsersCount', value: k.activeUsersCount, icon: '$accountCircle' },
        { key: 'repeatUsersRate', labelKey: 'divercity.admin.kpis.repeatUsersRate', value: formatPercent(k.repeatUsersRate), icon: '$accountCircle' },
        { key: 'spaceAdminsCount', labelKey: 'divercity.admin.kpis.spaceAdminsCount', value: k.spaceAdminsCount, icon: '$accountCircle' }
      ]
    }
  ]
})
</script>

<style lang="scss">
.KpisManagementPanel {
  display: flex;
  flex-direction: column;
  gap: 2.5rem;

  &__header {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
  }

  &__filters {
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  &__dateField {
    max-width: 165px;
  }

  &__arrow {
    color: rgb(var(--v-theme-dark-grey));
    font-size: $font-size-h5;
  }

  &__content {
    display: flex;
    flex-direction: column;
    gap: 2.5rem;
  }

  &__section {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
  }

  &__grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
  }

  &__loading,
  &__empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 3rem 1rem;
    color: rgb(var(--v-theme-dark-grey));
  }

  &__loadingText {
    font-size: $font-size-sm;
  }
}

@media (max-width: $bp-lg) {
  .KpisManagementPanel {
    &__grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }
}

@media (max-width: $bp-md) {
  .KpisManagementPanel {
    &__grid {
      grid-template-columns: 1fr;
    }
  }
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
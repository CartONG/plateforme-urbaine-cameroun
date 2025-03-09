<template>
  <div class="AdminPanel">
    <AdminTopBar
      page="Comments"
      :items="commentStore.comments.filter((x) => x.origin === origin)"
      :sortingListItems="[
        { sortingKey: 'readByAdmin', text: 'Non lus en premiers' },
        { sortingKey: 'nameAZ', text: 'Nom A-Z' },
        { sortingKey: 'nameZA', text: 'Nom Z-A' }
      ]"
      searchKey="name"
      @updateSortingKey="sortingCommentsSelectedMethod = $event"
      @update-search-query="searchQuery = $event"
    >
      <template #right-buttons>
        <v-btn
          prepend-icon="mdi-folder-edit-outline"
          color="main-red"
          :disabled="selectedComments.length === 0"
        >
          {{ $t('admin.comments.actionsButton') }}
          <v-menu activator="parent" location="left">
            <v-list>
              <v-list-item @click="markAsRead()">
                <v-icon icon="mdi mdi-email-open-outline"></v-icon>
                <span class="ml-4">{{ $t('admin.comments.markAsRead') }}</span>
              </v-list-item>
              <v-list-item @click="deleteComments()">
                <v-icon icon="mdi mdi-delete-outline"></v-icon>
                <span class="ml-4">{{ $t('admin.comments.delete') }}</span>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-btn>
      </template>
    </AdminTopBar>
    <div v-for="comment in filteredItems" :key="comment.id">
      <div
        class="AdminTable__row h-auto"
        :class="{ 'AdminTable__row--overlay': !comment.readByAdmin }"
        :style="{ gridTemplateColumns: ['5%', '20%', 'auto', '15%', '5%'].join(' ') }"
      >
        <div class="AdminTable__item d-flex align-center">
          <v-checkbox
            :value="comment.id"
            v-model="selectedComments"
            hide-details
            @update:model-value="toggleSelection(comment.id)"
          >
          </v-checkbox>
        </div>
        <div class="AdminTable__item d-flex align-center pl-3 text-wrap">
          <span class="text-uppercase font-weight-bold">{{ comment.createdBy.lastName }}</span>
          <span class="ml-2">{{ comment.createdBy.firstName }}</span>
        </div>
        <div class="AdminTable__item d-flex align-center pl-3 text-wrap">
          <p>{{ comment.message }}</p>
        </div>
        <div class="AdminTable__item d-flex align-center justify-center">
          {{ new Date(comment.createdAt).toLocaleDateString() }}
        </div>
        <div class="AdminTable__item d-flex align-center justify-center">
          <template v-if="comment.originURL">
            <v-icon icon="mdi-magnify" @click="showEntity(comment.originURL)"></v-icon>
          </template>
          <template v-else-if="comment.location">
            <v-icon icon="mdi-map" @click="showMap(comment.location, comment.message)"></v-icon>
          </template>
        </div>
      </div>
    </div>
  </div>

  <MapCommentVisualiser
    :coords="activeLocation"
    :comment="commentText"
    v-if="showMapModal && activeLocation && commentText"
    @close="showMapModal = false"
  ></MapCommentVisualiser>
</template>
<script setup lang="ts">
import AdminTopBar from '@/components/admin/AdminTopBar.vue'
import type {
  AdminCommentsSortingValues,
  AppComment,
  CommentOrigin
} from '@/models/interfaces/Comment'
import { useCommentStore } from '@/stores/commentStore'
import { computed, ref, watch, type Ref } from 'vue'
import MapCommentVisualiser from './MapCommentVisualiser.vue'
const commentStore = useCommentStore()
const props = defineProps<{ origin: CommentOrigin }>()

const sortingCommentsSelectedMethod: Ref<AdminCommentsSortingValues> = ref('readByAdmin')
const sortedComments: Ref<AppComment[]> = ref(
  commentStore.getSortedComments(props.origin, sortingCommentsSelectedMethod.value)
)
watch(
  () => sortingCommentsSelectedMethod.value,
  () => {
    sortedComments.value = commentStore.getSortedComments(
      props.origin,
      sortingCommentsSelectedMethod.value
    )
  }
)
watch(
  () => commentStore.comments,
  () => {
    sortedComments.value = commentStore.getSortedComments(
      props.origin,
      sortingCommentsSelectedMethod.value
    )
  },
  { immediate: true, deep: true }
)
const searchQuery = ref('')
const filteredItems = computed(() => {
  if (!searchQuery.value) {
    return sortedComments.value
  }
  return sortedComments.value.filter((item: AppComment) => {
    return (
      item.message.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      item.createdBy.lastName?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      item.createdBy.firstName?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      item.createdBy.email?.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  })
})

const selectedComments: Ref<string[]> = ref([])
const toggleSelection = (commentID: string) => {
  if (selectedComments.value.includes(commentID)) {
    selectedComments.value.push(commentID)
  } else {
    selectedComments.value = selectedComments.value.filter((id) => id !== commentID)
  }
}
function markAsRead() {
  commentStore.markAsRead(selectedComments.value)
  selectedComments.value = []
}
function deleteComments() {
  commentStore.deleteComments(selectedComments.value)
  selectedComments.value = []
}
function showEntity(url: string) {
  window.open(url, '_blank')
}
const showMapModal = ref(false)
const activeLocation: Ref<string | null> = ref(null)
const commentText: Ref<string | null> = ref(null)
function showMap(location: string, message: string) {
  commentText.value = message
  activeLocation.value = location
  showMapModal.value = true
}
</script>

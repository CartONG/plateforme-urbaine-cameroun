<template>
  <AdminCommentsContent :origin="CommentOrigin.PROJECT" />
</template>
<script setup lang="ts">
import { CommentOrigin } from '@/models/interfaces/Comment'
import AdminCommentsContent from './AdminCommentsContent.vue'
</script>

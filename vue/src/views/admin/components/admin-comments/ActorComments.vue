<template>
  <AdminCommentsContent :origin="CommentOrigin.ACTOR" />
</template>
<script setup lang="ts">
import { CommentOrigin } from '@/models/interfaces/Comment'
import AdminCommentsContent from './AdminCommentsContent.vue'
</script>

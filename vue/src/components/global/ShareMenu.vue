<template>
  <v-menu activator="parent" :location="location">
    <v-list>
      <v-list-item @click="shareOnWhatsapp()">
        <v-icon icon="mdi mdi-whatsapp"></v-icon>
        <span class="ml-4">{{ $t('share.shareWhatsapp') }}</span>
      </v-list-item>
      <v-list-item @click="shareOnFacebook()">
        <v-icon icon="mdi mdi-facebook"></v-icon>
        <span class="ml-4">{{ $t('share.shareFb') }}</span>
      </v-list-item>
      <v-list-item @click="shareByEmail()">
        <v-icon icon="mdi mdi-email-plus-outline"></v-icon>
        <span class="ml-4">{{ $t('share.shareEmail') }}</span>
      </v-list-item>
      <v-list-item @click="shareWithLink(url)">
        <v-icon icon="mdi mdi-content-copy"></v-icon>
        <span class="ml-4">{{ $t('share.copyLink') }}</span>
      </v-list-item>
    </v-list>
  </v-menu>
</template>
<script setup lang="ts">
import { NotificationType } from '@/models/enums/app/NotificationType'
import { i18n } from '@/plugins/i18n'
import { addNotification } from '@/services/notifications/NotificationService'

const props = defineProps<{
  location: 'bottom' | 'top' | 'left' | 'right'
  url: string
  body: string
}>()

async function shareWithLink(url: string) {
  await navigator.clipboard.writeText(url)
  addNotification(i18n.t('share.urlCopied'), NotificationType.SUCCESS)
}

function shareByEmail() {
  const mailto = `mailto:?subject=${encodeURIComponent(i18n.t('share.mailSubject'))}&body=${encodeURIComponent(props.body)}`
  window.location.href = mailto
}

function shareOnWhatsapp() {
  const whatsappLink = `https://wa.me/?text=${props.url}`
  window.open(whatsappLink, '_blank')
}

function shareOnFacebook() {
  const facebookShareUrl = `https://www.facebook.com/sharer/sharer.php?u=${props.url}`
  window.open(facebookShareUrl, '_blank', 'width=600,height=400')
}
</script>

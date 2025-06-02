<template>
  <div class="TextEditor">
    <div class="TextEditor__toolbar">
      <v-btn
        :class="{ 'bg-main-blue': editor?.isActive('bold') }"
        @click.stop="toggleBold"
        size="x-small"
      >
        <v-icon>mdi-format-bold</v-icon>
      </v-btn>

      <v-btn
        :class="{ 'bg-main-blue': editor?.isActive('italic') }"
        @click.stop="toggleItalic"
        size="x-small"
      >
        <v-icon>mdi-format-italic</v-icon>
      </v-btn>

      <v-btn
        :class="{ 'bg-main-blue': editor?.isActive('strike') }"
        @click.stop="toggleStrike"
        size="x-small"
      >
        <v-icon>mdi-format-strikethrough</v-icon>
      </v-btn>

      <v-menu>
        <template v-slot:activator="{ props }">
          <v-btn :class="{ 'bg-main-blue': isHeadingActive }" v-bind="props" size="x-small">
            H
          </v-btn>
        </template>
        <v-list>
          <v-list-item
            @click="toggleHeading(1)"
            :class="{ 'text-main-blue': isH1Active, 'font-weight-bold': isH1Active }"
          >
            H1
          </v-list-item>
          <v-list-item
            @click="toggleHeading(2)"
            :class="{ 'text-main-blue': isH2Active, 'font-weight-bold': isH2Active }"
          >
            H2
          </v-list-item>
          <v-list-item
            @click="toggleHeading(3)"
            :class="{ 'text-main-blue': isH3Active, 'font-weight-bold': isH3Active }"
          >
            H3
          </v-list-item>
        </v-list>
      </v-menu>
    </div>
    <editor-content :editor="editor" class="editor-content" />
  </div>
</template>

<script setup lang="ts">
import StarterKit from '@tiptap/starter-kit'

import { Editor, EditorContent } from '@tiptap/vue-3'
import { computed, onBeforeUnmount, ref } from 'vue'

// Init Editor instance
const editor = ref<Editor | null>(null)

editor.value = new Editor({
  extensions: [StarterKit],
  content: '<p>Écris quelque chose ici…</p>'
})

// Toggle functions
const toggleBold = () => editor.value?.chain().focus().toggleBold().run()
const toggleItalic = () => editor.value?.chain().focus().toggleItalic().run()
const toggleStrike = () => editor.value?.chain().focus().toggleStrike().run()
const toggleHeading = (level: any) => editor.value?.chain().focus().toggleHeading({ level }).run()

const isHeadingActive = computed(() => {
  return (
    editor.value.isActive('heading', { level: 1 }) ||
    editor.value.isActive('heading', { level: 2 }) ||
    editor.value.isActive('heading', { level: 3 })
  )
})
const isH1Active = computed(() => editor.value.isActive('heading', { level: 1 }))
const isH2Active = computed(() => editor.value.isActive('heading', { level: 2 }))
const isH3Active = computed(() => editor.value.isActive('heading', { level: 3 }))

onBeforeUnmount(() => {
  editor.value?.destroy()
})
</script>

<style>
.TextEditor {
  display: flex;
  flex-direction: column;
}

.TextEditor__toolbar {
  display: flex;
  flex-direction: row;
  width: 100%;
  gap: 0.2rem;
  justify-content: center;
}
</style>

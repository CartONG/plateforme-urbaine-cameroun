<template>
  <div class="TextEditor" :class="{ 'TextEditor--error': errorStatus }">
    <div class="TextEditor__toolbar">
      <v-btn
        :class="{ 'bg-main-blue': editor?.isActive('bold') }"
        @click.stop="toggleBold"
        size="x-small"
      >
        G
      </v-btn>

      <v-btn
        :class="{ 'bg-main-blue': editor?.isActive('italic') }"
        @click.stop="toggleItalic"
        size="x-small"
      >
        <v-icon icon="$formatItalic"></v-icon>
      </v-btn>

      <v-btn
        :class="{ 'bg-main-blue': editor?.isActive('strike') }"
        @click.stop="toggleStrike"
        size="x-small"
      >
        <span class="text-decoration-line-through">ABC</span>
      </v-btn>

      <v-menu>
        <template v-slot:activator="{ props }">
          <v-btn :class="{ 'bg-main-blue': isHeadingActive }" v-bind="props" size="x-small">
            T <v-icon icon="$menuDown"></v-icon>
          </v-btn>
        </template>
        <v-list>
          <v-list-item
            @click="toggleHeading(1)"
            :class="{ 'bg-main-blue': isH1Active, 'font-weight-bold': isH1Active }"
          >
            T1
          </v-list-item>
          <v-list-item
            @click="toggleHeading(2)"
            :class="{ 'bg-main-blue': isH2Active, 'font-weight-bold': isH2Active }"
          >
            T2
          </v-list-item>
          <v-list-item
            @click="toggleHeading(3)"
            :class="{ 'bg-main-blue': isH3Active, 'font-weight-bold': isH3Active }"
          >
            T3
          </v-list-item>
        </v-list>
      </v-menu>
    </div>

    <editor-content :editor="editor" />

    <div v-if="errorStatus" class="TextEditor__errorMessage">
      <span v-if="editor.getText().length > props.maxLength">{{
        $t('forms.errorMessages.maxlength', { max: maxLength })
      }}</span>
      <span v-else-if="editor.getText().length < props.minLength">{{
        $t('forms.errorMessages.minlength', { min: minLength })
      }}</span>
    </div>
  </div>
</template>

<script setup lang="ts">
import StarterKit from '@tiptap/starter-kit';
import { Editor, EditorContent } from '@tiptap/vue-3';
import { computed, onBeforeUnmount, onMounted, type ModelRef } from 'vue';

const contentModel: ModelRef<string> = defineModel('contentModel', { required: true })
const props = withDefaults(
  defineProps<{
    parentFormError: boolean
    minLength?: number
    maxLength?: number
  }>(),
  {
    minLength: 50,
    maxLength: Infinity
  }
)

const editor = new Editor({
  extensions: [StarterKit],
  content: contentModel,
  editorProps: {
    attributes: {
      class: 'TextEditor__content'
    }
  }
})

editor.on('update', ({ editor }) => {
  contentModel.value = editor.getHTML()
})

const errorStatus = computed(
  () =>
    (editor.getText().length < props.minLength || editor.getText().length > props.maxLength) &&
    props.parentFormError
)

onMounted(() => {
  editor.commands.setContent(contentModel.value)
})

const toggleBold = () => editor.chain().focus().toggleBold().run()
const toggleItalic = () => editor.chain().focus().toggleItalic().run()
const toggleStrike = () => editor.chain().focus().toggleStrike().run()
const toggleHeading = (level: any) => editor.chain().focus().toggleHeading({ level }).run()

const isHeadingActive = computed(() => {
  return (
    editor.isActive('heading', { level: 1 }) ||
    editor.isActive('heading', { level: 2 }) ||
    editor.isActive('heading', { level: 3 })
  )
})
const isH1Active = computed(() => editor.isActive('heading', { level: 1 }))
const isH2Active = computed(() => editor.isActive('heading', { level: 2 }))
const isH3Active = computed(() => editor.isActive('heading', { level: 3 }))

onBeforeUnmount(() => {
  editor.destroy()
})
</script>

<style lang="scss">
.TextEditor {
  display: flex;
  flex-direction: column;
  border: 1px solid rgb(var(--v-theme-dark-grey));
  border-radius: 5px;
}
.TextEditor:hover {
  border-color: black;
}
.TextEditor--error {
  border: 2px solid red;
}
.TextEditor--error:hover {
  border-color: red;
}

.TextEditor__toolbar {
  display: flex;
  flex-direction: row;
  width: 100%;
  gap: 0.2rem;
  justify-content: center;
}

.TextEditor__content {
  min-height: 10rem;

  ul li {
    margin-left: 1.5rem;
  }
}

.TextEditor__errorMessage {
  color: red;
  font-size: 0.85em;
  margin: 0.5em;
}
</style>

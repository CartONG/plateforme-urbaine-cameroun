<template>
    <div class="ListSearchBar bg-main-yellow">
        <v-text-field
            v-model="searchQuery"
            variant="solo"
            bg-color="white"
            density="comfortable"
            :hide-details="true"
            :placeholder="placeholder"
        >
            <template v-slot:prepend-inner>
                <v-icon icon="mdi-magnify" color="main-blue"></v-icon>
            </template>
        </v-text-field>
    </div>
</template>
<script setup lang="ts">

const emit = defineEmits(['updateSelect', 'update:search'])

const searchQuery = defineModel()

defineProps<{
    placeholder: string,
}>()
</script>
<style lang="scss">
.ListSearchBar {
    display: flex;
    align-items: center;
    width: 28rem;

    border-radius: .5rem;
    padding: 1rem 1.5rem;

    .v-input {
        box-shadow: 0 .2rem .25rem -.125rem rgba(0, 0, 0, 0.3);
    }

    .v-autocomplete {
        ::placeholder {
            opacity: 1;
            letter-spacing: .05rem;
        }
    }

    .v-field__prepend-inner > .v-icon {
        opacity: 1;
    }
}

@media (max-width: $bp-md) {
    .ListSearchBar {
        width: 100%;
    }
}
</style>
<template>
  <div class="ContactCard">
    <div
      class="ContactCard__photo"
      :style="'background: url(' + image + ') center no-repeat'"
    ></div>
    <div class="ContactCard__infoCtn">
      <div class="ContactCard__name">{{ name }}</div>
      <div class="ContactCard__description">{{ description }}</div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  name: string
  description: string
  image: string
}>()
</script>

<style lang="scss">
.ContactCard {
  display: flex;
  width: 100%;
  flex-flow: row nowrap;
  gap: 1rem;

  .ContactCard__photo {
    $dim-photo: 3.5rem;
    width: $dim-photo;
    min-width: $dim-photo;
    height: $dim-photo;
    min-height: $dim-photo;
    background-size: cover;
    border-radius: 50%;
  }
  .ContactCard__infoCtn {
    display: flex;
    flex-direction: column;
    justify-content: center;

    .ContactCard__name {
      font-weight: 700;
    }

    .ContactCard__description {
    }
  }
}
</style>

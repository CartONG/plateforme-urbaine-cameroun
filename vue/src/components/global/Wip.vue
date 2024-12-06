<template>
  <div class="WIP" :style="`height: ${height}px`">
    <h2 class="text-main-green">Work In Progress</h2>
  </div>
</template>
<script setup lang="ts">
defineProps({
  height: {
    type: Number,
    default: 80
  }
})
</script>
<style scoped>
.WIP {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  background-color: #fdf5da;
  opacity: 0.8;
  /* background-image: radial-gradient(circle at center center, #F6CC47, #fdf5da), repeating-radial-gradient(circle at center center, #444cf7, #444cf7, 10px, transparent 20px, transparent 10px);
    background-blend-mode: multiply; */
  border: 1px solid black;

  --s: 20px; /* control the size*/
  --c1: #fdf5da;
  --c2: #f6cc47;

  --g: var(--c1) 3.125%, var(--c2) 0 9.375%, var(--c1) 0 15.625%, var(--c2) 0 21.875%,
    var(--c1) 0 28.125%, #0000 0;
  background:
    linear-gradient(
        225deg,
        #0000 3.125%,
        var(--c2) 0 9.375%,
        #0000 0 78.125%,
        var(--c2) 0 84.375%,
        #0000 0
      )
      0 calc(var(--s) / 2),
    linear-gradient(45deg, var(--g)) 0 var(--s),
    linear-gradient(45deg, var(--g)) calc(var(--s) / -2) calc(var(--s) / -2),
    linear-gradient(225deg, var(--g)) var(--s) 0,
    linear-gradient(225deg, var(--g)) calc(var(--s) / 2) var(--s),
    repeating-linear-gradient(-45deg, var(--c1) -3.125% 3.125%, var(--c2) 0 9.375%);
  background-size: calc(2 * var(--s)) calc(2 * var(--s));
}
</style>

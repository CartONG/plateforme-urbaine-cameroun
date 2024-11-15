<template>
  <div class="MosaicCtn">
    <div class="Mosaic">
      <div
        v-for="(image, index) in processedImages"
        :key="index"
        class="MosaicImg"
        :class="image.class"
        :style="`background-image: url('${image.url}')`"
        @click="viewImage(image.original)"
      />
    </div>
  </div>
  <v-dialog v-model="showImageViewer" width="auto">
    <img :src="imageViewerSrc" class="ImageViewer" />
  </v-dialog>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import type { MediaObject } from '@/models/interfaces/MediaObject';

const props = defineProps<{
  images: (string | MediaObject)[];
}>();

interface ProcessedImage {
  url: string;
  width: number;
  height: number;
  ratio: number;
  class: string;
  original: string | MediaObject;
  format: 'square' | 'horizontal' | 'vertical';
}

const imageData = ref<ProcessedImage[]>([]);

onMounted(() => {
  props.images.forEach((image) => {
    const imgUrl = (image as MediaObject).contentUrl ? (image as MediaObject).contentUrl : (image as string);
    const img = new Image();
    img.src = imgUrl;
    img.onload = () => {
      const { width, height } = img;
      const ratio = width / height;
      
      let format: 'square' | 'horizontal' | 'vertical';
      if (Math.abs(ratio - 1) <= 0.2) {
        format = 'square';
      } else if (ratio > 1) {
        format = 'horizontal';
      } else {
        format = 'vertical';
      }

      imageData.value.push({
        url: imgUrl,
        width,
        height,
        ratio,
        class: '',
        original: image,
        format
      });
    };
  });
});

const maxRowWidth = 4;
type GridCell = {
    occupied: boolean;
    verticalImage: boolean;
  };
type Grid = GridCell[][];

const initRow = (grid: Grid) => {
    const newRow = Array(maxRowWidth).fill(null).map(() => ({
      occupied: false,
      verticalImage: false
    }));
    grid.push(newRow);
    return grid.length - 1;
  };

const placeImage = (image: ProcessedImage, row: number, col: number, result: ProcessedImage[], grid: Grid) => {
  const width = image.format === 'horizontal' ? 2 : 1;
  const height = image.format === 'vertical' ? 2 : 1;
  const className = `MosaicImg--${image.format}`;
  // If we are in the last row, we need to create a new one if we have a vertical image to place
  if (row + height > grid.length) {
    initRow(grid);
  }
  // Mark occupied cells in rows
  for (let r = row; r < row + height; r++) {
    for (let c = col; c < col + width; c++) {
      grid[r][c].occupied = true;
      grid[r][c].verticalImage = image.format === 'vertical';
    }
  }
  result.push({ ...image, class: className });
};

const canPlaceImage = (row: number, col: number, width: number, height: number, grid: Grid): boolean => {
  if (col + width > maxRowWidth) return false;
  for (let r = row; r < row + height; r++) {
    if (r >= grid.length) {
      continue;
    }
    for (let c = col; c < col + width; c++) {
      if (grid[r][c].occupied) return false;
    }
  }
  return true;
};

const processedImages = computed(() => {
  let result: ProcessedImage[] = [];

  // Sort images by shape
  let sortedImages = [...imageData.value];
  const horizontalImages = sortedImages.filter(img => img.format === 'horizontal');
  const verticalImages = sortedImages.filter(img => img.format === 'vertical');
  const squareImages = sortedImages.filter(img => img.format === 'square');

  // Initialise a 2 dimensional grid
  let grid: Grid = [];
  initRow(grid);
  if (verticalImages.length > 0) {
    initRow(grid);
  }

  // PLaces images by shape while ensuring we can fill the next line when vertical images are verticalImagePlaced
  let currentRow = 0;
  while (verticalImages.length > 0 || horizontalImages.length > 0 || squareImages.length > 0) {
    // Ensure we have 2 lines for vertical images
    while (currentRow + 1 >= grid.length) {
      initRow(grid);
    }

    // Try to place first vertical image
    let verticalImagePlaced = false;
    for (let col = 0; col < maxRowWidth && verticalImages.length > 0; col++) {
      if (canPlaceImage(currentRow, col, 1, 2, grid)) {
        const remainingSpacesCurrentRow = maxRowWidth - col - 1;
        const remainingSpacesNextRow = maxRowWidth - 1;

        // Clone arrays of horizontal/square images to simulate filling lines
        const tempHorizontal = [...horizontalImages];
        const tempSquare = [...squareImages];
        
        let canFillCurrentRow = true;
        let canFillNextRow = true;

        // Simulates filling the current line
        let spacesNeeded = remainingSpacesCurrentRow;
        while (spacesNeeded > 0) {
          if (spacesNeeded >= 2 && tempHorizontal.length > 0) {
            tempHorizontal.shift();
            spacesNeeded -= 2;
          } else if (tempSquare.length > 0) {
            tempSquare.shift();
            spacesNeeded -= 1;
          } else {
            canFillCurrentRow = false;
            break;
          }
        }

        // Simulates filling the next line
        spacesNeeded = remainingSpacesNextRow;
        while (spacesNeeded > 0) {
          if (spacesNeeded >= 2 && tempHorizontal.length > 0) {
            tempHorizontal.shift();
            spacesNeeded -= 2;
          } else if (tempSquare.length > 0) {
            tempSquare.shift();
            spacesNeeded -= 1;
          } else {
            canFillNextRow = false;
            break;
          }
        }

        if (canFillCurrentRow && canFillNextRow) {
          placeImage(verticalImages.shift()!, currentRow, col, result, grid);
          verticalImagePlaced = true;
          break;
        }
      }
    }

    // If no vertical images have been placed, try horizontal images
    if (!verticalImagePlaced && horizontalImages.length > 0) {
      for (let col = 0; col <= maxRowWidth - 2; col++) {
        if (canPlaceImage(currentRow, col, 2, 1, grid)) {
          placeImage(horizontalImages.shift()!, currentRow, col, result, grid);
          verticalImagePlaced = true;
          break;
        }
      }
    }

    // Then try square images
    if (!verticalImagePlaced && squareImages.length > 0) {
      for (let col = 0; col < maxRowWidth; col++) {
        if (canPlaceImage(currentRow, col, 1, 1, grid)) {
          placeImage(squareImages.shift()!, currentRow, col, result, grid);
          verticalImagePlaced = true;
          break;
        }
      }
    }

    // If the current line is complete and there is no vertical image that overflows,
    // or if nothing more can be placed in the current line, go to the next line
    if (grid[currentRow].every(cell => cell.occupied) || !verticalImagePlaced) {
      let hasVerticalOverflow = false;
      for (let col = 0; col < maxRowWidth; col++) {
        if (grid[currentRow][col].verticalImage) {
          hasVerticalOverflow = true;
          break;
        }
      }
      
      if (!hasVerticalOverflow || !verticalImagePlaced) {
        currentRow++;
      }
    }
  }

  return result;
});

const imageViewerSrc = ref<string>('');
const showImageViewer = ref<boolean>(false);

function viewImage(image: string | MediaObject) {
  if (typeof image === 'string') {
    imageViewerSrc.value = image;
  } else {
    imageViewerSrc.value = (image as MediaObject).contentUrl;
  }
  showImageViewer.value = true;
}
</script>

<style lang="scss">
.MosaicCtn {
  padding: 1rem;

  .Mosaic {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    grid-auto-rows: 15rem;

    .MosaicImg {
      background-position: center;
      background-size: cover;
      border-radius: 0.5rem;
      overflow: hidden;
      cursor: pointer;
      width: 100%;
      height: 100%;

      &.MosaicImg--square {
        grid-column: span 1;
        grid-row: span 1;
      }

      &.MosaicImg--horizontal {
        grid-column: span 2;
        grid-row: span 1;
      }

      &.MosaicImg--vertical {
        grid-column: span 1;
        grid-row: span 2;
      }
    }
  }
}

.ImageViewer {
  background-color: white;
  max-width: 90vw;
  max-height: 90vh;
}
</style>

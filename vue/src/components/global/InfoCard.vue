<template>
    <v-card
        class="InfoCard Card"
        :to="to"
        :light="light"
    >
        <div class="InfoCard__content">
            <slot name="content"></slot>
        </div>
        <div class="InfoCard__footer" v-if="!light">
            <div class="InfoCard__footerBlock InfoCard__footerBlock--left">
                <slot name="footer-left"></slot>
            </div>
            <div class="InfoCard__footerBlock InfoCard__footerBlock--right">
                <slot name="footer-right"></slot>
            </div>
        </div>
    </v-card>
</template>

<script setup lang="ts">
import type { RouteLocationAsRelativeGeneric } from 'vue-router';
defineProps<{
    to?: string | RouteLocationAsRelativeGeneric,
    light?: boolean
}>()
</script>

<style lang="scss">
.InfoCard.v-card {
    width: 100%;
    justify-content: flex-end;
    padding: 25px;
    display: flex;
    flex-direction: column;

    &[light="true"] {
        box-shadow: none;
    }

    &:hover, &[active="true"] {

        .InfoCard__actionIcon {
            background-color: rgb(var(--v-theme-main-blue));
            color: white !important;
        }
    }

    .InfoCard__content {
        display: flex;
        flex-flow: column nowrap;
        flex-grow: 1;

        .InfoCard__subTitle {
            font-size: 14px;
            font-weight: 700;
            color: rgb(var(--v-theme-light-blue));
        }

        .InfoCard__title {
            font-size: 18px;
            font-weight: 700;
            color: rgb(var(--v-theme-main-blue));
        }
    }

    .InfoCard__footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;

        .InfoCard__footerBlock {
            display: flex;
            align-items: center;
            flex-flow: row nowrap;
        }
    }
    

    .InfoCard__actionIcon {
        background-color: transparent;
        padding: 1rem;
        border-radius: 50%;
        transition: all .1s ease-in;
    }
}
</style>
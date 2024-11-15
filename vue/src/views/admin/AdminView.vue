<template>
    <PageBanner :title="$t('admin.title')" class="mt-10" v-if="userStore.currentUser?.roles.includes(UserRoles.ADMIN)"/>
    <div class="AdminView mt-10" v-if="userStore.currentUser?.roles.includes(UserRoles.ADMIN)">
        <div class="AdminView__Left"><AdminPanelsSelector /></div>
        <div class="AdminView__Right"><router-view /></div>
    </div>
    <div v-else>Access denied</div>
</template>
<script setup lang="ts">
import AdminPanelsSelector from '@/views/admin/components/AdminPanelsSelector.vue';
import { UserRoles } from '@/models/enums/auth/UserRoles';
import { useUserStore } from '@/stores/userStore';
import PageBanner from '@/components/banners/PageBanner.vue';
const userStore = useUserStore();
</script>
<style lang="scss">
.AdminView {
    display: flex;
    width: 100%;
}
.AdminView__Left {
    width: 30%;
}
.AdminView__Right {
    width: 70%;
    background-color: white;
}
.AdminPanel {
    display: flex;
    flex-direction: column;
    width: 100%;
    &__additionnalMenu {
        font-weight: 700;
        color: rgb(var(--v-theme-main-blue));
    }
}
</style>
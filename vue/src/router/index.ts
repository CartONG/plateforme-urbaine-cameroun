import { createRouter, createWebHistory, onBeforeRouteUpdate, onBeforeRouteLeave } from 'vue-router';
import HomeView from '@/views/home/HomeView.vue'
import { useApplicationStore } from '@/stores/applicationStore'
import ActorProfile from '@/views/actors/components/ActorProfile.vue'
import AdminMembers from '@/views/admin/components/AdminMembers.vue'
import AdminContent from '@/views/admin/components/AdminContent.vue'
import AdminComments from '@/views/admin/components/AdminComments.vue'
import { useAdminStore } from '@/stores/adminStore'
import { AdministrationPanels } from '@/models/enums/app/AdministrationPanels'
import { DialogKey } from '@/models/enums/app/DialogKey'
import { useProjectStore } from '@/stores/projectStore'
import { ProjectListDisplay } from '@/models/enums/app/ProjectListType';
import { useActorsStore } from '@/stores/actorsStore';
import type { Actor } from '@/models/interfaces/Actor';
import { useUserStore } from '@/stores/userStore';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  scrollBehavior(to, from, savedPosition) {
    return savedPosition ? savedPosition : { el: '#app', top: 0, behavior: 'smooth' }
  },
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/actors',
      name: 'actors',
      component: () => import('@/views/actors/ActorsView.vue')
    },
    {
      path: '/actors/:slug',
      name: 'actorProfile',
      component: ActorProfile,
      beforeEnter: async (to, from, next) => {
        const actorsStore = useActorsStore()
        const actor: Actor | undefined = actorsStore.actors.find(actor => actor.slug === to.params.slug);
        actorsStore.setSelectedActor(actor?.id as string);
        next()
      }
    },
    {
      path: '/projects',
      name: 'projects',
      component: () => import('@/views/projects/ProjectListView.vue'),
      beforeEnter: (to, from, next) => {
        const projectStore = useProjectStore()
        projectStore.isProjectMapFullWidth = to.query.type === ProjectListDisplay.MAP ? true : false
        projectStore.activeProjectId = to.query.project ? +to.query.project : null
        next()
      }
    },
    {
      path: '/projects/:slug',
      name: 'projectPage',
      component: () => import('@/views/projects/ProjectSheetView.vue'),
      beforeEnter: async (to, from, next) => {
        const projectStore = useProjectStore()
        await projectStore.loadProjectBySlug(to.params.slug)
        next()
      }
    },
    {
      path: '/resources',
      name: 'resources',
      component: () => import('@/views/resources/ResourcesView.vue')
    },
    {
      path: '/services',
      name: 'services',
      component: () => import('@/views/services/ServicesView.vue')
    },
    {
      path: '/map',
      name: 'map',
      component: () => import('@/views/map/MapView.vue')
    },
    {
      path: '/myAccount',
      name: 'userAccount',
      component: () => import('@/views/member/MemberView.vue'),
      beforeEnter: (to, from, next) => {
        const userStore = useUserStore()
        if (!userStore.userIsLogged) {
          next({ path: '/' })
        } else {
          next()
        }
      }
    },
    {
      path: '/administration',
      name: 'admin',
      redirect: to => {
        const adminStore = useAdminStore()
        adminStore.selectedAdminPanel = AdministrationPanels.MEMBERS
        return {
          path: '/administration/membersPanel'
        }
      },
      component: () => import('@/views/admin/AdminView.vue'),
      beforeEnter: (to, from, next) => {
        const userStore = useUserStore()
        if (!userStore.userIsAdmin()) {
          next({ path: '/' })
        } else {
          next()
        }
      },
      children: [
        {
          path: 'membersPanel',
          component: AdminMembers,
        },
        {
          path: 'contentPanel',
          component: AdminContent,
        },
        {
          path: 'commentPanel',
          component: AdminComments,
        },
      ]
    },
  ]
})

router.beforeEach((to, from, next) => {
  const applicationStore = useApplicationStore()
  if (typeof to.query.dialog === 'string' && Object.values(DialogKey).includes(to.query.dialog as any) && to.query.dialog != undefined) {
    applicationStore.activeDialog = to.query.dialog as DialogKey
  } else {
    applicationStore.activeDialog = null
  }
  next()
})

export default router

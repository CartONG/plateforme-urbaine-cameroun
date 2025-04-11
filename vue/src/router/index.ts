import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '@/views/home/HomeView.vue'
import { useApplicationStore } from '@/stores/applicationStore'
import ActorProfile from '@/views/actors/ActorSheetView.vue'
import AdminMembers from '@/views/admin/components/AdminMembers.vue'
import AdminContent from '@/views/admin/components/AdminContent.vue'
import AdminComments from '@/views/admin/components/AdminComments.vue'
import { useAdminStore } from '@/stores/adminStore'
import { AdministrationPanels } from '@/models/enums/app/AdministrationPanels'
import { DialogKey } from '@/models/enums/app/DialogKey'
import { useProjectStore } from '@/stores/projectStore'
import { ProjectListDisplay } from '@/models/enums/app/ProjectListType'
import { useActorsStore } from '@/stores/actorsStore'
import type { Actor } from '@/models/interfaces/Actor'
import { useUserStore } from '@/stores/userStore'
import { i18n } from '@/plugins/i18n'
import AdminMaps from '@/views/admin/components/AdminMaps.vue'
import { useMyMapStore } from '@/stores/myMapStore'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  scrollBehavior(to, from, savedPosition) {
    return savedPosition ? savedPosition : { el: '#app', top: 0 }
  },
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      name: 'ui',
      path: '/ui',
      beforeEnter: (to, from, next) =>
        import.meta.env.MODE === 'production' ? next({ name: 'home' }) : next(),
      component: () => import('@/views/_layout/ui/UiView.vue')
    },
    {
      path: `/${i18n.t('routes.actors')}`,
      name: 'actors',
      component: () => import('@/views/actors/ActorListView.vue')
    },
    {
      path: `/${i18n.t('routes.actors')}/:slug`,
      name: 'actorProfile',
      component: ActorProfile,
      beforeEnter: async (to, from, next) => {
        const actorsStore = useActorsStore()
        const actor: Actor | undefined = actorsStore.actors.find(
          (actor) => actor.slug === to.params.slug
        )
        if (actor?.id) {
          await actorsStore.setSelectedActor(actor.id, false)
        }
        next()
      }
    },
    {
      path: `/${i18n.t('routes.projects')}`,
      name: 'projects',
      component: () => import('@/views/projects/ProjectListView.vue'),
      beforeEnter: (to, from, next) => {
        const projectStore = useProjectStore()
        projectStore.isProjectMapFullWidth = to.query.type === ProjectListDisplay.MAP ? true : false
        projectStore.activeProjectId = to.query.project ? to.query.project.toString() : null
        next()
      }
    },
    {
      path: `/${i18n.t('routes.projects')}/:slug`,
      name: 'projectPage',
      component: () => import('@/views/projects/ProjectSheetView.vue'),
      beforeEnter: async (to, from, next) => {
        const projectStore = useProjectStore()
        await projectStore.loadProjectBySlug(to.params.slug)
        next()
      }
    },
    {
      path: `/${i18n.t('routes.resources')}`,
      name: 'resources',
      component: () => import('@/views/resources/ResourceListView.vue')
    },
    {
      path: `/${i18n.t('routes.services')}`,
      name: 'services',
      component: () => import('@/views/services/ServicesView.vue')
    },
    {
      path: `/${i18n.t('routes.map')}`,
      name: 'map',
      component: () => import('@/views/map/MyMapView.vue'),
      beforeEnter: (to, from, next) => {
        const myMapStore = useMyMapStore()
        myMapStore.activeItemId = to.query.item ? to.query.item.toString() : null
        next()
      }
    },
    {
      path: `/${i18n.t('routes.myAccount')}`,
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
      path: '/admin',
      name: 'admin',
      redirect: () => {
        const adminStore = useAdminStore()
        adminStore.selectedAdminPanel = AdministrationPanels.MEMBERS
        return {
          name: 'adminUsers'
        }
      },
      component: () => import('@/views/admin/AdminView.vue'),
      beforeEnter: (to, from, next) => {
        if (import.meta.env.MODE !== 'production') {
          next()
        } else {
          const userStore = useUserStore()
          if (!userStore.userIsAdmin()) {
            next({ path: '/' })
          } else {
            next()
          }
        }
      },
      children: [
        {
          path: 'users',
          name: 'adminUsers',
          component: AdminMembers
        },
        {
          path: 'content',
          name: 'adminContent',
          component: AdminContent,
          redirect: () => ({ name: 'adminActors' }),
          children: [
            {
              name: 'adminActors',
              path: 'actors',
              component: () => import('@/views/admin/components/admin-content/AdminActorsPanel.vue')
            },
            {
              name: 'adminProjects',
              path: 'projects',
              component: () =>
                import('@/views/admin/components/admin-content/AdminProjectsPanel.vue')
            },
            {
              name: 'adminResources',
              path: 'resources',
              component: () =>
                import('@/views/admin/components/admin-content/AdminResourcesPanel.vue')
            }
          ]
        },
        {
          path: 'maps',
          name: 'adminMaps',
          component: AdminMaps,
          redirect: () => ({ name: 'adminPredefinedMaps' }),
          children: [
            {
              name: 'adminPredefinedMaps',
              path: 'observatory',
              component: () => import('@/views/admin/components/admin-maps/AdminPredefinedMaps.vue')
            },
            {
              name: 'adminThematicMaps',
              path: 'catalogue',
              component: () => import('@/views/admin/components/admin-maps/AdminThematicMaps.vue')
            },
            {
              name: 'adminQgisMaps',
              path: 'data',
              component: () => import('@/views/admin/components/admin-maps/AdminQgisMaps.vue')
            }
          ]
        },
        {
          name: 'adminHighlights',
          path: 'highlights',
          component: () =>
            import('@/views/admin/components/admin-highlights/AdminHighlightsPanel.vue')
        },
        {
          name: 'adminComments',
          path: 'comments',
          component: AdminComments,
          redirect: () => ({ name: 'actorsComments' }),
          children: [
            {
              name: 'actorsComments',
              path: 'actorsComments',
              component: () => import('@/views/admin/components/admin-comments/ActorComments.vue')
            },
            {
              name: 'projectsComments',
              path: 'projectsComments',
              component: () => import('@/views/admin/components/admin-comments/ProjectComments.vue')
            },
            {
              name: 'resourcesComments',
              path: 'resourcesComments',
              component: () =>
                import('@/views/admin/components/admin-comments/ResourcesComments.vue')
            },
            {
              name: 'mapComments',
              path: 'mapComments',
              component: () => import('@/views/admin/components/admin-comments/MapComments.vue')
            }
          ]
        }
      ]
    }
  ]
})

router.beforeEach((to, from, next) => {
  const applicationStore = useApplicationStore()
  if (
    to.query.dialog !== undefined &&
    typeof to.query.dialog === 'string' &&
    Object.values(DialogKey).includes(to.query.dialog as unknown as DialogKey)
  ) {
    applicationStore.activeDialog = to.query.dialog as DialogKey
  } else {
    applicationStore.activeDialog = null
  }
  next()
})

// Workaround for https://github.com/vitejs/vite/issues/11804
router.onError((err, to) => {
  if (err?.message?.includes?.('Failed to fetch dynamically imported module')) {
    if (!localStorage.getItem('vuetify:dynamic-reload')) {
      console.log('Reloading page to fix dynamic import error')
      localStorage.setItem('vuetify:dynamic-reload', 'true')
      location.assign(to.fullPath)
    } else {
      console.error('Dynamic import error, reloading page did not fix it', err)
    }
  } else {
    console.error(err)
  }
})

router.isReady().then(() => {
  localStorage.removeItem('vuetify:dynamic-reload')
})

export default router

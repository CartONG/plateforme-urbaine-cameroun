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
import AdminMaps from '@/views/admin/components/AdminMaps.vue'

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
      path: '/actors',
      name: 'actors',
      component: () => import('@/views/actors/ActorListView.vue')
    },
    {
      path: '/actors/:slug',
      name: 'actorProfile',
      component: ActorProfile,
      beforeEnter: async (to, from, next) => {
        const actorsStore = useActorsStore()
        const actor: Actor | undefined = actorsStore.actors.find(
          (actor) => actor.slug === to.params.slug
        )
        actorsStore.setSelectedActor(actor?.id as string)
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
        projectStore.activeProjectId = to.query.project ? to.query.project.toString() : null
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
      component: () => import('@/views/resources/ResourceListView.vue')
    },
    {
      path: '/services',
      name: 'services',
      component: () => import('@/views/services/ServicesView.vue')
    },
    {
      path: '/map',
      name: 'map',
      component: () => import('@/views/map/MyMapView.vue')
    },
    // TODO : To delete, for testing
    {
      path: '/map-qgis',
      name: 'mapQgis',
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
      path: '/admin',
      name: 'admin',
      redirect: () => {
        const adminStore = useAdminStore()
        adminStore.selectedAdminPanel = AdministrationPanels.MEMBERS
        return {
          name: 'adminMembers'
        }
      },
      component: () => import('@/views/admin/AdminView.vue'),
      beforeEnter: (to, from, next) => {
        // const userStore = useUserStore()
        // if (!userStore.userIsAdmin()) {
        //   next({ path: '/' })
        // } else {
        next()
        // }
      },
      children: [
        {
          path: 'membres',
          name: 'adminMembers',
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
          redirect: () => ({ name: 'adminAtlas' }),
          children: [
            {
              name: 'adminAtlas',
              path: 'atlas',
              component: () => import('@/views/admin/components/admin-maps/AdminAtlas.vue')
            },
            {
              name: 'adminQgisProjects',
              path: 'qgis-projects',
              component: () => import('@/views/admin/components/admin-maps/AdminQgisProjects.vue')
            }
          ]
        },
        {
          name: 'adminComments',
          path: 'comments',
          component: AdminComments
        }
      ]
    }
  ]
})

router.beforeEach((to, from, next) => {
  const applicationStore = useApplicationStore()
  if (
    typeof to.query.dialog === 'string' &&
    Object.values(DialogKey).includes(to.query.dialog as any) &&
    to.query.dialog != undefined
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

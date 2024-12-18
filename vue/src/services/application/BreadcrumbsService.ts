import { i18n } from '@/plugins/i18n'
import { ContentPagesList } from '@/models/enums/app/ContentPagesList'
import { useActorsStore } from '@/stores/actorsStore'
import { useApplicationStore } from '@/stores/applicationStore'
import { useProjectStore } from '@/stores/projectStore'
import type { RouteLocationNormalizedLoadedGeneric } from 'vue-router'

export class BreadcrumbsService {
  static getBreadcrumbsItems(route: RouteLocationNormalizedLoadedGeneric) {
    const segments = route.path.split('/').filter(Boolean)
    let newRoute = '/'
    const breadcrumbs: { title: string; to: string }[] = []
    const appStore = useApplicationStore()
    const actor = useActorsStore().selectedActor
    const project = useProjectStore().project
    if (segments.length > 0) {
      breadcrumbs.push({ title: i18n.t('breadcrumbs.home'), to: newRoute })
      segments.map((segment, i) => {
        const key = 'breadcrumbs.' + segment
        const text = i18n.t(key)
        newRoute += segment
        if (appStore.currentContentPage === ContentPagesList.ACTOR && segment === actor?.slug) {
          breadcrumbs.push({ title: actor.name, to: newRoute })
        } else if (
          appStore.currentContentPage === ContentPagesList.PROJECT &&
          segment === project?.slug
        ) {
          breadcrumbs.push({ title: project.name, to: newRoute })
        } else {
          // Check if traduction exists
          if (text === key) {
            breadcrumbs.push({ title: decodeURI(segment), to: newRoute })
          } else {
            breadcrumbs.push({ title: text, to: newRoute })
          }
        }

        if (i < segments.length - 1) {
          newRoute += '/'
        }
      })
    }

    return breadcrumbs
  }
}

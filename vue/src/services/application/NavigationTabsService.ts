import { i18n } from '@/plugins/i18n'
import { NavigationTabs } from '@/models/enums/app/NavigationTabs'
import type { RouteLocationNormalizedLoadedGeneric } from 'vue-router'
export class NavigationTabsService {
  static getContent() {
    return [
      {
        name: i18n.t('header.home'),
        value: NavigationTabs.HOME,
        route: '/'
      },
      {
        name: i18n.t('header.actors'),
        value: NavigationTabs.ACTORS,
        route: '/actors'
      },
      {
        name: i18n.t('header.projects'),
        value: NavigationTabs.PROJECTS,
        route: '/projects'
      },
      {
        name: i18n.t('header.resources'),
        value: NavigationTabs.RESOURCES,
        route: '/resources'
      },
      {
        name: i18n.t('header.services'),
        value: NavigationTabs.SERVICES,
        route: '/services'
      }
    ]
  }
  static getTabsNumberFromRoute(route: RouteLocationNormalizedLoadedGeneric, actualNumber: number) {
    const segments = route.path.split('/').filter(Boolean)
    //When Homepage
    if (segments.length === 0) {
      return 0
    }
    const newTabsNumber = segments
      .map((string) => this.getContent().find((obj) => obj.route.includes(string)))
      .filter(Boolean)
      .pop()?.value
    if (newTabsNumber && newTabsNumber !== actualNumber) {
      return newTabsNumber
    }
    // When user select a route that is not in the tabs (eg.Map)
    if (!newTabsNumber) {
      return -1
    }
    return actualNumber
  }
}

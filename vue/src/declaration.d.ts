/* eslint-disable */
import type {RouteLocationNormalizedLoaded} from "vue-router";

export {}

declare module 'vue' {
  export interface ComponentCustomProperties {
    $t: (key: string, ...args: any[]) => string;
    $route: RouteLocationNormalizedLoaded;
  }
}

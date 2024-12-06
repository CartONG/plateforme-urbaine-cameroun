import * as Sentry from '@sentry/vue'
import type { App } from 'vue'
import type { Router } from 'vue-router'

export const initSentry = (app: App, router: Router) => {
  if (import.meta.env.MODE === 'production') {
    Sentry.init({
      app,
      dsn: import.meta.env.VITE_SENTRY_DSN,
      integrations: [Sentry.browserTracingIntegration({ router })],
      // Tracing
      tracesSampleRate: 1.0, //  Capture 100% of the transactions
      // Set 'tracePropagationTargets' to control for which URLs distributed tracing should be enabled
      tracePropagationTargets: [/^https:\/\/(dev|uat)\.puc.\.cartong\.org/]
    })
  }
}

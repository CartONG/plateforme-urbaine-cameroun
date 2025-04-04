// Plugins
import Vue from '@vitejs/plugin-vue'
import Vuetify from 'vite-plugin-vuetify'
import { sentryVitePlugin } from '@sentry/vite-plugin'
import VueDevTools from 'vite-plugin-vue-devtools'

// Utilities
import { defineConfig } from 'vite'
import { fileURLToPath, URL } from 'node:url'

// https://vitejs.dev/config/
export default defineConfig({
  // base: "/",
  plugins: [
    Vue(),
    VueDevTools(),
    // https://github.com/vuetifyjs/vuetify-loader/tree/master/packages/vite-plugin#readme
    Vuetify({
      autoImport: true
    }),
    sentryVitePlugin({
      org: 'cartong',
      project: 'puc-vue',
      authToken: process.env.SENTRY_AUTH_TOKEN
    })
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    },
    extensions: ['.js', '.json', '.jsx', '.mjs', '.ts', '.tsx', '.vue']
  },
  server: {
    port: 3000,
    allowedHosts: ['puc.local']
    // host: "0.0.0.0",
    // watch: {
    //   usePolling: true,
    // }
  },
  css: {
    preprocessorOptions: {
      scss: {
        additionalData: `@import "./src/assets/styles/global/_variables";`,
        silenceDeprecations: ['legacy-js-api', 'import']
      }
    }
  }
})

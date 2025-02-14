import { defineConfig } from 'vitepress'

// https://vitepress.dev/reference/site-config
export default defineConfig({
  title: "Urban Platform Cameroun",
  description: "Technical documentation",
  vite: {
    server: {
      host: "0.0.0.0",
      port: 5000, 
      watch: {
        usePolling: true
      }
    }
  },
  base: "/",
  themeConfig: {
    // https://vitepress.dev/reference/default-theme-config
    nav: [
      { text: 'Home', link: '/' },
      { text: 'Documentation', link: '/home' }
    ],
    
    search: {
      provider: 'local'
    },

    sidebar: [
      {
        collapsed: false,
        text: 'Environments',
        items: [
          {
            text: 'Setup',
            collapsed: true,
            items: [
              { text: 'Setup Local', link: '/environments/setup/local' },
              { text: 'Setup Production', link: '/environments/setup/production' },
            ]
          },
          { text: 'Remote environments', link: '/environments/remote' },
          { text: 'Deployement', link: '/environments/deployement' },
          { text: 'Continuous Integration', link: '/environments/ci' },
        ]
      },
      {
        collapsed: false,
        text: 'Infrastructure',
        items: [
          { text: 'Architecture schema', link: '/infrastructure' },
        ]
      },
      {
        collapsed: false,
        text: 'Development',
        items: [
          { text: 'Dev commands', link: '/development/commands' },
          { text: 'Request the API', link: '/development/request-the-api' },
          { text: 'Components', link: '/development/components' },
        ]
      },
      {
        collapsed: false,
        text: 'Database access',
        items: [
          { text: 'PostgreSQL', link: '/database/postgresql' },
          { text: 'Qgis Desktop', link: '/database/qgis' },
        ]
      },
    ],

    socialLinks: [
      { icon: 'github', link: 'https://github.com/CartONG/plateforme-urbaine-cameroun' }
    ]
  }
})

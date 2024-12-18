/**
 * plugins/vuetify.ts
 *
 * Framework documentation: https://vuetifyjs.com`
 */

// Styles
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import { VDateInput } from 'vuetify/labs/VDateInput'

// Composables
import { createVuetify, type ThemeDefinition } from 'vuetify'

const pucCustomTheme: ThemeDefinition = {
  colors: {
    'main-blue': '#1c3b87',
    'bright-blue': '#111EF7',
    'light-blue': '#6176AB',
    'main-red': '#E83323',
    'main-yellow': '#F6CC47',
    'light-yellow': '#fdf5da',
    'main-grey': '#E0E0E0',
    'main-green': '#2D6438'
  }
}

// https://vuetifyjs.com/en/introduction/why-vuetify/#feature-guides
export default createVuetify({
  components: {
    VDateInput
  },
  display: {
    mobileBreakpoint: 'lg',
    thresholds: {
      xs: 0,
      sm: 576,
      md: 768,
      lg: 992,
      xl: 1100
    }
  },
  theme: {
    defaultTheme: 'pucCustomTheme',
    themes: {
      pucCustomTheme
    }
  },
  defaults: {
    VBtn: {
      variant: 'flat',
      style: [
        {
          textTransform: 'none',
          fontWeight: 'bold',
          letterSpacing: '.045rem'
        }
      ]
    },
    VTextField: {
      hideDetails: 'auto'
    },
    VTextarea: {
      hideDetails: 'auto'
    },
    VSelect: {
      hideDetails: 'auto'
    },
    VAutocomplete: {
      hideDetails: 'auto'
    },
    VPagination: {
      rounded: 'circle',
      color: 'main-blue',
      activeColor: 'main-yellow'
    },
    VTabs: {
      height: '38px'
    },
    VBreadcrumbs: {
      style: [
        {
          padding: '.75rem 0',
          fontSize: '.875rem'
        }
      ]
    },
    VFieldLabel: {
      style: [
        {
          opacity: '1'
        }
      ]
    }
  }
})

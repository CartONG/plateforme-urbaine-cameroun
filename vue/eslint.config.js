/**
 * .eslint.js
 *
 * ESLint configuration file.
 */
import pluginVue from 'eslint-plugin-vue'
import js from '@eslint/js'
import vueTsEslintConfig from '@vue/eslint-config-typescript'
import vuePrettierConfig from '@vue/eslint-config-prettier'
import unusedImports from 'eslint-plugin-unused-imports'
// import importPlugin from 'eslint-plugin-import'

export default [
  ...pluginVue.configs['flat/essential'],
  js.configs.recommended,
  ...vueTsEslintConfig(),
  vuePrettierConfig,
  {
    plugins: {
      'unused-imports': unusedImports
    },
    name: 'app/files-to-lint',
    files: ['**/*.{ts,mts,tsx,vue}'],
    rules: {
      'vue/multi-word-component-names': 'off',
      '@typescript-eslint/no-explicit-any': 'warn',
      '@typescript-eslint/no-empty-object-type': 'warn',
      'no-unused-vars': 'off',
      'unused-imports/no-unused-imports': 'error',
      'unused-imports/no-unused-vars': [
        'error',
        {
          vars: 'all',
          varsIgnorePattern: '^_',
          args: 'after-used',
          argsIgnorePattern: '^_'
        }
      ]
    }
  },
  {
    name: 'app/files-to-ignore',
    ignores: ['**/dist/**', '**/dist-ssr/**', '**/coverage/**']
  }
]

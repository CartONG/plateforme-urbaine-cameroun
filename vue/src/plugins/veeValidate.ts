import { configure } from 'vee-validate'
import { localize, setLocale } from '@vee-validate/i18n'
import fr from '@vee-validate/i18n/dist/locale/fr.json'

export class VeeValidate {
  static init() {
    configure({
      generateMessage: localize({ fr })
    })
    setLocale('fr')
  }
}

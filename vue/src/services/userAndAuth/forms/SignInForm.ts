import { i18n } from '@/plugins/i18n'
import { toTypedSchema } from '@vee-validate/zod'
import { useField, useForm } from 'vee-validate'
import { z } from 'zod'

export class SignInForm {
  static getSignInForm() {
    const validationSchema = toTypedSchema(
      z.object({
        email: z.string().email({ message: i18n.t('forms.errorMessages.email') }),
        password: z.string().min(1, { message: i18n.t('forms.errorMessages.required') }),
        stayLoggedIn: z.boolean().optional()
      })
    )

    const { errors, handleSubmit, isSubmitting } = useForm({ validationSchema: validationSchema })
    const form = {
      email: useField('email', '', { validateOnValueUpdate: false }),
      password: useField('password', '', { validateOnValueUpdate: false }),
      stayLoggedIn: useField('stayLoggedIn', undefined, { validateOnValueUpdate: false })
    }
    return { form, errors, handleSubmit, isSubmitting }
  }
}

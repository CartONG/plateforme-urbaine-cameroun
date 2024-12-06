import { toTypedSchema } from '@vee-validate/zod'
import { useField, useForm } from 'vee-validate'
import { z } from 'zod'
import { i18n } from '@/assets/plugins/i18n'

export class SignInForm {
  static getSignInForm() {
    const validationSchema = toTypedSchema(
      z.object({
        email: z.string().email({ message: i18n.t('forms.errorMessages.email') }),
        password: z.string().min(1, { message: i18n.t('forms.errorMessages.required') })
      })
    )

    const { errors, handleSubmit, isSubmitting } = useForm({ validationSchema: validationSchema })
    const form = {
      email: useField('email', '', { validateOnValueUpdate: false }),
      password: useField('password', '', { validateOnValueUpdate: false })
    }
    return { form, errors, handleSubmit, isSubmitting }
  }
}

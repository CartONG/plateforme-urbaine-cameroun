import { z, ZodString, ZodObject } from 'zod'
import { i18n } from '@/plugins/i18n'

export class UserValidator {
  public static get emailSchema() {
    return z.string().email({ message: i18n.t('forms.errorMessages.email') })
  }

  public static get passwordSchema() {
    return z
      .string()
      .min(1, { message: i18n.t('forms.errorMessages.required') })
      .min(8, { message: i18n.t('forms.errorMessages.minlength', { min: 8 }) })
  }

  public static passwordsObject() {
    return {
      plainPassword: UserValidator.passwordSchema,
      confirmPassword: UserValidator.passwordSchema
    }
  }

  public static refinePasswordMatch(
    zodObject: ZodObject<{ plainPassword: ZodString; confirmPassword: ZodString }>
  ) {
    return zodObject.refine(
      (data: { plainPassword: string; confirmPassword: string }) =>
        data.plainPassword === data.confirmPassword,
      {
        message: i18n.t('auth.becomeMember.form.passwordMatchError'),
        path: ['confirmPassword']
      }
    )
  }
}

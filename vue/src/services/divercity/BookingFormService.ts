import { i18n } from '@/plugins/i18n'
import { toTypedSchema } from '@vee-validate/zod'
import { useField, useForm } from 'vee-validate'
import { z } from 'zod'
import { CommonZodSchema } from '@/services/forms/CommonZodSchema'

export class BookingFormService {
  static getBookingForm(defaults: Record<string, any> = {}) {
    const zodModels = CommonZodSchema.getDefinitions()

    const bookingSchema = z.object({
      // Étape 1 — Identification du demandeur
      lastName: z.string({ required_error: i18n.t('forms.errorMessages.required') }).min(1),
      firstName: z.string({ required_error: i18n.t('forms.errorMessages.required') }).min(1),
      organization: z.string().optional(),
      role: z.string({ required_error: i18n.t('forms.errorMessages.required') }).min(1),
      email: zodModels.email,
      phone: z.string({ required_error: i18n.t('forms.errorMessages.required') }).min(1),

      // Étape 2 — Informations sur l'évènement / activité
      title: z.string({ required_error: i18n.t('forms.errorMessages.required') }).min(1),
      eventActivityType: z.string().optional().nullable(),
      agenda: z.instanceof(File, { message: i18n.t('forms.errorMessages.required') }),
      resourceDocument: z
        .array(z.instanceof(File))
        .min(1, { message: i18n.t('forms.errorMessages.required') }),
      otherDocument: z.array(z.instanceof(File)).optional().nullable(),
      additionalInformation: z.string().optional(),
      bookingPurpose: z.string({ required_error: i18n.t('forms.errorMessages.required') }).min(1),
      participantCount: z
        .number({ required_error: i18n.t('forms.errorMessages.required') })
        .positive({ message: i18n.t('forms.errorMessages.positive') }),

      // Étape 3 — Créneau souhaité
      date: z.string({ required_error: i18n.t('forms.errorMessages.required') }),
      startTime: z.string({ required_error: i18n.t('forms.errorMessages.required') }),
      endTime: z.string({ required_error: i18n.t('forms.errorMessages.required') })
    })

    const { errors, handleSubmit, isSubmitting, values } = useForm({
      initialValues: defaults,
      validationSchema: toTypedSchema(bookingSchema)
    })

    const form = {
      lastName: useField('lastName', '', { validateOnValueUpdate: false }),
      firstName: useField('firstName', '', { validateOnValueUpdate: false }),
      organization: useField('organization', '', { validateOnValueUpdate: false }),
      role: useField('role', '', { validateOnValueUpdate: false }),
      email: useField('email', '', { validateOnValueUpdate: false }),
      phone: useField('phone', '', { validateOnValueUpdate: false }),
      title: useField('title', '', { validateOnValueUpdate: false }),
      eventActivityType: useField('eventActivityType', '', { validateOnValueUpdate: false }),
      agenda: useField<File | null>('agenda', '', { validateOnValueUpdate: false }),
      resourceDocument: useField<File[] | null>('resourceDocument', '', {
        validateOnValueUpdate: false
      }),
      otherDocument: useField<File[] | null>('otherDocument', '', {
        validateOnValueUpdate: false
      }),
      additionalInformation: useField('additionalInformation', '', {
        validateOnValueUpdate: false
      }),
      bookingPurpose: useField('bookingPurpose', '', { validateOnValueUpdate: false }),
      participantCount: useField('participantCount', '', { validateOnValueUpdate: false }),
      date: useField('date', '', { validateOnValueUpdate: false }),
      startTime: useField('startTime', '', { validateOnValueUpdate: false }),
      endTime: useField('endTime', '', { validateOnValueUpdate: false })
    }

    // Regroupement des champs par étape, pour valider avant de passer à "Suivant"
    const stepFields: Record<number, (keyof typeof form)[]> = {
      1: ['lastName', 'firstName', 'organization', 'role', 'email', 'phone'],
      2: [
        'title',
        'eventActivityType',
        'agenda',
        'resourceDocument',
        'otherDocument',
        'additionalInformation',
        'bookingPurpose',
        'participantCount'
      ],
      3: ['date', 'startTime', 'endTime']
    }

    return { form, errors, handleSubmit, isSubmitting, values, stepFields }
  }

  // Formulaire (mini) affiché sur l'écran "Terminé"
  static getInformationSourceForm() {
    const schema = z.object({
      informationSource: z.string({ required_error: i18n.t('forms.errorMessages.required') })
    })

    const { handleSubmit, isSubmitting } = useForm({
      validationSchema: toTypedSchema(schema)
    })

    const form = {
      informationSource: useField('informationSource', '', { validateOnValueUpdate: false })
    }

    return { form, handleSubmit, isSubmitting }
  }
}
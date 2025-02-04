import { toTypedSchema } from '@vee-validate/zod'
import { useField, useForm } from 'vee-validate'
import { z } from 'zod'
import { i18n } from '@/plugins/i18n'
import { CommonZodSchema } from '@/services/forms/CommonZodSchema'
import type { QgisMap } from '@/models/interfaces/QgisMap'

export class QgisMapFormService {
  static getForm(qgisMap: QgisMap | null) {
    const zodModels = CommonZodSchema.getDefinitions()
    // Schéma de base pour les champs communs
    const baseSchema = {
      name: z.string().min(1, { message: i18n.t('forms.errorMessages.required') }),
      description: z.string().optional(),
      qgisMapType: z.string().min(1, { message: i18n.t('forms.errorMessages.required') }),
      needsToBeVisualiseAsPlainImageInsteadOfWMS: z.boolean().optional()
    }

    // Schéma pour la création (fichier obligatoire)
    const createSchema = z.object({
      ...baseSchema,
      qgisProject: zodModels.qgisProject
    })

    // Schéma pour l'édition (fichier optionnel)
    const editSchema = z.object({
      ...baseSchema
    })

    // Sélection du schéma approprié
    const qgisMapSchema = qgisMap ? editSchema : createSchema

    const { errors, handleSubmit, isSubmitting, setFieldValue } = useForm<Partial<QgisMap>>({
      initialValues: qgisMap,
      validationSchema: toTypedSchema(qgisMapSchema)
    })

    const form: any = {
      name: useField('name'),
      description: useField('description'),
      qgisProject: useField('qgisProject'),
      qgisMapType: useField('qgisMapType'),
      needsToBeVisualiseAsPlainImageInsteadOfWMS: useField(
        'needsToBeVisualiseAsPlainImageInsteadOfWMS'
      )
    }

    return { form, errors, handleSubmit, isSubmitting, setFieldValue }
  }
}

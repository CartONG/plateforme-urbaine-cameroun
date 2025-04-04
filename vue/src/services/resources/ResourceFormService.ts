import type { Resource } from '@/models/interfaces/Resource'
import { toTypedSchema } from '@vee-validate/zod'
import { useField, useForm } from 'vee-validate'
import { z } from 'zod'
import { i18n } from '@/plugins/i18n'
import { CommonZodSchema } from '@/services/forms/CommonZodSchema'
import { ResourceFormat } from '@/models/enums/contents/ResourceFormat'
import { ResourceType } from '@/models/enums/contents/ResourceType'

export class ResourceFormService {
  static getForm(resource: Resource | null) {
    const zodModels = CommonZodSchema.getDefinitions()
    // @ts-expect-error - zod object.
    const resourceSchema: z.ZodType<Partial<Resource>> = z
      .object({
        name: z.string().min(1, { message: i18n.t('forms.errorMessages.required') }),
        description: zodModels.descriptionRequired,
        type: z.nativeEnum(ResourceType),
        otherType: z.string().optional(),
        geoData: zodModels.geoDataNullable.optional(),
        startAt: z.coerce.date().nullable().optional(),
        endAt: z.coerce.date().nullable().optional(),
        file: zodModels.file.nullable().optional(),
        link: zodModels.website.nullable().optional(),
        format: z.nativeEnum(ResourceFormat),
        author: zodModels.maxLabel.optional(),
        thematics: zodModels.symfonyRelations,
        otherThematic: z.string().optional()
      })
      .refine(
        (schema) => {
          return schema.file || schema.link
        },
        {
          message: i18n.t('resources.form.errorMessages.atLeastOneField'),
          path: ['link', 'file']
        }
      )
      .refine(
        (schema) =>
          (schema.type === ResourceType.EVENTS && schema.startAt) ||
          schema.type !== ResourceType.EVENTS,
        {
          message: i18n.t('forms.errorMessages.required'),
          path: ['startAt']
        }
      )

    const { errors, handleSubmit, isSubmitting, setFieldValue } = useForm<Partial<Resource>>({
      initialValues: resource,
      validationSchema: toTypedSchema(resourceSchema)
    })

    const form: any = {
      name: useField('name'),
      description: useField('description'),
      type: useField('type'),
      otherType: useField('otherType'),
      file: useField('file'),
      format: useField('format'),
      geoData: useField('geoData'),
      startAt: useField('startAt'),
      endAt: useField('endAt'),
      author: useField('author'),
      thematics: useField('thematics'),
      otherThematic: useField('otherThematic'),
      link: useField('link')
    }

    return { form, errors, handleSubmit, isSubmitting, setFieldValue }
  }
}

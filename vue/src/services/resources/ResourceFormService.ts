import type { Resource, ResourceSubmission } from '@/models/interfaces/Resource'
import { toTypedSchema } from '@vee-validate/zod'
import { useField, useForm } from 'vee-validate'
import { z } from 'zod'
import { i18n } from '@/plugins/i18n'
import { CommonZodSchema } from '@/services/forms/CommonZodSchema'
import { ResourceFormat } from '@/models/enums/contents/ResourceFormat'
import { ResourceType } from '@/models/enums/contents/ResourceType'
import GeocodingService from '@/services/map/GeocodingService'

export class ResourceFormService {
  static getForm(resource: Resource | null) {
    const zodModels = CommonZodSchema.getDefinitions()
    // @ts-ignore
    const resourceSchema: z.ZodType<Partial<Resource>> = z
      .object({
        name: z.string().min(1, { message: i18n.t('forms.errorMessages.required') }),
        description: zodModels.descriptionRequired,
        type: z.nativeEnum(ResourceType),
        osmData: zodModels.osmData.nullable(),
        startAt: z.coerce.date().nullable().optional(),
        endAt: z.coerce.date().nullable().optional(),
        file: zodModels.file.nullable().optional(),
        link: zodModels.website.nullable().optional(),
        format: z.nativeEnum(ResourceFormat),
        author: zodModels.maxLabel.optional(),
        thematics: zodModels.symfonyRelations
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

    const { errors, handleSubmit, isSubmitting, setFieldValue } = useForm<
      Partial<Resource | ResourceSubmission>
    >({
      initialValues: {
        ...resource,
        osmData: resource?.geoData
          ? GeocodingService.geoDataToGeocodingItem(resource?.geoData)
          : null
      },
      validationSchema: toTypedSchema(resourceSchema)
    })

    const form: any = {
      name: useField('name'),
      description: useField('description'),
      type: useField('type'),
      file: useField('file'),
      format: useField('format'),
      osmData: useField('osmData'),
      startAt: useField('startAt'),
      endAt: useField('endAt'),
      author: useField('author'),
      thematics: useField('thematics'),
      link: useField('link')
    }

    return { form, errors, handleSubmit, isSubmitting, setFieldValue }
  }
}

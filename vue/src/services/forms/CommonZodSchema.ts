import { i18n } from '@/plugins/i18n'
import type { GeoData } from '@/models/interfaces/geo/GeoData'
import type { FileObject } from '@/models/interfaces/object/FileObject'
import type { SymfonyRelation } from '@/models/interfaces/SymfonyRelation'
import { z, ZodType } from 'zod'
import { OsmType } from '@/models/enums/geo/OsmType'

export class CommonZodSchema {
  static getDefinitions() {
    const SymfonyRelationSchema = z.object({
      '@id': z.string(),
      name: z.string()
    }) satisfies ZodType<SymfonyRelation>
    const LatitudeSchema = z
      .number()
      .nullable()
      .optional()
      .refine(
        (value) => {
          if (!value) return true
          return value >= -90 && value <= 90
        },
        {
          message: i18n.t('forms.errorMessages.latLng')
        }
      )
    const LongitudeSchema = z
      .number()
      .nullable()
      .optional()
      .refine(
        (value) => {
          if (!value) return true
          return value >= -180 && value <= 180
        },
        {
          message: i18n.t('forms.errorMessages.latLng')
        }
      )

    const AbstractGeoDataSchema = z
      .object({
        osmId: z.string().nullable(),
        osmType: z.nativeEnum(OsmType).nullable(),
        name: z.string().nullable(),
        latitude: LatitudeSchema.nullable().optional(),
        longitude: LongitudeSchema.nullable().optional()
      })
      .transform((data) =>
        data?.osmId == null && data?.latitude == null
          ? null
          : {
              osmId: data.osmId ?? null,
              osmType: data.osmType ?? null,
              name: data.name ?? '',
              latitude: data.latitude ?? null,
              longitude: data.longitude ?? null
            }
      ) satisfies ZodType<GeoData | null>

    const NotNullableGeoDataSchema = AbstractGeoDataSchema.refine(
      (data) => {
        console.log('data', data)
        return (data?.latitude && data?.longitude) || (data?.osmId && data?.osmType) || data != null
      },
      { message: i18n.t('inputs.locationSelector.errors.notNull') }
    )
    const NullableGeoDataSchema = z
      .union([AbstractGeoDataSchema, z.literal(null), z.undefined()])
      .refine(
        (data) => {
          if (data == null) return true
          return (data.latitude && data.longitude) || (data.osmId && data.osmType)
        },
        { message: i18n.t('inputs.locationSelector.errors.notNull') }
      )

    return {
      symfonyRelations: z.array(SymfonyRelationSchema).nonempty({
        message: i18n.t('forms.errorMessages.required')
      }),
      symfonyRelation: SymfonyRelationSchema,
      geoData: NotNullableGeoDataSchema,
      geoDataNullable: NullableGeoDataSchema,
      file: this.createFileSchema({
        allowedTypes: [
          'application/pdf',
          'application/vnd.oasis.opendocument.spreadsheet',
          'application/vnd.ms-excel',
          'image/jpeg',
          'image/png',
          'image/webp'
        ],
        maxSize: 5000000
      }),
      qgisProject: this.createFileSchema({
        allowedTypes: ['application/zip', 'application/x-zip-compressed'],
        maxSize: 20000000
      }),
      website: z
        .string()
        .optional()
        .refine(
          (url) => {
            if (!url) return true
            const regex =
              /^(https?:\/\/)?(www\.)?[a-zA-Z0-9-]+\.[a-zA-Z]{2,}(\.[a-zA-Z]{2,})?(\/.*)?$/
            return regex.test(url)
          },
          {
            message: i18n.t('forms.errorMessages.url')
          }
        )
        .refine(
          (url) => {
            if (!url) return true
            const regex = /^https.*$/
            return regex.test(url)
          },
          {
            message: i18n.t('forms.errorMessages.https')
          }
        ),
      email: z
        .string()
        .optional()
        .refine(
          (value) =>
            value === undefined || value === '' || z.string().email().safeParse(value).success,
          {
            message: i18n.t('forms.errorMessages.email')
          }
        ),
      description: z
        .string()
        .min(1, { message: i18n.t('forms.errorMessages.required') })
        .min(50, { message: i18n.t('forms.errorMessages.minlength', { min: 50 }) })
        .optional(),
      descriptionRequired: z
        .string()
        .min(50, { message: i18n.t('forms.errorMessages.minlength', { min: 50 }) }),
      maxLabel: z
        .string()
        .max(100, { message: i18n.t('forms.errorMessages.maxlength', { max: 100 }) }),
      maxDescription: z
        .string()
        .max(500, { message: i18n.t('forms.errorMessages.maxlength', { max: 500 }) })
        .optional(),
      phone: z
        .string()
        .optional()
        .refine(
          (phone) => {
            if (!phone) return true
            const regex = /^(?:\+?[1-9]\d{1,3}[ .-]?)?(?:[1-9]\d{8}|0[1-9]\d{8})$/
            return regex.test(phone)
          },
          {
            message: i18n.t('forms.errorMessages.phone')
          }
        ),
      latString: LatitudeSchema,
      lngString: LongitudeSchema,
      latLngString: z
        .string()
        .optional()
        .refine(
          (value) => {
            if (!value) return true
            const regex = /^\s*(-?\d+(\.\d+)?)\s*,\s*(-?\d+(\.\d+)?)\s*$/
            const match = value.match(regex)
            if (!match) return false
            const lat = parseFloat(match[1])
            const lng = parseFloat(match[3])
            return lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180
          },
          {
            message: i18n.t('forms.errorMessages.latLng')
          }
        )
    }
  }

  static createFileSchema = ({
    allowedTypes,
    maxSize
  }: {
    allowedTypes: string[]
    maxSize: number
  }) => {
    return z
      .instanceof(File)
      .refine((file: File | null) => file != null, i18n.t('forms.errorMessages.required'))
      .refine(
        (file) => file.size < maxSize,
        i18n.t('forms.errorMessages.file.maxSize', { maxSize: maxSize / 1000000 })
      )
      .refine(
        (file) => this.checkFileType(file, allowedTypes),
        i18n.t('forms.errorMessages.file.wrongFormat', {
          formats: allowedTypes.join(', ')
        })
      )
      .or(
        z.object({
          '@id': z.string(),
          contentUrl: z.string()
        }) satisfies ZodType<FileObject>
      )
  }

  static checkFileType(file: File, allowedTypes: string[]) {
    if (file?.type) {
      const fileType = file.type
      return fileType ? allowedTypes.includes(fileType) : false
    }
    return false
  }
}

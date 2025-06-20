import { ODD } from '@/models/enums/contents/ODD'
import { Thematic } from '@/models/enums/contents/Thematic'
import type { Admin1Boundary, Admin3Boundary } from '@/models/interfaces/AdminBoundaries'
import type { GeoData } from '@/models/interfaces/geo/GeoData'
import type { FileObject } from '@/models/interfaces/object/FileObject'
import type { SymfonyRelation } from '@/models/interfaces/SymfonyRelation'
import { i18n } from '@/plugins/i18n'
import { number, z, ZodType } from 'zod'

export class CommonZodSchema {
  static getDefinitions() {
    const SymfonyRelationSchema = z.object({
      '@id': z.string(),
      name: z.string()
    }) satisfies ZodType<SymfonyRelation>

    const Admin1BoundarySchema = z.object({
      id: number(),
      '@id': z.string(),
      adm1Name: z.string(),
      adm1Pcode: z.string()
    }) satisfies ZodType<Admin1Boundary>

    const Admin3BoundarySchema = z.object({
      id: number(),
      '@id': z.string(),
      adm3Name: z.string(),
      adm3Pcode: z.string()
    }) satisfies ZodType<Admin3Boundary>

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

    const AbstractGeoDataSchema = z.any().transform((data) => {
      return data?.osmId == null && data?.latitude == null
        ? null
        : {
            osmId: data.osmId ?? null,
            osmType: data.osmType ?? null,
            name: data.name ?? '',
            latitude: data.latitude ?? null,
            longitude: data.longitude ?? null
          }
    }) satisfies ZodType<GeoData | null | undefined>

    const NotNullableGeoDataSchema = AbstractGeoDataSchema.refine(
      (data) => {
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
      symfonyRelations: z.array(SymfonyRelationSchema, {
        required_error: i18n.t('forms.errorMessages.required')
      }),
      symfonyRelation: SymfonyRelationSchema,
      admin1Boundaries: z.array(Admin1BoundarySchema),
      admin3Boundaries: z.array(Admin3BoundarySchema),
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
        maxSize: 20000000 // 20MB
      }),
      qgisProject: this.createFileSchema({
        allowedTypes: ['application/zip', 'application/x-zip-compressed'],
        maxSize: 50000000
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
      description: z.string({ required_error: i18n.t('forms.errorMessages.required') }).optional(),
      descriptionRequired: z
        .string({ required_error: i18n.t('forms.errorMessages.required') })
        .min(50, { message: i18n.t('forms.errorMessages.minlength', { min: 50 }) }),
      maxLabel: z
        .string()
        .max(100, { message: i18n.t('forms.errorMessages.maxlength', { max: 100 }) }),
      maxDescription: z.preprocess(
        (val) => (val === '' ? undefined : val),
        z
          .string()
          .max(1000, { message: i18n.t('forms.errorMessages.maxlength', { max: 1000 }) })
          .optional()
      ),
      phone: z.string().optional(),
      latString: LatitudeSchema,
      lngString: LongitudeSchema,
      thematics: z.array(z.nativeEnum(Thematic), {
        required_error: i18n.t('forms.errorMessages.required')
      }),
      odds: z.array(z.nativeEnum(ODD), {
        required_error: i18n.t('forms.errorMessages.required')
      }),
      banoc: z.preprocess(
        (val) => (val === '' ? undefined : val),
        z
          .string()
          .min(1, { message: i18n.t('forms.errorMessages.minlength', { min: 1 }) })
          .max(6, { message: i18n.t('forms.errorMessages.maxlength', { max: 6 }) })
          .optional()
      ),
      banocUrl: z.string().optional()
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

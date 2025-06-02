import { AdministrativeScope } from '@/models/enums/AdministrativeScope'
import { BeneficiaryType } from '@/models/enums/contents/BeneficiaryType'
import { ProjectFinancingType } from '@/models/enums/contents/ProjectFinancingType'
import { Status } from '@/models/enums/contents/Status'
import type { Project, ProjectSubmission } from '@/models/interfaces/Project'
import { i18n } from '@/plugins/i18n'
import { toTypedSchema } from '@vee-validate/zod'
import { useField, useForm } from 'vee-validate'
import { z } from 'zod'
import { CommonZodSchema } from '../forms/CommonZodSchema'

export class ProjectFormService {
  static getForm(project: Project | null) {
    const zodModels = CommonZodSchema.getDefinitions()
    // @ts-expect-error wrong zod type
    const projectSchema: z.ZodType<Partial<Project>> = z
      .object({
        name: z.string().min(1, { message: i18n.t('forms.errorMessages.required') }),
        description: zodModels.description,
        calendar: zodModels.maxDescription,
        deliverables: zodModels.maxDescription,
        focalPointName: zodModels.maxLabel,
        focalPointPosition: zodModels.maxLabel.optional(),
        focalPointEmail: zodModels.email,
        administrativeScopes: z.array(z.nativeEnum(AdministrativeScope)),
        admin1List: zodModels.admin1Boundaries.optional(),
        admin2List: zodModels.admin2Boundaries.optional(),
        admin3List: zodModels.admin3Boundaries.optional(),
        focalPointTel: zodModels.phone,
        geoData: zodModels.geoDataNullable.optional(),
        actor: zodModels.symfonyRelation.optional(),
        otherActor: z.string().optional(),
        status: z.nativeEnum(Status),
        financingTypes: z.array(z.nativeEnum(ProjectFinancingType)),
        otherFinancingType: z.string().optional(),
        actorsInCharge: z.array(zodModels.symfonyRelation).optional(),
        otherActorInCharge: z.string().optional(),
        thematics: zodModels.symfonyRelations,
        otherThematic: z.string().optional(),
        beneficiaryTypes: z.array(z.nativeEnum(BeneficiaryType)),
        otherBeneficiary: z.string().optional(),
        website: zodModels.website
      })
      .refine(
        (data) => {
          return (
            (data.actorsInCharge !== undefined && data.actorsInCharge.length > 0) ||
            !!data.otherActorInCharge
          )
        },
        {
          message: i18n.t('projects.form.errorMessages.noActor'),
          path: ['actorsInCharge', 'otherActorInCharge']
        }
      )
      .refine(
        (data) => {
          return (data.actor !== undefined && data.actor !== null) || !!data.otherActor
        },
        {
          message: i18n.t('projects.form.errorMessages.noActor'),
          path: ['actor', 'otherActor']
        }
      )

    const defaultValues: Partial<Project> = {
      name: '',
      description: '',
      deliverables: '',
      calendar: '',
      actorsInCharge: [],
      otherActorInCharge: '',
      financingTypes: [],
      administrativeScopes: [],
      focalPointName: '',
      focalPointPosition: '',
      focalPointEmail: '',
      focalPointTel: '',
      beneficiaryTypes: [],
      actor: undefined,
      otherActor: '',
      status: undefined,
      geoData: undefined,
      thematics: [],
      website: ''
    }

    const { errors, handleSubmit, isSubmitting } = useForm<Partial<Project | ProjectSubmission>>({
      initialValues: {
        ...defaultValues,
        ...project
      },
      validationSchema: toTypedSchema(projectSchema)
    })

    const form = {
      name: useField('name'),
      description: useField('description'),
      deliverables: useField('deliverables'),
      calendar: useField('calendar'),
      actorsInCharge: useField('actorsInCharge'),
      otherActorInCharge: useField('otherActorInCharge'),
      financingTypes: useField('financingTypes'),
      otherFinancingType: useField('otherFinancingType'),
      administrativeScopes: useField('administrativeScopes'),
      admin1List: useField('admin1List'),
      admin2List: useField('admin2List'),
      admin3List: useField('admin3List'),
      focalPointName: useField('focalPointName'),
      focalPointPosition: useField('focalPointPosition'),
      focalPointEmail: useField('focalPointEmail'),
      focalPointTel: useField('focalPointTel'),
      beneficiaryTypes: useField('beneficiaryTypes'),
      otherBeneficiary: useField('otherBeneficiary'),
      actor: useField('actor'),
      otherActor: useField('otherActor'),
      status: useField('status'),
      geoData: useField('geoData'),
      thematics: useField('thematics'),
      otherThematic: useField('otherThematic'),
      website: useField('website')
    }

    return { form, errors, handleSubmit, isSubmitting }
  }
}

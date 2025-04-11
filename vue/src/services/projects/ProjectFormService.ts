import type { Project, ProjectSubmission } from '@/models/interfaces/Project'
import { toTypedSchema } from '@vee-validate/zod'
import { useField, useForm } from 'vee-validate'
import { z } from 'zod'
import { i18n } from '@/plugins/i18n'
import { CommonZodSchema } from '../forms/CommonZodSchema'
import { Status } from '@/models/enums/contents/Status'
import { AdministrativeScope } from '@/models/enums/AdministrativeScope'
import { BeneficiaryType } from '@/models/enums/contents/BeneficiaryType'

export class ProjectFormService {
  static getForm(project: Project | null) {
    const zodModels = CommonZodSchema.getDefinitions()
    // @ts-expect-error wrong zod type
    const projectSchema: z.ZodType<Partial<Project>> = z.object({
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
      geoData: zodModels.geoData,
      actor: zodModels.symfonyRelation,
      status: z.nativeEnum(Status),
      donors: zodModels.symfonyRelations,
      contractingOrganisation: zodModels.symfonyRelation,
      thematics: zodModels.symfonyRelations,
      beneficiaryTypes: z.array(z.nativeEnum(BeneficiaryType)),
      website: zodModels.website
    })

    const { errors, handleSubmit, isSubmitting } = useForm<Partial<Project | ProjectSubmission>>({
      initialValues: project,
      validationSchema: toTypedSchema(projectSchema)
    })

    const form = {
      name: useField('name'),
      description: useField('description'),
      deliverables: useField('deliverables'),
      calendar: useField('calendar'),
      contractingOrganisation: useField('contractingOrganisation'),
      donors: useField('donors'),
      administrativeScopes: useField('administrativeScopes'),
      admin1List: useField('admin1List'),
      admin2List: useField('admin2List'),
      admin3List: useField('admin3List'),
      focalPointName: useField('focalPointName'),
      focalPointPosition: useField('focalPointPosition'),
      focalPointEmail: useField('focalPointEmail'),
      focalPointTel: useField('focalPointTel'),
      beneficiaryTypes: useField('beneficiaryTypes'),
      actor: useField('actor'),
      status: useField('status'),
      geoData: useField('geoData'),
      thematics: useField('thematics'),
      website: useField('website')
    }

    return { form, errors, handleSubmit, isSubmitting }
  }
}

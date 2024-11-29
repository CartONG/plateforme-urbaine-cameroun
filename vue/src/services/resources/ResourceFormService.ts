import type { Resource, ResourceSubmission } from "@/models/interfaces/Resource";
import { toTypedSchema } from "@vee-validate/zod";
import { useField, useForm } from "vee-validate";
import { z } from "zod";
import { i18n } from "@/assets/plugins/i18n";
import { CommonZodSchema } from "@/services/forms/CommonZodSchema";
import { ResourceType } from "@/models/enums/contents/ResourceType";

export class ResourceFormService {
    static getForm(resource: Resource | null) {
        const zodModels = CommonZodSchema.getDefinitions()
        const resourceSchema: z.ZodType<Partial<Resource>> = z.object({
            name: z
                .string()
                .min(1, { message: i18n.t('forms.errorMessages.required') }),
            description: zodModels.description,
            type: z.nativeEnum(ResourceType),
            // thematics: zodModels.symfonyRelations,
            link: zodModels.website,
        })

        const { errors, handleSubmit, isSubmitting } = useForm<Partial<Resource | ResourceSubmission>>({
            initialValues: resource,
            validationSchema: toTypedSchema(resourceSchema),
        });

        const form = {
            name: useField('name'),
            description: useField('description'),
            type: useField('type'),
            // thematics: useField('thematics'),
            link: useField('link'),
        }

        return {form, errors, handleSubmit, isSubmitting}
    }
}
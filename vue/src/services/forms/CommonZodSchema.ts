import { i18n } from "@/assets/plugins/i18n";
import type { SymfonyRelation } from "@/models/interfaces/SymfonyRelation";
import { z, ZodType } from "zod";

export class CommonZodSchema {

    static getDefinitions() {
        const SymfonyRelationSchema = z.object({
            "@id": z.string(),
            name: z.string()
        }) satisfies ZodType<SymfonyRelation>

        return {
            symfonyRelations: z.array(SymfonyRelationSchema).nonempty({
                    message: i18n.t('forms.errorMessages.required'),
                }),
            website: z
                .string()
                .optional()
                .refine((url) => {
                    if (!url) return true;
                    const regex = /^(https?:\/\/)?(www\.)?[a-zA-Z0-9-]+\.[a-zA-Z]{2,}(\.[a-zA-Z]{2,})?(\/.*)?$/;
                    return regex.test(url);
                }, {
                    message: i18n.t('forms.errorMessages.url'),
                }),
            email: z
            .string()
            .optional()
            .refine((value) => value === undefined || value === "" || z.string().email().safeParse(value).success, {
              message: i18n.t('forms.errorMessages.email'),
            }),
            phone: z.string().optional().refine((phone) => {
                if (!phone) return true;
                const regex = /^(?:\+?[1-9]\d{1,3}[ .-]?)?(?:[1-9]\d{8}|0[1-9]\d{8})$/;
                return regex.test(phone);
              }, {
                message: i18n.t('forms.errorMessages.phone'),
              }),
            latLngString: z.string().optional().refine((value) => {
                if (!value) return true;
                const regex = /^\s*(-?\d+(\.\d+)?)\s*,\s*(-?\d+(\.\d+)?)\s*$/;
                const match = value.match(regex);
                if (!match) return false;
                const lat = parseFloat(match[1]);
                const lng = parseFloat(match[3]);
                return lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180;
              }, {
                message: i18n.t('forms.errorMessages.latLng')
              })
        }
    }
}
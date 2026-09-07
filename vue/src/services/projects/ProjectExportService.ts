import type { Project } from '@/models/interfaces/Project'
import { Status } from '@/models/enums/contents/Status'
import { NotificationType } from '@/models/enums/app/NotificationType'
import { i18n } from '@/plugins/i18n'
import { addNotification } from '@/services/notifications/NotificationService'

interface ExportColumn {
  header: string
  key: string
  width: number
  numFmt?: string
}

const CURRENCY_FORMAT = '#,##0" FCFA"'
const DATE_FORMAT = 'dd/mm/yyyy'

export class ProjectExportService {
  // Champs jugés utiles pour un export terrain / reporting : identification,
  // contact, localisation, thématiques/financement, et le volet financier
  // (maturité, statut financier, budget, gap). La description/livrables sont
  // volontairement exclus (texte long, peu lisible en colonne Excel).
  private static readonly COLUMNS: ExportColumn[] = [
    { header: 'Nom du projet', key: 'name', width: 35 },
    { header: 'Porteur du projet', key: 'owner', width: 28 },
    { header: 'Point focal', key: 'focalPointName', width: 24 },
    { header: 'Email point focal', key: 'focalPointEmail', width: 28 },
    { header: 'Téléphone point focal', key: 'focalPointTel', width: 18 },
    { header: "Zone(s) d'intervention", key: 'administrativeScopes', width: 24 },
    { header: 'Localisation', key: 'location', width: 24 },
    { header: 'Thématiques', key: 'thematics', width: 28 },
    { header: 'Types de financement', key: 'financingTypes', width: 28 },
    { header: 'Maturité technique', key: 'technicalMaturity', width: 20 },
    { header: 'Statut financier', key: 'financialStatus', width: 22 },
    { header: 'Budget total', key: 'totalBudget', width: 18, numFmt: CURRENCY_FORMAT },
    { header: 'Fonds mobilisés', key: 'mobilizedFunds', width: 18, numFmt: CURRENCY_FORMAT },
    { header: 'Gap résiduel', key: 'residualGap', width: 18, numFmt: CURRENCY_FORMAT },
    { header: 'Site web', key: 'website', width: 30 },
    { header: 'Créé le', key: 'createdAt', width: 14, numFmt: DATE_FORMAT },
    { header: 'Mis à jour le', key: 'updatedAt', width: 14, numFmt: DATE_FORMAT }
  ]

    static async exportToExcel(projects: Project[]): Promise<void> {
        if (!projects || projects.length === 0) {
            addNotification(i18n.t('projects.export.empty'), NotificationType.WARNING)
            return
        }

        const [{ default: ExcelJS }, { saveAs }] = await Promise.all([
            import('exceljs'),
            import('file-saver')
        ])

        const workbook = new ExcelJS.Workbook()
        workbook.creator = 'Plateforme Urbaine Cameroun'
        workbook.created = new Date()
        // Force Excel/LibreOffice à recalculer les formules à l'ouverture,
        // en plus du "result" mis en cache ci-dessous (double sécurité).
        workbook.calcProperties = { fullCalcOnLoad: true }

        const groups = this.groupByStatus(projects)

        groups.forEach(({ status, projects: statusProjects }) => {
            const sheet = workbook.addWorksheet(this.getSheetName(status, workbook))
            sheet.columns = this.COLUMNS.map(({ header, key, width }) => ({ header, key, width }))
            sheet.getRow(1).font = { bold: true, color: { argb: 'FF1F3864' } }
            sheet.getRow(1).fill = {
            type: 'pattern',
            pattern: 'solid',
            fgColor: { argb: 'FFDCE6F1' }
            }
            sheet.views = [{ state: 'frozen', ySplit: 1 }]

            const totalCol = this.columnLetter('totalBudget')
            const mobilizedCol = this.columnLetter('mobilizedFunds')

            statusProjects.forEach((project) => {
            const rowIndex = sheet.rowCount + 1
            const hasBudgetData = project.totalBudget != null && project.mobilizedFunds != null
            const residualGapValue = hasBudgetData
                ? (project.totalBudget as number) - (project.mobilizedFunds as number)
                : null

            const row = sheet.addRow({
                name: project.name,
                owner: project.actor?.name || project.otherActor || '',
                focalPointName: project.focalPointName,
                focalPointEmail: project.focalPointEmail,
                focalPointTel: project.focalPointTel,
                administrativeScopes: (project.administrativeScopes || [])
                .map((scope) => this.translate(`actors.scope.${scope}`, scope))
                .join(', '),
                location: project.geoData?.name || '',
                thematics: (project.thematics || []).join(', '),
                financingTypes: (project.financingTypes || [])
                .map((type) => this.translate(`projects.financing.${type}`, type))
                .join(', '),
                technicalMaturity: project.technicalMaturity
                ? this.translate(
                    `projects.technicalMaturity.${project.technicalMaturity}`,
                    project.technicalMaturity
                    )
                : '',
                financialStatus: project.financialStatus
                ? this.translate(
                    `projects.financialStatus.${project.financialStatus}`,
                    project.financialStatus
                    )
                : '',
                totalBudget: project.totalBudget ?? null,
                mobilizedFunds: project.mobilizedFunds ?? null,
                // Formule + résultat mis en cache : le Gap s'affiche immédiatement
                // à l'ouverture, ET reste recalculable si l'utilisateur modifie
                // les montants dans le fichier exporté.
                residualGap: hasBudgetData
                ? { formula: `${totalCol}${rowIndex}-${mobilizedCol}${rowIndex}`, result: residualGapValue }
                : null,
                website: project.website,
                createdAt: project.createdAt ? new Date(project.createdAt) : null,
                updatedAt: project.updatedAt ? new Date(project.updatedAt) : null
            })

            // Formats appliqués directement sur les cellules de la ligne,
            // indépendamment de tout format défini au niveau colonne.
            ;['totalBudget', 'mobilizedFunds', 'residualGap'].forEach((key) => {
                row.getCell(key).numFmt = CURRENCY_FORMAT
            })
            row.getCell('createdAt').numFmt = DATE_FORMAT
            row.getCell('updatedAt').numFmt = DATE_FORMAT
            })

            // Conservé pour que les colonnes gardent leur format par défaut
            // même si une future ligne est ajoutée par un autre code appelant.
            this.COLUMNS.forEach(({ key, numFmt }) => {
            if (numFmt) sheet.getColumn(key).numFmt = numFmt
            })
        })

        const buffer = await workbook.xlsx.writeBuffer()
        const fileName = `projets_export_${this.formatDateForFileName(new Date())}.xlsx`
        saveAs(
            new Blob([buffer], {
            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            }),
            fileName
        )

        addNotification(i18n.t('projects.export.success'), NotificationType.SUCCESS)
    }

  /**
   * Regroupe les projets par statut réellement présent dans le jeu de
   * données exporté. Aucune énumération codée en dur : si demain un
   * cinquième statut apparaît dans l'enum Status ET dans les données,
   * sa feuille est créée automatiquement, sans toucher à ce fichier.
   */
  private static groupByStatus(projects: Project[]): { status: string; projects: Project[] }[] {
    const map = new Map<string, Project[]>()
    projects.forEach((project) => {
      const status = project.status as unknown as string
      if (!map.has(status)) map.set(status, [])
      map.get(status)!.push(project)
    })

    // Ordre d'affichage aligné sur l'enum Status quand connu, sinon
    // les statuts inconnus (futurs) sont ajoutés en fin, triés alphabétiquement.
    const knownOrder = Object.values(Status)

    return Array.from(map.entries())
      .map(([status, list]) => ({ status, projects: list }))
      .sort((a, b) => {
        const ai = knownOrder.indexOf(a.status as Status)
        const bi = knownOrder.indexOf(b.status as Status)
        if (ai === -1 && bi === -1) return a.status.localeCompare(b.status)
        if (ai === -1) return 1
        if (bi === -1) return -1
        return ai - bi
      })
  }

  private static getSheetName(status: string, workbook: any): string {
    let name = this.translate(`projects.status.${status}`, status)
    // Caractères interdits dans un nom de feuille Excel : \ / ? * [ ] :
    name = name.replace(/[\\/?*[\]:]/g, '').slice(0, 31) || 'Statut'

    let finalName = name
    let suffix = 2
    while (workbook.getWorksheet(finalName)) {
      finalName = `${name.slice(0, 28)} (${suffix})`
      suffix++
    }
    return finalName
  }

  private static translate(key: string, fallback: string): string {
    const translated = i18n.t(key)
    return translated === key ? fallback : translated
  }

  private static columnLetter(key: string): string {
    const index = this.COLUMNS.findIndex((col) => col.key === key)
    return String.fromCharCode(65 + index) // A, B, C... (17 colonnes < 26, donc un seul caractère suffit)
  }

  private static formatDateForFileName(date: Date): string {
    const pad = (n: number) => String(n).padStart(2, '0')
    return `${date.getFullYear()}${pad(date.getMonth() + 1)}${pad(date.getDate())}_${pad(date.getHours())}${pad(date.getMinutes())}`
  }
}
export interface KpiData {
  bookingsCount: number
  acceptedBookingsCount: number
  pendingBookingsCount: number
  cancelledRate: number
  occupancyRate: number
  activeUsersCount: number
  repeatUsersRate: number
  averageLeadTimeHours: number | null
  spaceAdminsCount: number
}
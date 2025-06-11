import { jwtDecode } from 'jwt-decode'
import { AuthenticationService } from './AuthenticationService'

export default class JwtCookie {
  public static isValid = async (): Promise<boolean> => {
    const cookieName = 'jwt_hp'
    const cookieValue = JwtCookie.getCookieValue(cookieName)
    if (cookieValue === undefined) {
      return await this.checkRefreshToken()
    }

    return !JwtCookie.isExpired(jwtDecode(cookieValue))
  }

  public static getCookieValue = (name: string): string | undefined => {
    return JwtCookie._allCookie()
      .find((row) => row.startsWith(name + '='))
      ?.split('=')[1]
  }

  public static getJwtExpirationDate = (cookieValue: string): string => {
    let exp = new Date().toUTCString()
    const decodedValue: any = jwtDecode(cookieValue)
    const expirationTime: number = decodedValue.exp
    if (expirationTime) {
      exp = new Date(expirationTime * 1000).toUTCString()
    }
    return exp
  }

  public static isExpired = (cookieValue: any): boolean => {
    if (!Object.prototype.hasOwnProperty.call(cookieValue, 'exp')) {
      return false
    }
    return new Date().valueOf() < cookieValue.exp
  }

  public static clearCookies(): void {
    document.cookie.split(';').forEach(function (c) {
      document.cookie = c
        .replace(/^ +/, '')
        .replace(/=.*/, '=;expires=' + new Date().toUTCString() + ';path=/')
    })
  }

  private static _allCookie = () => {
    return document.cookie.split('; ')
  }

  private static async checkRefreshToken() {
    const refreshToken = JwtCookie.getCookieValue('refresh_token')
    if (refreshToken) {
      await AuthenticationService.refreshAuthToken()
      return true
    }
    return false
  }

  public static deleteRefreshToken(): void {
    document.cookie = 'refresh_token=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/'
  }
}

import { DialogKey } from '@/models/enums/app/DialogKey'
import { StoresList } from '@/models/enums/app/StoresList'
import type {
  EmailVerifierValues,
  SignInValues,
  SignUpValues
} from '@/models/interfaces/auth/AuthenticationsValues'
import type { User, UserSubmission } from '@/models/interfaces/auth/User'
import { AuthenticationService } from '@/services/userAndAuth/AuthenticationService'
import JwtCookie from '@/services/userAndAuth/JWTCookie'
import { defineStore } from 'pinia'
import { computed, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import * as Sentry from '@sentry/browser'
import { UserRoles } from '@/models/enums/auth/UserRoles'
import FileUploader from '@/services/files/FileUploader'
import type { FileObject } from '@/models/interfaces/object/FileObject'
import { UserService } from '@/services/userAndAuth/UserService'
import { useApplicationStore } from './applicationStore'
import { AxiosError } from 'axios'

export const useUserStore = defineStore(StoresList.USER, () => {
  const router = useRouter()
  const route = useRoute()
  const currentUser = ref<User | null>(null)
  const errorWhileSignInOrSignUp = ref(false)
  const invalidAccount = ref(false)
  const userIsLogged = computed(() => currentUser.value !== null)
  const userHasRole = (role: UserRoles) =>
    userIsLogged.value && currentUser.value?.roles.includes(role)
  const userIsAdmin = () => userIsLogged.value && userHasRole(UserRoles.ADMIN)
  const userIsActorEditor = () => userHasRole(UserRoles.EDITOR_ACTORS)
  const userIsEditor = () => {
    return (
      userIsLogged.value &&
      (userHasRole(UserRoles.EDITOR_PROJECTS) ||
        userIsActorEditor() ||
        userHasRole(UserRoles.EDITOR_RESSOURCES) ||
        userHasRole(UserRoles.EDITOR_DATA))
    )
  }

  const signIn = async (values: SignInValues, hideDialog = true) => {
    try {
      await AuthenticationService.signIn(values)
      await setCurrentUser()
      errorWhileSignInOrSignUp.value = false
      if (currentUser.value?.hasSeenRequestedRoles === false) {
        await UserService.patchUser({ hasSeenRequestedRoles: true }, currentUser.value.id)
        return await router.replace({
          query: { ...route.query, dialog: DialogKey.AUTH_BECOME_MEMBER_ROLES }
        })
      }
      if (hideDialog) {
        await router.replace({ query: { ...route.query, dialog: undefined } })
      }
    } catch (err) {
      Sentry.captureException(err)
      if (err instanceof AxiosError && err.response?.status === 401) {
        invalidAccount.value = true
        return
      }
      errorWhileSignInOrSignUp.value = true
    }
  }

  const setCurrentUser = async () => {
    await getAuthenticatedUser()
    const appStore = useApplicationStore()
    appStore.getLikesList()
  }

  const getAuthenticatedUser = async () => {
    currentUser.value = (await AuthenticationService.getAuthenticatedUser()).data
  }

  const signUp = async (values: SignUpValues) => {
    try {
      await UserService.createUser(values)
      await router.replace({
        query: { ...route.query, dialog: DialogKey.AUTH_BECOME_MEMBER_THANKS }
      })
    } catch (err) {
      errorWhileSignInOrSignUp.value = true
      Sentry.captureException(err)
    }
  }

  const signOut = async () => {
    currentUser.value = null
    JwtCookie.clearCookies()
    router.push({ name: 'home' })
  }

  const verifyEmail = async (emailVerifierValues: EmailVerifierValues) => {
    await AuthenticationService.verifyEmail(emailVerifierValues)
    if (userIsLogged.value) {
      await getAuthenticatedUser()
    }
  }

  const checkAuthenticated = async () => {
    const jwtCookieIsValid = JwtCookie.isValid()
    if (jwtCookieIsValid) {
      setCurrentUser()
    }
  }

  const patchUser = async (
    values: Partial<UserSubmission>,
    updateLogo = false,
    logo: File | null = null
  ) => {
    if (values.logo && (values.logo as FileObject)['@id']) {
      values.logo = (values.logo as FileObject)['@id']
    }
    if (updateLogo && logo) {
      const newLogo = await FileUploader.uploadFile(logo)
      values.logo = newLogo['@id']
    }
    await UserService.patchUser(values as User, currentUser.value!.id)
    setCurrentUser()
    router.replace({ query: { ...route.query, dialog: undefined } })
  }
  return {
    userIsLogged,
    userIsAdmin,
    userIsEditor,
    userHasRole,
    userIsActorEditor,
    currentUser,
    errorWhileSignInOrSignUp,
    invalidAccount,
    signIn,
    signUp,
    signOut,
    verifyEmail,
    checkAuthenticated,
    patchUser
  }
})

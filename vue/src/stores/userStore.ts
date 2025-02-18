import { DialogKey } from '@/models/enums/app/DialogKey'
import { StoresList } from '@/models/enums/app/StoresList'
import type { SignInValues, SignUpValues } from '@/models/interfaces/auth/AuthenticationsValues'
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

export const useUserStore = defineStore(StoresList.USER, () => {
  const router = useRouter()
  const route = useRoute()
  const currentUser = ref<User | null>(null)
  const errorWhileSignInOrSignUp = ref(false)
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
      setCurrentUser()
      errorWhileSignInOrSignUp.value = false
      if (hideDialog) {
        router.replace({ query: { ...route.query, dialog: undefined } })
      }
    } catch (err) {
      Sentry.captureException(err)
      errorWhileSignInOrSignUp.value = true
    }
  }

  const setCurrentUser = async () => {
    currentUser.value = (await AuthenticationService.getAuthenticatedUser()).data
    const appStore = useApplicationStore()
    appStore.getLikesList()
  }

  const signUp = async (values: SignUpValues) => {
    try {
      await UserService.createUser(values)
      signIn(
        {
          email: values.email,
          password: values.plainPassword
        },
        false
      )
      router.replace({ query: { ...route.query, dialog: DialogKey.AUTH_BECOME_MEMBER_THANKS } })
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
    signIn,
    signUp,
    signOut,
    checkAuthenticated,
    patchUser
  }
})

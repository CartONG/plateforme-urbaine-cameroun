export interface SignInValues {
  email: string
  password: string
  stayLoggedIn?: boolean
}

export interface SignUpValues {
  firstName: string
  lastName: string
  email: string
  plainPassword: string
}

export interface EmailVerifierValues {
  token: string
  _hash: string
  email: string
  expiresAt: string
}

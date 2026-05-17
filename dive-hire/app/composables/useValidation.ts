export const useValidation = () => {
  const validateEmail = (email: string) => {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)
  }

  const validatePassword = (password: string) => {
    return password.length >= 6
  }

  const validateLogin = (email: string, password: string) => {
    return {
      email: validateEmail(email),
      password: validatePassword(password)
    }
  }

  const validateRegister = (email: string, password: string) => {
    return {

      email: validateEmail(email),
      password: validatePassword(password),
    }
  }

  return {
    validateEmail,
    validatePassword,
    validateLogin,
    validateRegister
  }
}
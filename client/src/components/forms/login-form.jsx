"use client"

import { DEFAULT_LOGIN_REDIRECT } from "@/route"
import { useState } from "react"
import { useRouter } from "next/navigation"
import { loginAction } from "@/app/actions/authActions" // Adjust path as needed
import LoginButton from "@/components/buttons/login-button"
import styles from "./login-form.module.css"

export default function LoginForm() {
  const [errorMessage, setErrorMessage] = useState("")
  const [emailErrorMessage, setEmailErrorMessage] = useState("")
  const [passwordErrorMessage, setPasswordErrorMessage] = useState("")
  const [successMessage, setSuccessMessage] = useState("")
  const router = useRouter()

  const handleSubmit = async (event) => {
    event.preventDefault() // Prevent default form submission

    setErrorMessage("") // Clear previous errors
    setEmailErrorMessage("")
    setPasswordErrorMessage("")
    setSuccessMessage("") // Clear previous success messages

    const formData = new FormData(event.currentTarget)
    const result = await loginAction(formData)
    
    if (result.success) {
      setSuccessMessage(result.success)
      // Redirect to a dashboard or protected page after successful login
      router.push(DEFAULT_LOGIN_REDIRECT) // Example redirect
    } else if (result.error) {
      if (result.error.email) {
        setEmailErrorMessage(result.error.email)
      } 
      
      if (result.error.password) {
        setPasswordErrorMessage(result.error.password)
      }  

      if (typeof result.error === "string") {
        setErrorMessage(result.error)
      }
    } else {
      setErrorMessage("An unexpected error occurred.")
    }
  }

  return (
    <div className={styles["login-form-container"]}>
      <form onSubmit={handleSubmit} className={styles["login-form"]}>
        <h2 className={styles["form-title"]}>Login</h2>

        {errorMessage && <div className={styles["error-message"]}>{errorMessage}</div>}
        {successMessage && <div className={styles["success-message"]}>{successMessage}</div>}

        <div className={styles["form-group"]}>
          <label htmlFor="email" className={styles["form-label"]}>
            Email
          </label>
          <input
            id="email"
            name="email"
            type="email"
            placeholder="your@example.com"
            required
            className={styles["form-input"]}
            />
        </div>
        {emailErrorMessage && <div className={styles["error-message"]}>{emailErrorMessage}</div>}

        <div className={styles["form-group"]}>
          <label htmlFor="password" className={styles["form-label"]}>
            Password
          </label>
          <input
            id="password"
            name="password"
            type="password"
            placeholder="********"
            required
            className={styles["form-input"]}
            />
        </div>
        {passwordErrorMessage && <div className={styles["error-message"]}>{passwordErrorMessage}</div>}

        <LoginButton />

        <div className={styles["form-footer"]}>
          <a href="/auth/signup" className={styles["forgot-link"]}>
            Don't have an account? Sign up
          </a>
        </div>
      </form>
    </div>
  )
}

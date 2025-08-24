"use client"

import { useState } from "react"
import { useRouter } from "next/navigation" // Used for redirection after successful signup
import { createUserAction } from "@/actions/userActions" // Updated import path
import {SignupButton} from "@/components/buttons/buttons"
import styles from "./customer-signup-form.module.css"
import { DEFAULT_LOGIN_REDIRECT } from "@/route"

export default function SignupForm() {
  const [errors, setErrors] = useState({}) // Object to hold field-specific errors
  const [successMessage, setSuccessMessage] = useState("")
  const router = useRouter()

  const handleSubmit = async (event) => {
    event.preventDefault() // Prevent default form submission

    setErrors({}) // Clear previous errors
    setSuccessMessage("") // Clear previous success messages

    const formData = new FormData(event.currentTarget)
    const result = await createUserAction(formData)
    console.log(result);
    if (result.success) {
      setSuccessMessage(result.success)
      setErrors({}) // Clear any lingering errors on success
      // Redirect to a login page or dashboard after successful signup
      router.push(DEFAULT_LOGIN_REDIRECT) // Updated redirect path
    } else if (result.error) {
      setErrors(result.error) // Set the error object
      setSuccessMessage("") // Clear success message on error
    } else {
      setErrors({ general: "An unexpected error occurred." })
      setSuccessMessage("")
    }
  }

  return (
    <div className={styles["signup-form-container"]}>
      <form onSubmit={handleSubmit} className={styles["signup-form"]}>
        <h2 className={styles["form-title"]}>Sign Up</h2>

        {errors.general && <div className={styles["error-message"]}>{errors.general}</div>}
        {errors.error && <div className={styles["error-message"]}>{errors.error}</div>}
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
          {errors.email && <p className={styles["field-error"]}>{errors.email}</p>}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="username" className={styles["form-label"]}>
            Username (Optional)
          </label>
          <input
            id="username"
            name="username"
            type="text"
            placeholder="Choose a username"
            className={styles["form-input"]}
          />
          {errors.username && <p className={styles["field-error"]}>{errors.username}</p>}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="first_name" className={styles["form-label"]}>
            First Name (Optional)
          </label>
          <input
            id="first_name"
            name="first_name"
            type="text"
            placeholder="Your first name"
            className={styles["form-input"]}
          />
          {errors.first_name && <p className={styles["field-error"]}>{errors.first_name}</p>}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="last_name" className={styles["form-label"]}>
            Last Name (Optional)
          </label>
          <input
            id="last_name"
            name="last_name"
            type="text"
            placeholder="Your last name"
            className={styles["form-input"]}
          />
          {errors.last_name && <p className={styles["field-error"]}>{errors.last_name}</p>}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="address" className={styles["form-label"]}>
            Address (Optional)
          </label>
          <input id="address" name="address" type="text" placeholder="Your address" className={styles["form-input"]} />
          {errors.address && <p className={styles["field-error"]}>{errors.address}</p>}
        </div>

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
          {errors.password && <p className={styles["field-error"]}>{errors.password}</p>}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="password_confirmation" className={styles["form-label"]}>
            Confirm Password
          </label>
          <input
            id="password_confirmation"
            name="password_confirmation"
            type="password"
            placeholder="********"
            required
            className={styles["form-input"]}
          />
          {errors.password_confirmation && <p className={styles["field-error"]}>{errors.password_confirmation}</p>}
        </div>

        <SignupButton />

        <div className={styles["form-footer"]}>
          Already have an account?{" "}
          <a href="/login" className={styles["login-link"]}>
            Login here
          </a>
        </div>
      </form>
    </div>
  )
}

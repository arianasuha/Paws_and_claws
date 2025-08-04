"use client"

import { useState } from "react"
import { useRouter } from "next/navigation"
import { createVetAction } from "@/app/actions/vetActions"
import VetSignupButton from "@/components/buttons/vet-signup-button"
import styles from "./vet-signup-form.module.css"

export default function VetSignupForm() {
  const [errors, setErrors] = useState({})
  const [successMessage, setSuccessMessage] = useState("")
  const router = useRouter()

  const handleSubmit = async (event) => {
    event.preventDefault()

    setErrors({})
    setSuccessMessage("")

    const formData = new FormData(event.currentTarget)
    const result = await createVetAction(formData)
    
    if (result.success) {
      setSuccessMessage(result.success)
      setErrors({})
      router.push("/login") // Redirect to login after successful vet signup
    } else if (result.error) {
      setErrors(result.error)
      setSuccessMessage("")
    } else {
      setErrors({ general: "An unexpected error occurred." })
      setSuccessMessage("")
    }
  }

  return (
    <div className={styles["vet-signup-form-container"]}>
      <form onSubmit={handleSubmit} className={styles["vet-signup-form"]}>
        <h2 className={styles["form-title"]}>Sign Up as a Vet</h2>

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
          <label htmlFor="clinic_name" className={styles["form-label"]}>
            Clinic Name
          </label>
          <input
            id="clinic_name"
            name="clinic_name"
            type="text"
            placeholder="Your clinic's name"
            required
            className={styles["form-input"]}
          />
          {errors.clinic_name && <p className={styles["field-error"]}>{errors.clinic_name}</p>}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="specialization" className={styles["form-label"]}>
            Specialization
          </label>
          <input
            id="specialization"
            name="specialization"
            type="text"
            placeholder="e.g., Dermatology, Orthopedics"
            required
            className={styles["form-input"]}
          />
          {errors.specialization && <p className={styles["field-error"]}>{errors.specialization}</p>}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="services_offered" className={styles["form-label"]}>
            Services Offered
          </label>
          <input
            id="services_offered"
            name="services_offered"
            type="text"
            placeholder="e.g., Vaccinations, Surgery"
            required
            className={styles["form-input"]}
          />
          {errors.services_offered && <p className={styles["field-error"]}>{errors.services_offered}</p>}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="working_hour" className={styles["form-label"]}>
            Working Hours
          </label>
          <input
            id="working_hour"
            name="working_hour"
            type="text"
            placeholder="e.g., Mon-Fri 9 AM - 5 PM"
            required
            className={styles["form-input"]}
          />
          {errors.working_hour && <p className={styles["field-error"]}>{errors.working_hour}</p>}
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

        <VetSignupButton />

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

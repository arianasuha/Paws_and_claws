"use client"

import { useState, useEffect } from "react"
import { useRouter } from "next/navigation"
import { 
  getServiceProviderAction, 
  updateServiceProviderAction, 
  deleteServiceProviderAction 
} from "@/actions/serviceActions"
import {UpdateButton, DeleteButton, ShowPetsButton} from "@/components/buttons/buttons"
import ConfirmationModal from "@/components/modals/confirmation-modal"
import styles from "./service-profile-form.module.css"

export default function ServiceProfileForm({ userId }) {
  const [formData, setFormData] = useState({
    email: "",
    username: "",
    first_name: "",
    last_name: "",
    address: "",
    service_type: "",
    service_desc: "",
    rating: "",
    rate_per_hour: "",
    password: "",
    password_confirmation: "",
  })
  const [errors, setErrors] = useState({})
  const [successMessage, setSuccessMessage] = useState("")
  const [isLoading, setIsLoading] = useState(true)
  const [isModalOpen, setIsModalOpen] = useState(false)
  const router = useRouter()

  useEffect(() => {
    async function fetchServiceData() {
      if (!userId) {
        setErrors({ general: "User ID not found. Please log in." })
        setIsLoading(false)
        return
      }
      const result = await getServiceProviderAction(userId)
   
      if (result.data) {
        setFormData({
          email: result.data.user.email || "",
          username: result.data.user.username || "",
          first_name: result.data.user.first_name || "",
          last_name: result.data.user.last_name || "",
          address: result.data.user.address || "",
          service_type: result.data.service_type || "",
          service_desc: result.data.service_desc || "",
          rating: result.data.rating || "",
          rate_per_hour: result.data.rate_per_hour || "",
          password: "",
          password_confirmation: "",
        })
        setSuccessMessage("")
  
      } else if (result.error) {
        setErrors({ general: result.error.detail || "Failed to load provider data." })
      }
      
      setIsLoading(false)
    }
    fetchServiceData()
  }, [userId])

  const handleChange = (e) => {
    const { name, value } = e.target
    setFormData((prev) => ({ ...prev, [name]: value }))
  }

  const handleSubmit = async (event) => {
    event.preventDefault()
    setErrors({})
    setSuccessMessage("")

    const dataToUpdate = new FormData(event.currentTarget) // Use FormData directly for server action

    // Basic client-side validation for password match if provided
    const password = dataToUpdate.get("password")
    const password_confirmation = dataToUpdate.get("password_confirmation")

    if (password && password !== password_confirmation) {
      setErrors({ password_confirmation: "Passwords do not match." })
      return
    }

    const result = await updateServiceProviderAction(userId, dataToUpdate)

    if (result.success) {
      setSuccessMessage(result.success)
      // Re-fetch data to ensure UI is up-to-date after successful update
      const updatedResult = await getServiceProviderAction(userId)
      if (updatedResult.data) {
        setFormData({
          email: result.data.user.email || "",
          username: result.data.user.username || "",
          first_name: result.data.user.first_name || "",
          last_name: result.data.user.last_name || "",
          address: result.data.user.address || "",
          service_type: updatedResult.data.service_type || "",
          service_desc: updatedResult.data.service_desc || "",
          rating: updatedResult.data.rating || "",
          rate_per_hour: updatedResult.data.rate_per_hour || "",
          password: "",
          password_confirmation: "",
        })
      }
    } else if (result.error) {
      setErrors(result.error)
    } else {
      setErrors({ general: "An unexpected error occurred during update." })
    }
  }

  const handleDeleteClick = () => {
    setIsModalOpen(true)
  }

  const handleConfirmDelete = async () => {
    setIsModalOpen(false)
    const result = await deleteServiceProviderAction(userId)
    if (result.success) {
      alert(result.success)
      router.push("/login") // Redirect to login after deletion
    } else if (result.error) {
      setErrors({ general: result.error.general || "Failed to delete account." })
    }
  }

  if (isLoading) {
    return <div className={styles["loading-message"]}>Loading service provider profile...</div>
  }

  return (
    <div className={styles["profile-form-container"]}>
      <form onSubmit={handleSubmit} className={styles["profile-form"]}>
        <h2 className={styles["form-title"]}>Service Provider Profile</h2>

        {errors.general && <div className={styles["error-message"]}>{errors.general}</div>}
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
            value={formData.email}
            onChange={handleChange}
            required
            className={styles["form-input"]}
          />
          {errors.email && <p className={styles["field-error"]}>{errors.email}</p>}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="username" className={styles["form-label"]}>
            Username
          </label>
          <input
            id="username"
            name="username"
            type="text"
            placeholder="Choose a username"
            value={formData.username}
            onChange={handleChange}
            className={styles["form-input"]}
          />
          {errors.username && <p className={styles["field-error"]}>{errors.username}</p>}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="first_name" className={styles["form-label"]}>
            First Name
          </label>
          <input
            id="first_name"
            name="first_name"
            type="text"
            placeholder="Your first name"
            value={formData.first_name}
            onChange={handleChange}
            className={styles["form-input"]}
          />
          {errors.first_name && <p className={styles["field-error"]}>{errors.first_name}</p>}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="last_name" className={styles["form-label"]}>
            Last Name
          </label>
          <input
            id="last_name"
            name="last_name"
            type="text"
            placeholder="Your last name"
            value={formData.last_name}
            onChange={handleChange}
            className={styles["form-input"]}
          />
          {errors.last_name && <p className={styles["field-error"]}>{errors.last_name}</p>}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="address" className={styles["form-label"]}>
            Address
          </label>
          <input
            id="address"
            name="address"
            type="text"
            placeholder="Your address"
            value={formData.address}
            onChange={handleChange}
            className={styles["form-input"]}
          />
          {errors.address && <p className={styles["field-error"]}>{errors.address}</p>}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="service_type" className={styles["form-label"]}>
            Service Type
          </label>
          <select
            id="service_type"
            name="service_type"
            value={formData.service_type}
            onChange={handleChange}
            required
            className={styles["form-input"]}
          >
            <option value="" disabled>
              Select a service type
            </option>
            <option value="walker">Walker</option>
            <option value="groomer">Groomer</option>
            <option value="trainer">Trainer</option>
          </select>
          {errors.service_type && (
            <p className={styles["field-error"]}>{errors.service_type}</p>
          )}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="service_desc" className={styles["form-label"]}>
            Service Description
          </label>
          <input
            id="service_desc"
            name="service_desc"
            type="text"
            placeholder="Service Description"
            value={formData.service_desc}
            onChange={handleChange}
            required
            className={styles["form-input"]}
          />
          {errors.service_desc && (
            <p className={styles["field-error"]}>{errors.service_desc}</p>
          )}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="rate_per_hour" className={styles["form-label"]}>
            Rate Per Hour
          </label>
          <input
            id="rate_per_hour"
            name="rate_per_hour"
            type="float"
            placeholder="10.00"
            value={formData.rate_per_hour}
            onChange={handleChange}
            required
            className={styles["form-input"]}
          />
          {errors.rate_per_hour && (
            <p className={styles["field-error"]}>{errors.rate_per_hour}</p>
          )}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="rating" className={styles["form-label"]}>
            Rating
          </label>
          <input
            id="rating"
            name="rating"
            type="float"
            value={formData.rating}
            onChange={handleChange}
            disabled
            className={styles["form-input"]}
          />
          {errors.rating && (
            <p className={styles["field-error"]}>{errors.rating}</p>
          )}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="password" className={styles["form-label"]}>
            New Password (leave blank to keep current)
          </label>
          <input
            id="password"
            name="password"
            type="password"
            placeholder="********"
            value={formData.password}
            onChange={handleChange}
            className={styles["form-input"]}
          />
          {errors.password && <p className={styles["field-error"]}>{errors.password}</p>}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="password_confirmation" className={styles["form-label"]}>
            Confirm New Password
          </label>
          <input
            id="password_confirmation"
            name="password_confirmation"
            type="password"
            placeholder="********"
            value={formData.password_confirmation}
            onChange={handleChange}
            className={styles["form-input"]}
          />
          {errors.password_confirmation && <p className={styles["field-error"]}>{errors.password_confirmation}</p>}
        </div>

        <UpdateButton />
        <ShowPetsButton />
        <DeleteButton onClick={handleDeleteClick} />
      </form>

      <ConfirmationModal
        isOpen={isModalOpen}
        onClose={() => setIsModalOpen(false)}
        onConfirm={handleConfirmDelete}
        title="Confirm Deletion"
        message="Are you sure you want to delete your service provider account? This action cannot be undone."
      />
    </div>
  )
}

"use client"

import { useState, useEffect } from "react"
import { useRouter } from "next/navigation"
import { getVetAction, updateVetAction, deleteVetAction } from "@/app/actions/vetActions"
import UpdateButton from "@/components/buttons/update-button"
import DeleteButton from "@/components/buttons/delete-button"
import ShowPetsButton from "@/components/buttons/show-pets-button" // Vets might also see pets they've treated
import ConfirmationModal from "@/components/modals/confirmation-modal"
import styles from "./vet-profile-form.module.css"

export default function VetProfileForm({ userId }) {
  const [formData, setFormData] = useState({
    email: "",
    username: "",
    first_name: "",
    last_name: "",
    address: "",
    clinic_name: "",
    specialization: "",
    services_offered: "",
    working_hour: "",
    password: "",
    password_confirmation: "",
  })
  const [errors, setErrors] = useState({})
  const [successMessage, setSuccessMessage] = useState("")
  const [isLoading, setIsLoading] = useState(true)
  const [isModalOpen, setIsModalOpen] = useState(false)
  const router = useRouter()

  useEffect(() => {
    async function fetchVetData() {
      if (!userId) {
        setErrors({ general: "User ID not found. Please log in." })
        setIsLoading(false)
        return
      }
      const result = await getVetAction(userId)
      if (result.data) {
        setFormData({
          email: result.data.user.email || "",
          username: result.data.user.username || "",
          first_name: result.data.user.first_name || "",
          last_name: result.data.user.last_name || "",
          address: result.data.user.address || "",
          clinic_name: result.data.clinic_name || "",
          specialization: result.data.specialization || "",
          services_offered: result.data.services_offered || "",
          working_hour: result.data.working_hour || "",
          password: "",
          password_confirmation: "",
        })
        setSuccessMessage("")
      } else if (result.error) {
        setErrors({ general: result.error.detail || "Failed to load vet data." })
      }
      setIsLoading(false)
    }
    fetchVetData()
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

    const result = await updateVetAction(userId, dataToUpdate)

    if (result.success) {
      setSuccessMessage(result.success)
      // Re-fetch data to ensure UI is up-to-date after successful update
      const updatedResult = await getVetAction(userId)
      if (updatedResult.data) {
        setFormData({
          email: result.data.user.email || "",
          username: result.data.user.username || "",
          first_name: result.data.user.first_name || "",
          last_name: result.data.user.last_name || "",
          address: result.data.user.address || "",
          clinic_name: updatedResult.data.clinic_name || "",
          specialization: updatedResult.data.specialization || "",
          services_offered: updatedResult.data.services_offered || "",
          working_hour: updatedResult.data.working_hour || "",
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
    const result = await deleteVetAction(userId)
    if (result.success) {
      alert(result.success)
      router.push("/login") // Redirect to login after deletion
    } else if (result.error) {
      setErrors({ general: result.error.general || "Failed to delete account." })
    }
  }

  if (isLoading) {
    return <div className={styles["loading-message"]}>Loading vet profile...</div>
  }

  return (
    <div className={styles["profile-form-container"]}>
      <form onSubmit={handleSubmit} className={styles["profile-form"]}>
        <h2 className={styles["form-title"]}>Veterinarian Profile</h2>

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
          <label htmlFor="clinic_name" className={styles["form-label"]}>
            Clinic Name
          </label>
          <input
            id="clinic_name"
            name="clinic_name"
            type="text"
            placeholder="Your clinic's name"
            value={formData.clinic_name}
            onChange={handleChange}
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
            value={formData.specialization}
            onChange={handleChange}
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
            value={formData.services_offered}
            onChange={handleChange}
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
            value={formData.working_hour}
            onChange={handleChange}
            required
            className={styles["form-input"]}
          />
          {errors.working_hour && <p className={styles["field-error"]}>{errors.working_hour}</p>}
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
        message="Are you sure you want to delete your vet account? This action cannot be undone."
      />
    </div>
  )
}

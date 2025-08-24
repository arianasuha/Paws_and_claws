"use client"

import { useState, useEffect } from "react"
import { useRouter } from "next/navigation"
import { getUserAction, updateUserAction, deleteUserAction } from "@/actions/userActions" // Import updateUserAction directly for form submission
import {
  UpdateButton,
  DeleteButton,
  ShowPetsButton
} from "@/components/buttons/buttons"
import ConfirmationModal from "@/components/modals/confirmation-modal"
import styles from "./customer-profile-form.module.css"

export default function CustomerProfileForm({ userId }) {
  const [formData, setFormData] = useState({
    email: "",
    username: "",
    first_name: "",
    last_name: "",
    address: "",
    password: "",
    password_confirmation: "",
  })
  const [errors, setErrors] = useState({})
  const [successMessage, setSuccessMessage] = useState("")
  const [isLoading, setIsLoading] = useState(true)
  const [isModalOpen, setIsModalOpen] = useState(false)
  const router = useRouter()

  useEffect(() => {
    async function fetchUserData() {
      if (!userId) {
        setErrors({ general: "User ID not found. Please log in." })
        setIsLoading(false)
        return
      }
      const result = await getUserAction(userId)
      if (result.data) {
        setFormData({
          email: result.data.email || "",
          username: result.data.username || "",
          first_name: result.data.first_name || "",
          last_name: result.data.last_name || "",
          address: result.data.address || "",
          password: "", // Never pre-fill passwords
          password_confirmation: "", // Never pre-fill passwords
        })
        setSuccessMessage("")
      } else if (result.error) {
        setErrors({ general: result.error.detail || "Failed to load user data." })
      }
      setIsLoading(false)
    }
    fetchUserData()
  }, [userId])

  const handleChange = (e) => {
    const { name, value } = e.target
    setFormData((prev) => ({ ...prev, [name]: value }))
  }

  const handleSubmit = async (event) => {
    event.preventDefault()
    setErrors({})
    setSuccessMessage("")

    const dataToUpdate = { ...formData }
    // Remove empty password fields if not being updated
    if (!dataToUpdate.password) delete dataToUpdate.password
    if (!dataToUpdate.password_confirmation) delete dataToUpdate.password_confirmation

    // Basic client-side validation for password match if provided
    if (dataToUpdate.password && dataToUpdate.password !== dataToUpdate.password_confirmation) {
      setErrors({ password_confirmation: "Passwords do not match." })
      return
    }

    try {
      // Directly call the API client's put method for update
      const result = await updateUserAction(userId, dataToUpdate)

      if (result.error) {
        setErrors(result.error)
      } else {
        setSuccessMessage(result.success || "Profile updated successfully!")
        // Re-fetch data to ensure UI is up-to-date after successful update
        const updatedResult = await getUserAction(userId)
        if (updatedResult.data) {
          setFormData({
            email: updatedResult.data.email || "",
            username: updatedResult.data.username || "",
            first_name: updatedResult.data.first_name || "",
            last_name: updatedResult.data.last_name || "",
            address: updatedResult.data.address || "",
            password: "",
            password_confirmation: "",
          })
        }
      }
    } catch (error) {
      setErrors({ general: error.message || "An error occurred during update." })
    }
  }

  const handleDeleteClick = () => {
    setIsModalOpen(true)
  }

  const handleConfirmDelete = async () => {
    setIsModalOpen(false)
    const result = await deleteUserAction(userId)
    if (result.success) {
      alert(result.success)
      router.push("/login") // Redirect to login after deletion
    } else if (result.error) {
      setErrors({ general: result.error.general || "Failed to delete account." })
    }
  }

  if (isLoading) {
    return <div className={styles["loading-message"]}>Loading profile...</div>
  }

  return (
    <div className={styles["profile-form-container"]}>
      <form onSubmit={handleSubmit} className={styles["profile-form"]}>
        <h2 className={styles["form-title"]}>Customer Profile</h2>

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
        message="Are you sure you want to delete your account? This action cannot be undone."
      />
    </div>
  )
}

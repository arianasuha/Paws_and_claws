"use client"

import { useState, useEffect } from "react"
import { createAppointmentAction } from "@/actions/appointmentActions"
import { getPetsAction } from "@/actions/petActions"
import { getUserIdAction } from "@/actions/authActions"
import styles from "./BookingForm.module.css"

export default function BookingForm({ providerId, providerType, onSuccess, onCancel }) {
  const [formData, setFormData] = useState({
    pet: "",
    app_date: "",
    app_time: "",
    visit_reason: "",
  })
  const [errors, setErrors] = useState({})
  const [successMessage, setSuccessMessage] = useState("")
  const [isSubmitting, setIsSubmitting] = useState(false)
  const [pets, setPets] = useState([])

  useEffect(() => {
    const fetchUserPets = async () => {
      try {
        const response = await getPetsAction({my_pets: 1})
        
        if (response.error) {
          console.error("Failed to fetch user pets:", response.error)
          setPets([{ name: "Failed to fetch user pets" }])
        } else {
          setPets(response.data)
        }
      } catch (error) {
        console.error("Failed to fetch user pets:", error)
      }
    }

    fetchUserPets()
  }, [])

  const handleInputChange = (e) => {
    const { name, value } = e.target
    setFormData((prev) => ({
      ...prev,
      [name]: value,
    }))

    // Clear error when user starts typing
    if (errors[name]) {
      setErrors((prev) => ({
        ...prev,
        [name]: "",
      }))
    }
  }

  const validateForm = () => {
    const newErrors = {}

    if (!formData.pet) {
      newErrors.pet = "Please select a pet"
    }

    if (!formData.app_date) {
      newErrors.app_date = "Please select an appointment date"
    } else {
      const selectedDate = new Date(formData.app_date)
      const today = new Date()
      today.setHours(0, 0, 0, 0)

      if (selectedDate < today) {
        newErrors.app_date = "Appointment date cannot be in the past"
      }
    }

    if (!formData.app_time) {
      newErrors.app_time = "Please select an appointment time"
    }

    if (!formData.visit_reason.trim()) {
      newErrors.visit_reason = "Please provide a reason for the visit"
    }

    return newErrors
  }

  const handleSubmit = async (e) => {
    e.preventDefault()

    const validationErrors = validateForm()
    if (Object.keys(validationErrors).length > 0) {
      setErrors(validationErrors)
      return
    }

    setIsSubmitting(true)
    setErrors({})
    setSuccessMessage("")

    try {
      const appointmentData = {
        ...formData,
        provider: providerId,
      }

      const response = await createAppointmentAction(appointmentData)

      if (response.error) {
        setErrors(response.error)
      } else if (response.success) {
        setSuccessMessage("Appointment booked successfully!")
        setTimeout(() => {
          onSuccess()
        }, 1500)
      }
    } catch (error) {
      setErrors({ error: "Failed to book appointment. Please try again." })
    } finally {
      setIsSubmitting(false)
    }
  }

  const getTomorrowDate = () => {
    const tomorrow = new Date()
    tomorrow.setDate(tomorrow.getDate() + 1)
    return tomorrow.toISOString().split("T")[0]
  }

  return (
    <form onSubmit={handleSubmit} className={styles.form}>
      {successMessage && <div className={styles.success}>{successMessage}</div>}

      {errors.error && <div className={styles.error}>{errors.error}</div>}

      <div className={styles.field}>
        <label htmlFor="pet" className={styles.label}>
          Select Pet *
        </label>
        <select
          id="pet"
          name="pet"
          value={formData.pet}
          onChange={handleInputChange}
          className={`${styles.select} ${errors.pet ? styles.fieldError : ""}`}
          disabled={isSubmitting}
        >
          <option value="">Choose a pet...</option>
          {pets.map((pet) => (
            <option key={pet.id} value={pet.id}>
              {pet.name} ({pet.species})
            </option>
          ))}
        </select>
        {errors.pet && <span className={styles.errorText}>{errors.pet}</span>}
      </div>

      <div className={styles.field}>
        <label htmlFor="app_date" className={styles.label}>
          Appointment Date *
        </label>
        <input
          type="date"
          id="app_date"
          name="app_date"
          value={formData.app_date}
          onChange={handleInputChange}
          min={getTomorrowDate()}
          className={`${styles.input} ${errors.app_date ? styles.fieldError : ""}`}
          disabled={isSubmitting}
        />
        {errors.app_date && <span className={styles.errorText}>{errors.app_date}</span>}
      </div>

      <div className={styles.field}>
        <label htmlFor="app_time" className={styles.label}>
          Appointment Time *
        </label>
        <select
          id="app_time"
          name="app_time"
          value={formData.app_time}
          onChange={handleInputChange}
          className={`${styles.select} ${errors.app_time ? styles.fieldError : ""}`}
          disabled={isSubmitting}
        >
          <option value="">Choose a time...</option>
          <option value="09:00">9:00 AM</option>
          <option value="10:00">10:00 AM</option>
          <option value="11:00">11:00 AM</option>
          <option value="12:00">12:00 PM</option>
          <option value="13:00">1:00 PM</option>
          <option value="14:00">2:00 PM</option>
          <option value="15:00">3:00 PM</option>
          <option value="16:00">4:00 PM</option>
          <option value="17:00">5:00 PM</option>
        </select>
        {errors.app_time && <span className={styles.errorText}>{errors.app_time}</span>}
      </div>

      <div className={styles.field}>
        <label htmlFor="visit_reason" className={styles.label}>
          Reason for Visit *
        </label>
        <textarea
          id="visit_reason"
          name="visit_reason"
          value={formData.visit_reason}
          onChange={handleInputChange}
          placeholder="Please describe the reason for your visit..."
          rows={4}
          className={`${styles.textarea} ${errors.visit_reason ? styles.fieldError : ""}`}
          disabled={isSubmitting}
        />
        {errors.visit_reason && <span className={styles.errorText}>{errors.visit_reason}</span>}
      </div>

      <div className={styles.actions}>
        <button type="button" onClick={onCancel} className={styles.cancelButton} disabled={isSubmitting}>
          Cancel
        </button>
        <button type="submit" className={styles.submitButton} disabled={isSubmitting}>
          {isSubmitting ? "Booking..." : "Book Appointment"}
        </button>
      </div>
    </form>
  )
}

"use client"

import { useState } from "react"
import styles from "./UpdateAppointmentForm.module.css"

export default function UpdateAppointmentForm({ appointment, onSubmit, isSubmitting }) {
  const [formData, setFormData] = useState({
    status: appointment?.status || "pending",
  })

  const handleSubmit = async (e) => {
    e.preventDefault()
    await onSubmit(formData)
  }

  const handleChange = (e) => {
    const { name, value } = e.target
    setFormData((prev) => ({
      ...prev,
      [name]: value,
    }))
  }

  return (
    <form onSubmit={handleSubmit} className={styles.form}>
      <div className={styles.field}>
        <label htmlFor="status" className={styles.label}>
          Status
        </label>
        <select
          id="status"
          name="status"
          value={formData.status}
          onChange={handleChange}
          className={styles.select}
          required
        >
          <option value="pending">Pending</option>
          <option value="accepted">Accepted</option>
          <option value="canceled">Canceled</option>
        </select>
      </div>

      <div className={styles.actions}>
        <button type="submit" disabled={isSubmitting} className={styles.submitButton}>
          {isSubmitting ? "Updating..." : "Update Appointment"}
        </button>
      </div>
    </form>
  )
}

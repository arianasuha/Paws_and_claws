"use client"

import { useState } from "react"
import UpdateAppointmentForm from "@/components/forms/UpdateAppointmentForm"
import styles from "./UpdateAppointmentModal.module.css"

export default function UpdateAppointmentModal({ appointment, onClose, onSubmit }) {
  const [isSubmitting, setIsSubmitting] = useState(false)

  const handleSubmit = async (formData) => {
    setIsSubmitting(true)
    await onSubmit(formData)
    setIsSubmitting(false)
  }

  const handleBackdropClick = (e) => {
    if (e.target === e.currentTarget) {
      onClose()
    }
  }

  return (
    <div className={styles.backdrop} onClick={handleBackdropClick}>
      <div className={styles.modal}>
        <div className={styles.header}>
          <h2 className={styles.title}>Update Appointment</h2>
          <button className={styles.closeButton} onClick={onClose}>
            ×
          </button>
        </div>

        <div className={styles.content}>
          <UpdateAppointmentForm appointment={appointment} onSubmit={handleSubmit} isSubmitting={isSubmitting} />
        </div>
      </div>
    </div>
  )
}

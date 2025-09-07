"use client"

import { useState } from "react"
import { updateMedicalLogAction } from "@/actions/medicalLogActions"
import UpdateMedicalLogForm from "@/components/forms/UpdateMedicalLogForm"
import styles from "./UpdatePetModal.module.css"

export default function UpdatePetMarketModal({ isOpen, onClose, onSuccess, medicalLog }) {
  const [success, setSuccess] = useState("")
  const [errors, setErrors] = useState({})

  const handleSubmit = async (formData) => {
    setSuccess("")
    setErrors({})

    try {
      const result = await updateMedicalLogAction(medicalLog.id, formData)
      if (result.success) {
        setSuccess(result.success)
        setTimeout(() => {
          onClose();
          onSuccess();
        }, 1500)
      } else {
        setErrors(result.error)
      }
    } catch (err) {
      setErrors("An error occurred while creating the medicalLog")
    }
  }

  if (!isOpen || !medicalLog) return null

  return (
    <div className={styles.modalOverlay} onClick={onClose}>
      <div className={styles.modalContent} onClick={(e) => e.stopPropagation()}>
        <div className={styles.modalHeader}>
          <h2 className={styles.modalTitle}>Update Medical Log</h2>
          <button className={styles.closeButton} onClick={onClose}>
            ×
          </button>
        </div>

        <UpdateMedicalLogForm
          medicalLog={medicalLog}
          onClose={onClose} 
          action={handleSubmit}
          success={success}
          errors={errors}
          />

      </div>
    </div>
  )
}

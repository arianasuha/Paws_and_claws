"use client"

import { useState } from "react"
import { useRouter } from "next/navigation"
import { createMedicalLogAction } from "@/actions/medicalLogActions"
import MedicalForm from "@/components/forms/MedicalForm"
import styles from "./CreateMedicalModal.module.css"

export default function CreateMedicalModal({ isOpen, onClose, petId }) {
  const [errors, setErrors] = useState({})
  const [successMessage, setSuccessMessage] = useState("")
  const [isSubmitting, setIsSubmitting] = useState(false)
  const router = useRouter()

  if (!isOpen) return null

  const handleSubmit = async (formData, type) => {
    setIsSubmitting(true)
    setErrors({})
    setSuccessMessage("")

    const submitData = {
      pet: petId,
      ...formData,
    }

    const result = await createMedicalLogAction(submitData)

    if (result.success) {
      setSuccessMessage(result.success)
      setErrors({})
      setTimeout(() => {
        onClose()
        router.push(`/pet-medical-log/`)
      }, 2000)
    } else if (result.error) {
      setErrors(result.error)
      setSuccessMessage("")
    }

    setIsSubmitting(false)
  }

  return (
    <div className={styles.overlay}>
      <div className={styles.modal}>
        <div className={styles.header}>
          <h2 className={styles.title}>Create Medical Log</h2>
          <button className={styles.closeButton} onClick={onClose}>
            ×
          </button>
        </div>

        <div className={styles.content}>
          {successMessage && <div className={styles.success}>{successMessage}</div>}

          <MedicalForm onSubmit={handleSubmit} errors={errors} isSubmitting={isSubmitting} />
        </div>
      </div>
    </div>
  )
}

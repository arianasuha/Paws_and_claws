"use client"

import { useState } from "react"
import { useRouter } from "next/navigation"
import { createLostPetAction } from "@/actions/lostPetActions"
import LostPetForm from "@/components/forms/LostPetForm"
import styles from "./CreateLostPetModal.module.css"

export default function CreateLostPetModal({ isOpen, onClose, petId }) {
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
      status: "missing",
    }

    const result = await createLostPetAction(submitData)

    if (result.success) {
      setSuccessMessage(result.success)
      setErrors({})
      setTimeout(() => {
        onClose()
        router.push(`/lost-pet/`)
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
          <h2 className={styles.title}>Create Pet Market Listing</h2>
          <button className={styles.closeButton} onClick={onClose}>
            ×
          </button>
        </div>

        <div className={styles.content}>
          {successMessage && <div className={styles.success}>{successMessage}</div>}

          <LostPetForm onSubmit={handleSubmit} errors={errors} isSubmitting={isSubmitting} />
        </div>
      </div>
    </div>
  )
}

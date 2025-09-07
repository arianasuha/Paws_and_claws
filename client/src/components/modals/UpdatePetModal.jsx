"use client"

import { useState } from "react"
import { updatePetAction } from "@/actions/petActions"
import UpdatePetForm from "@/components/forms/UpdatePetForm"
import styles from "./UpdatePetModal.module.css"

export default function UpdatePetModal({ isOpen, onClose, onSuccess, pet }) {
  const [success, setSuccess] = useState("")
  const [errors, setErrors] = useState({})

  const handleSubmit = async (formData) => {
    setSuccess("")
    setErrors({})

    try {
      const result = await updatePetAction(pet.id, formData)
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
      setErrors("An error occurred while creating the pet")
    }
  }

  if (!isOpen || !pet) return null

  return (
    <div className={styles.modalOverlay} onClick={onClose}>
      <div className={styles.modalContent} onClick={(e) => e.stopPropagation()}>
        <div className={styles.modalHeader}>
          <h2 className={styles.modalTitle}>Update Pet</h2>
          <button className={styles.closeButton} onClick={onClose}>
            ×
          </button>
        </div>

        <UpdatePetForm 
          pet={pet}
          onClose={onClose} 
          action={handleSubmit}
          success={success}
          errors={errors}
          />

      </div>
    </div>
  )
}

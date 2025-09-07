"use client"

import { createPetAction } from "@/actions/petActions"
import { useState } from "react"
import PetForm from "@/components/forms/PetForm"
import styles from "./CreatePetModal.module.css"

export default function CreatePetModal({ isOpen, onClose, onSuccess }) {
  const [success, setSuccess] = useState("")
  const [errors, setErrors] = useState({})

  const handleSubmit = async (formData) => {
    setSuccess("")
    setErrors({})

    try {
      const result = await createPetAction(formData)
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

  if (!isOpen) return null

  return (
    <div className={styles.modalOverlay} onClick={onClose}>
      <div className={styles.modalContent} onClick={(e) => e.stopPropagation()}>
        <div className={styles.modalHeader}>
          <h2 className={styles.modalTitle}>Add New Pet</h2>
          <button className={styles.closeButton} onClick={onClose}>
            ×
          </button>
        </div>

        <PetForm 
          onClose={onClose} 
          action={handleSubmit}
          success={success}
          errors={errors}
          />
      </div>
    </div>
  )
}

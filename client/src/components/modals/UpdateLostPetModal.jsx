"use client"

import { useState } from "react"
import { updateLostPetAction } from "@/actions/lostPetActions"
import UpdateLostPetForm from "@/components/forms/UpdateLostPetForm"
import styles from "./UpdatePetModal.module.css"

export default function UpdateLostPetModal({ isOpen, onClose, onSuccess, lostPet }) {
  const [success, setSuccess] = useState("")
  const [errors, setErrors] = useState({})

  const handleSubmit = async (formData) => {
    setSuccess("")
    setErrors({})

    try {
      const result = await updateLostPetAction(lostPet.id, formData)
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
      setErrors("An error occurred while creating the lostPet")
    }
  }

  if (!isOpen || !lostPet) return null

  return (
    <div className={styles.modalOverlay} onClick={onClose}>
      <div className={styles.modalContent} onClick={(e) => e.stopPropagation()}>
        <div className={styles.modalHeader}>
          <h2 className={styles.modalTitle}>Update Lost Pet</h2>
          <button className={styles.closeButton} onClick={onClose}>
            ×
          </button>
        </div>

        <UpdateLostPetForm 
          lostPet={lostPet}
          onClose={onClose} 
          action={handleSubmit}
          success={success}
          errors={errors}
          />

      </div>
    </div>
  )
}

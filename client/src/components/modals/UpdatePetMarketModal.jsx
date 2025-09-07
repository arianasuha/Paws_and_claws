"use client"

import { useState } from "react"
import { updatePetMarketAction } from "@/actions/petMarketActions"
import UpdatePetMarketForm from "@/components/forms/UpdatePetMarketForm"
import styles from "./UpdatePetModal.module.css"

export default function UpdatePetMarketModal({ isOpen, onClose, onSuccess, petMarket }) {
  const [success, setSuccess] = useState("")
  const [errors, setErrors] = useState({})

  const handleSubmit = async (formData) => {
    setSuccess("")
    setErrors({})

    try {
      const result = await updatePetMarketAction(petMarket.id, formData)
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
      setErrors("An error occurred while creating the petMarket")
    }
  }

  if (!isOpen || !petMarket) return null

  return (
    <div className={styles.modalOverlay} onClick={onClose}>
      <div className={styles.modalContent} onClick={(e) => e.stopPropagation()}>
        <div className={styles.modalHeader}>
          <h2 className={styles.modalTitle}>Update Pet Market</h2>
          <button className={styles.closeButton} onClick={onClose}>
            ×
          </button>
        </div>

        <UpdatePetMarketForm 
          petMarket={petMarket}
          onClose={onClose} 
          action={handleSubmit}
          success={success}
          errors={errors}
          />

      </div>
    </div>
  )
}

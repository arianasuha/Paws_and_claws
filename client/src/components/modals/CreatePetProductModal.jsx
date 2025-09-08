"use client"
import { createPetProductAction } from "@/actions/petProductActions"
import { useState } from "react"
import PetProductForm from "@/components/forms/PetProductForm"
import styles from "./CreatePetProductModal.module.css"

export default function CreatePetProductModal({ isOpen, onClose, onSuccess }) {
  const [success, setSuccess] = useState("")
  const [errors, setErrors] = useState({})

  const handleSubmit = async (formData) => {
    setSuccess("")
    setErrors({})

    try {
      const result = await createPetProductAction(formData)
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
          <h2 className={styles.modalTitle}>Add New Product</h2>
          <button className={styles.closeButton} onClick={onClose}>
            ×
          </button>
        </div>

        <PetProductForm
          onClose={onClose}
          action={handleSubmit}
          success={success}
          errors={errors}
          />
      </div>
    </div>
  )
}

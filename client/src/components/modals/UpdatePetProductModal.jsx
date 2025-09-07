"use client"

import { useState } from "react"
import { updatePetProductAction } from "@/actions/petProductActions"
import UpdatePetProductForm from "@/components/forms/UpdatePetProductForm"
import styles from "./UpdatePetModal.module.css"

export default function UpdatePetProductModal({ isOpen, onClose, onSuccess, product }) {
  const [success, setSuccess] = useState("")
  const [errors, setErrors] = useState({})

  const handleSubmit = async (formData) => {
    setSuccess("")
    setErrors({})

    try {
      const result = await updatePetProductAction(product.id, formData)
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
      setErrors("An error occurred while creating the product")
    }
  }

  if (!isOpen || !product) return null

  return (
    <div className={styles.modalOverlay} onClick={onClose}>
      <div className={styles.modalContent} onClick={(e) => e.stopPropagation()}>
        <div className={styles.modalHeader}>
          <h2 className={styles.modalTitle}>Update Pet</h2>
          <button className={styles.closeButton} onClick={onClose}>
            ×
          </button>
        </div>

        <UpdatePetProductForm 
          product={product}
          onClose={onClose} 
          action={handleSubmit}
          success={success}
          errors={errors}
          />

      </div>
    </div>
  )
}

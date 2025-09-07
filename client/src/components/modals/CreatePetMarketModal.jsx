"use client"

import { useState } from "react"
import { useRouter } from "next/navigation"
import { createPetMarketAction } from "@/actions/petMarketActions"
import PetMarketForm from "@/components/forms/PetMarketForm"
import styles from "./CreatePetMarketModal.module.css"

export default function CreatePetMarketModal({ isOpen, onClose, petId }) {
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

    const result = await createPetMarketAction(submitData)

    if (result.success) {
      setSuccessMessage(result.success)
      setErrors({})
      setTimeout(() => {
        onClose()
        router.push(`/shop/pet-market?type=${type}`)
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

          <PetMarketForm onSubmit={handleSubmit} errors={errors} isSubmitting={isSubmitting} />
        </div>
      </div>
    </div>
  )
}

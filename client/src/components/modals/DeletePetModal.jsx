"use client"

import { useState } from "react"
import { useRouter } from "next/navigation"
import { deletePetAction } from "@/actions/petActions"
import styles from "./DeletePetModal.module.css"

export default function DeletePetModal({ isOpen, onClose, onSuccess, pet }) {
  const router = useRouter()
  const [isDeleting, setIsDeleting] = useState(false)
  const [error, setError] = useState("")

  const handleDelete = async () => {
    setIsDeleting(true)
    setError("")

    try {
      const result = await deletePetAction(pet.id)
      if (result.success) {
        onSuccess()
        onClose()
        router.push("/pet")
      } else {
        setError(result.error || "Failed to delete pet")
      }
    } catch (err) {
      setError("An error occurred while deleting the pet")
    } finally {
      setIsDeleting(false)
    }
  }

  if (!isOpen || !pet) return null

  return (
    <div className={styles.modalOverlay} onClick={onClose}>
      <div className={styles.modalContent} onClick={(e) => e.stopPropagation()}>
        <div className={styles.modalHeader}>
          <h2 className={styles.modalTitle}>Delete Pet</h2>
          <button className={styles.closeButton} onClick={onClose}>
            ×
          </button>
        </div>

        <div className={styles.modalBody}>
          {error && <div className={styles.errorMessage}>{error}</div>}

          <div className={styles.warningIcon}>⚠️</div>
          <p className={styles.confirmText}>
            Are you sure you want to delete <strong>{pet.name}</strong>?
          </p>
          <p className={styles.subText}>
            This action cannot be undone. All information about this pet will be permanently removed.
          </p>
        </div>

        <div className={styles.modalActions}>
          <button type="button" onClick={onClose} className={styles.cancelButton} disabled={isDeleting}>
            Cancel
          </button>
          <button type="button" onClick={handleDelete} className={styles.deleteButton} disabled={isDeleting}>
            {isDeleting ? "Deleting..." : "Delete Pet"}
          </button>
        </div>
      </div>
    </div>
  )
}

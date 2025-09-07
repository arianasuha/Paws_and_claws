"use client"

import { useState } from "react"
import { useRouter } from "next/navigation"
import { deletePetProductAction } from "@/actions/petProductActions"
import styles from "./DeletePetModal.module.css"

export default function DeletePetProductModal({ isOpen, onClose, onSuccess, product }) {
  const router = useRouter()
  const [isDeleting, setIsDeleting] = useState(false)
  const [error, setError] = useState("")

  const handleDelete = async () => {
    setIsDeleting(true)
    setError("")

    try {
      const result = await deletePetProductAction(product.id)
      if (result.success) {
        onSuccess()
        onClose()
        router.push("/shop/pet-product")
      } else {
        setError(result.error || "Failed to delete product")
      }
    } catch (err) {
      setError("An error occurred while deleting the product")
    } finally {
      setIsDeleting(false)
    }
  }

  if (!isOpen || !product) return null

  return (
    <div className={styles.modalOverlay} onClick={onClose}>
      <div className={styles.modalContent} onClick={(e) => e.stopPropagation()}>
        <div className={styles.modalHeader}>
          <h2 className={styles.modalTitle}>Delete Product</h2>
          <button className={styles.closeButton} onClick={onClose}>
            ×
          </button>
        </div>

        <div className={styles.modalBody}>
          {error && <div className={styles.errorMessage}>{error}</div>}

          <div className={styles.warningIcon}>⚠️</div>
          <p className={styles.confirmText}>
            Are you sure you want to delete <strong>{product.name}</strong>?
          </p>
          <p className={styles.subText}>
            This action cannot be undone. All information about this product will be permanently removed.
          </p>
        </div>

        <div className={styles.modalActions}>
          <button type="button" onClick={onClose} className={styles.cancelButton} disabled={isDeleting}>
            Cancel
          </button>
          <button type="button" onClick={handleDelete} className={styles.deleteButton} disabled={isDeleting}>
            {isDeleting ? "Deleting..." : "Delete Product"}
          </button>
        </div>
      </div>
    </div>
  )
}

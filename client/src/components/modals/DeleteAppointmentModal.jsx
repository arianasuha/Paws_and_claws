"use client"

import styles from "./DeleteAppointmentModal.module.css"

export default function DeleteAppointmentModal({ onClose, onConfirm, title, message }) {
  const handleBackdropClick = (e) => {
    if (e.target === e.currentTarget) {
      onClose()
    }
  }

  return (
    <div className={styles.backdrop} onClick={handleBackdropClick}>
      <div className={styles.modal}>
        <div className={styles.header}>
          <h2 className={styles.title}>{title}</h2>
        </div>

        <div className={styles.content}>
          <p className={styles.message}>{message}</p>
        </div>

        <div className={styles.actions}>
          <button className={styles.cancelButton} onClick={onClose}>
            No, Cancel
          </button>
          <button className={styles.confirmButton} onClick={onConfirm}>
            Yes, Delete
          </button>
        </div>
      </div>
    </div>
  )
}

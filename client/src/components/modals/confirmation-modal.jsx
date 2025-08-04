"use client"

import styles from "./confirmation-modal.module.css"

export default function ConfirmationModal({ isOpen, onClose, onConfirm, title, message }) {
  if (!isOpen) return null

  return (
    <div className={styles["modal-overlay"]}>
      <div className={styles["modal-content"]}>
        <h3 className={styles["modal-title"]}>{title}</h3>
        <p className={styles["modal-message"]}>{message}</p>
        <div className={styles["modal-actions"]}>
          <button onClick={onClose} className={styles["modal-cancel-btn"]}>
            Cancel
          </button>
          <button onClick={onConfirm} className={styles["modal-confirm-btn"]}>
            Confirm
          </button>
        </div>
      </div>
    </div>
  )
}

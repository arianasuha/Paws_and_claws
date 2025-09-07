"use client"

import {DeleteEmergencyButton} from "@/components/buttons/buttons"
import styles from "./EmergencyCard.module.css"

export default function EmergencyCard({ request, onClick, onDelete }) {
  const handleCardClick = () => {
    onClick()
  }

  const handleDeleteClick = (e) => {
    e.stopPropagation()
    onDelete()
  }

  const formatDate = (dateString) => {
    const date = new Date(dateString)
    const options = { year: "numeric", month: "long", day: "numeric" }
    return date.toLocaleDateString("en-US", options)
  }

  return (
    <div className={styles.card} onClick={handleCardClick}>
      <div className={styles.deleteButton}>
        <DeleteEmergencyButton onClick={handleDeleteClick} />
      </div>

      <div className={styles.content}>
        <h3 className={styles.petName}>{request.pet?.name || "Unknown Pet"}</h3>

        <div className={styles.details}>
          <div className={styles.detail}>
            <span className={styles.label}>Owner:</span>
            <span className={styles.value}>{request.user?.username || "Unknown User"}</span>
          </div>

          <div className={styles.detail}>
            <span className={styles.label}>Request Date:</span>
            <span className={styles.value}>{formatDate(request.request_date)}</span>
          </div>

          <div className={styles.detail}>
            <span className={styles.label}>Request ID:</span>
            <span className={styles.value}>#{request.id}</span>
          </div>
        </div>
      </div>
    </div>
  )
}

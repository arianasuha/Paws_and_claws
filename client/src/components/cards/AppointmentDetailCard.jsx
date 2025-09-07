"use client"

import styles from "./AppointmentDetailCard.module.css"

export default function AppointmentDetailCard({ appointment }) {
  const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString("en-US", {
      year: "numeric",
      month: "long",
      day: "numeric",
    })
  }

  const formatTime = (timeString) => {
    const [hours, minutes] = timeString.split(":")
    const date = new Date()
    date.setHours(Number.parseInt(hours), Number.parseInt(minutes))
    return date.toLocaleTimeString("en-US", {
      hour: "numeric",
      minute: "2-digit",
      hour12: true,
    })
  }

  const getStatusColor = (status) => {
    switch (status) {
      case "pending":
        return styles.statusPending
      case "accepted":
        return styles.statusAccepted
      case "canceled":
        return styles.statusCanceled
      default:
        return styles.statusDefault
    }
  }

  return (
    <div className={styles.card}>
      <div className={styles.header}>
        <h2 className={styles.title}>Appointment #{appointment.id}</h2>
        <span className={`${styles.status} ${getStatusColor(appointment.status)}`}>
          {appointment.status.charAt(0).toUpperCase() + appointment.status.slice(1)}
        </span>
      </div>

      <div className={styles.content}>
        <div className={styles.section}>
          <h3 className={styles.sectionTitle}>Appointment Information</h3>
          <div className={styles.infoGrid}>
            <div className={styles.infoItem}>
              <span className={styles.label}>Date:</span>
              <span className={styles.value}>{formatDate(appointment.app_date)}</span>
            </div>
            <div className={styles.infoItem}>
              <span className={styles.label}>Time:</span>
              <span className={styles.value}>{formatTime(appointment.app_time)}</span>
            </div>
          </div>
          {appointment.visit_reason && (
            <div className={styles.infoItem}>
              <span className={styles.label}>Visit Reason:</span>
              <span className={styles.value}>{appointment.visit_reason}</span>
            </div>
          )}
        </div>

        <div className={styles.section}>
          <h3 className={styles.sectionTitle}>Patient Information</h3>
          <div className={styles.infoItem}>
            <span className={styles.label}>Pet Name:</span>
            <span className={styles.value}>{appointment.pet?.name || `Pet ID: ${appointment.pet_id}`}</span>
          </div>
        </div>

        <div className={styles.section}>
          <h3 className={styles.sectionTitle}>Appointer Information</h3>
          <div className={styles.infoItem}>
            <span className={styles.label}>Appointer Name:</span>
            <span className={styles.value}>
              {appointment.provider
                ? `${appointment.provider.first_name} ${appointment.provider.last_name}`
                : `Provider ID: ${appointment.provider_id}`}
            </span>
          </div>
        </div>

        <div className={styles.section}>
          <h3 className={styles.sectionTitle}>Owner Information</h3>
          <div className={styles.infoItem}>
            <span className={styles.label}>Owner Name:</span>
            <span className={styles.value}>
              {appointment.user
                ? `${appointment.user.first_name} ${appointment.user.last_name}`
                : `User ID: ${appointment.user_id}`}
            </span>
          </div>
        </div>
      </div>
    </div>
  )
}

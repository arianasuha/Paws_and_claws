"use client"

import { useRouter } from "next/navigation"
import styles from "./MyAppointmentCard.module.css"

export default function MyAppointmentCard({ appointment }) {
  const router = useRouter()

  const handleClick = () => {
    router.push(`/appointment/my-appointment/${appointment.id}`)
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

  return (
    <div className={styles.card} onClick={handleClick}>
      <div className={styles.header}>
        <div className={styles.appointmentInfo}>
          <h3 className={styles.title}>Appointment #{appointment.id}</h3>
          <span className={`${styles.status} ${getStatusColor(appointment.status)}`}>
            {appointment.status.charAt(0).toUpperCase() + appointment.status.slice(1)}
          </span>
        </div>
      </div>

      <div className={styles.content}>
        <div className={styles.dateTime}>
          <div className={styles.date}>
            <strong>Date:</strong> {formatDate(appointment.app_date)}
          </div>
          <div className={styles.time}>
            <strong>Time:</strong> {formatTime(appointment.app_time)}
          </div>
        </div>

        <div className={styles.petInfo}>
          <strong>Pet ID:</strong> {appointment.pet_id}
        </div>

        {appointment.visit_reason && (
          <div className={styles.reason}>
            <strong>Reason:</strong> {appointment.visit_reason}
          </div>
        )}
      </div>
    </div>
  )
}

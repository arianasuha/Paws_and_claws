"use client"
import { DeleteNotificationButton } from "@/components/buttons/buttons"
import styles from "./NotificationDetailCard.module.css"

export default function NotificationDetailCard({ notification, onDelete }) {
  const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString("en-US", {
      year: "numeric",
      month: "long",
      day: "numeric",
      hour: "2-digit",
      minute: "2-digit",
      second: "2-digit",
    })
  }

  return (
    <div className={styles.card}>
      <div className={styles.header}>
        <div className={styles.titleSection}>
          <h1 className={styles.subject}>{notification.subject}</h1>
          <div className={styles.status}>
            <span className={`${styles.statusBadge} ${notification.is_read ? styles.read : styles.unread}`}>
              {notification.is_read ? "Read" : "Unread"}
            </span>
          </div>
        </div>
        <DeleteNotificationButton notificationId={notification.id} onDelete={onDelete} />
      </div>

      <div className={styles.content}>
        <div className={styles.messageSection}>
          <h3 className={styles.messageLabel}>Message</h3>
          <p className={styles.message}>{notification.message}</p>
        </div>

        <div className={styles.detailsGrid}>
          <div className={styles.detailItem}>
            <span className={styles.detailLabel}>Notification ID</span>
            <span className={styles.detailValue}>#{notification.id}</span>
          </div>

          <div className={styles.detailItem}>
            <span className={styles.detailLabel}>User ID</span>
            <span className={styles.detailValue}>#{notification.user_id}</span>
          </div>

          <div className={styles.detailItem}>
            <span className={styles.detailLabel}>Created At</span>
            <span className={styles.detailValue}>{formatDate(notification.created_at)}</span>
          </div>

          <div className={styles.detailItem}>
            <span className={styles.detailLabel}>Updated At</span>
            <span className={styles.detailValue}>{formatDate(notification.updated_at)}</span>
          </div>
        </div>
      </div>
    </div>
  )
}

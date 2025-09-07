"use client"

import { useRouter } from "next/navigation"
import styles from "./NotificationCard.module.css"

export default function NotificationCard({ notification }) {
  const router = useRouter()

  const handleClick = () => {
    router.push(`/notification/${notification.id}`)
  }

  const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString("en-US", {
      year: "numeric",
      month: "short",
      day: "numeric",
      hour: "2-digit",
      minute: "2-digit",
    })
  }

  return (
    <div className={`${styles.card} ${!notification.is_read ? styles.unread : ""}`} onClick={handleClick}>
      <div className={styles.content}>
        <div className={styles.header}>
          <h3 className={styles.subject}>{notification.subject}</h3>
          {!notification.is_read && <div className={styles.unreadIndicator}></div>}
        </div>
        <p className={styles.message}>{notification.message}</p>
        <div className={styles.date}>{formatDate(notification.created_at)}</div>
      </div>
      <div className={styles.arrow}>
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
          <path
            d="M9 18L15 12L9 6"
            stroke="currentColor"
            strokeWidth="2"
            strokeLinecap="round"
            strokeLinejoin="round"
          />
        </svg>
      </div>
    </div>
  )
}

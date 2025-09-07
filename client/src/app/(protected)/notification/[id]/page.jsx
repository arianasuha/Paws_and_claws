"use client"

import { useState, useEffect } from "react"
import { useParams, useRouter } from "next/navigation"
import { getNotificationAction, updateNotificationAction } from "@/actions/notificationsActions"
import NotificationDetailCard from "@/components/cards/NotificationDetailCard"
import styles from "./page.module.css"

export default function NotificationDetailPage() {
  const params = useParams()
  const router = useRouter()
  const [notification, setNotification] = useState(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState("")

  useEffect(() => {
    const fetchNotification = async () => {
      if (!params.id) return

      setLoading(true)
      setError("")

      try {
        const result = await getNotificationAction(params.id)

        if (result.error) {
          setError(result.error)
        } else {
          setNotification(result.data)

          // Mark notification as read if it's unread
          if (!result.data.is_read) {
            await updateNotificationAction(params.id)
          }
        }
      } catch (err) {
        setError("Failed to fetch notification details")
      } finally {
        setLoading(false)
      }
    }

    fetchNotification()
  }, [params.id])

  const handleBack = () => {
    router.push("/notification")
  }

  if (loading) {
    return (
      <div className={styles.container}>
        <div className={styles.loading}>Loading notification...</div>
      </div>
    )
  }

  if (error) {
    return (
      <div className={styles.container}>
        <button onClick={handleBack} className={styles.backButton}>
          ← Back to Notifications
        </button>
        <div className={styles.error}>{error}</div>
      </div>
    )
  }

  return (
    <div className={styles.container}>
      <button onClick={handleBack} className={styles.backButton}>
        ← Back to Notifications
      </button>

      {notification && (
        <NotificationDetailCard notification={notification} onDelete={() => router.push("/notification")} />
      )}
    </div>
  )
}

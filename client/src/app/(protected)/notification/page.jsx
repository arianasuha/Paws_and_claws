"use client"

import { useState, useEffect } from "react"
import { getNotificationsAction } from "@/actions/notificationsActions"
import NotificationCard from "@/components/cards/NotificationCard"
import Pagination from "@/components/pagination/Pagination"
import styles from "./page.module.css"

export default function NotificationPage() {
  const [notifications, setNotifications] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState("")
  const [currentPage, setCurrentPage] = useState(1)
  const [totalPages, setTotalPages] = useState(1)
  const [totalItems, setTotalItems] = useState(0)

  const fetchNotifications = async (page = 1) => {
    setLoading(true)
    setError("")

    try {
      const result = await getNotificationsAction({ page })

      if (result.error) {
        setError(result.error)
      } else {
        setNotifications(result.data || [])
        setTotalPages(result.pagination?.total_pages || 1)
        setTotalItems(result.pagination?.count || 0)
      }
    } catch (err) {
      setError("Failed to fetch notifications")
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    fetchNotifications(currentPage)
  }, [currentPage])

  const handlePageChange = (page) => {
    setCurrentPage(page)
  }

  if (loading) {
    return (
      <div className={styles.container}>
        <div className={styles.loading}>Loading notifications...</div>
      </div>
    )
  }

  return (
    <div className={styles.container}>
      <div className={styles.header}>
        <h1 className={styles.title}>Notifications</h1>
        <p className={styles.subtitle}>Stay updated with your latest notifications</p>
      </div>

      {error && <div className={styles.error}>{error}</div>}

      {notifications.length === 0 && !error ? (
        <div className={styles.emptyState}>
          <h3>No notifications found</h3>
          <p>You don't have any notifications at the moment.</p>
        </div>
      ) : (
        <>
          <div className={styles.notificationsList}>
            {notifications.map((notification) => (
              <NotificationCard key={notification.id} notification={notification} />
            ))}
          </div>

          <Pagination
            currentPage={currentPage}
            totalPages={totalPages}
            onPageChange={handlePageChange}
            totalItems={totalItems}
            itemsPerPage={10}
          />
        </>
      )}
    </div>
  )
}

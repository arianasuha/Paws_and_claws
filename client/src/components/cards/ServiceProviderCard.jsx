"use client"

import { useRouter } from "next/navigation"
import styles from "./ServiceProviderCard.module.css"

export default function ServiceProviderCard({ provider }) {
  const router = useRouter()

  const handleCardClick = () => {
    router.push(`/appointment/service-provider-appointment/${provider.user.id}`)
  }

  const getServiceTypeColor = (type) => {
    switch (type?.toLowerCase()) {
      case "walker":
        return "#10b981"
      case "groomer":
        return "#8b5cf6"
      case "trainer":
        return "#f59e0b"
      default:
        return "#6b7280"
    }
  }

  const renderStars = (rating) => {
    const numRating = Number.parseFloat(rating) || 0
    const stars = []
    for (let i = 1; i <= 5; i++) {
      stars.push(
        <span key={i} className={i <= numRating ? styles.starFilled : styles.starEmpty}>
          ★
        </span>,
      )
    }
    return stars
  }

  return (
    <div className={styles.card} onClick={handleCardClick}>
      <div className={styles.header}>
        <div className={styles.serviceInfo}>
          <span
            className={styles.serviceType}
            style={{
              backgroundColor: `${getServiceTypeColor(provider.service_type)}20`,
              color: getServiceTypeColor(provider.service_type),
            }}
          >
            {provider.service_type?.charAt(0).toUpperCase() + provider.service_type?.slice(1)}
          </span>
          <div className={styles.rating}>
            {renderStars(provider.rating)}
            <span className={styles.ratingValue}>({provider.rating || "N/A"})</span>
          </div>
        </div>
      </div>

      <div className={styles.providerInfo}>
        <h4 className={styles.providerName}>
          {provider.user.first_name} {provider.user.last_name}
        </h4>
        <p className={styles.email}>{provider.user.email}</p>
        <p className={styles.username}>@{provider.user.username}</p>
      </div>

      <div className={styles.details}>
        <div className={styles.rate}>
          <strong>Rate:</strong>
          <span className={styles.rateValue}>${provider.rate_per_hour}/hour</span>
        </div>

        <div className={styles.description}>
          <strong>Description:</strong>
          <p>{provider.service_desc}</p>
        </div>
      </div>

      <div className={styles.footer}>
        <button className={styles.bookButton}>Book Appointment</button>
      </div>
    </div>
  )
}

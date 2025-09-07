"use client"

import { useState } from "react"
import BookingModal from "@/components/modals/BookingModal"
import {BookNowButton} from "@/components/buttons/buttons"
import styles from "./ServiceProviderDetailCard.module.css"

export default function ServiceProviderDetailCard({ provider }) {
  const [isModalOpen, setIsModalOpen] = useState(false)

  const handleBookNow = () => {
    setIsModalOpen(true)
  }

  const handleCloseModal = () => {
    setIsModalOpen(false)
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
    <>
      <div className={styles.card}>
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
          <div className={styles.providerDetails}>
            <h2 className={styles.providerName}>
              {provider.user.first_name} {provider.user.last_name}
            </h2>
            <div className={styles.contactInfo}>
              <p className={styles.email}>
                <strong>Email:</strong> {provider.user.email}
              </p>
              <p className={styles.username}>
                <strong>Username:</strong> @{provider.user.username}
              </p>
              {provider.user.address && (
                <p className={styles.address}>
                  <strong>Address:</strong> {provider.user.address}
                </p>
              )}
            </div>
          </div>
        </div>

        <div className={styles.details}>
          <div className={styles.rate}>
            <h3>Rate</h3>
            <p className={styles.rateValue}>${provider.rate_per_hour}/hour</p>
          </div>

          <div className={styles.description}>
            <h3>Service Description</h3>
            <p>{provider.service_desc}</p>
          </div>

          <div className={styles.status}>
            <span className={provider.user.is_active ? styles.active : styles.inactive}>
              {provider.user.is_active ? "Available" : "Unavailable"}
            </span>
          </div>
        </div>

        <div className={styles.footer}>
          <BookNowButton onClick={handleBookNow} disabled={!provider.user.is_active} />
        </div>
      </div>

      {isModalOpen && (
        <BookingModal
          providerId={provider.id}
          providerName={`${provider.user.first_name} ${provider.user.last_name}`}
          providerType="service_provider"
          onClose={handleCloseModal}
        />
      )}
    </>
  )
}

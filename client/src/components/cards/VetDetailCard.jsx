"use client"

import { useState } from "react"
import BookingModal from "@/components/modals/BookingModal"
import { BookNowButton } from "@/components/buttons/buttons"
import styles from "./VetDetailCard.module.css"

export default function VetDetailCard({ vet }) {
  const [isModalOpen, setIsModalOpen] = useState(false)

  const handleBookNow = () => {
    setIsModalOpen(true)
  }

  const handleCloseModal = () => {
    setIsModalOpen(false)
  }

  return (
    <>
      <div className={styles.card}>
        <div className={styles.header}>
          <h1 className={styles.clinicName}>{vet.clinic_name}</h1>
          <span className={styles.specialization}>{vet.specialization}</span>
        </div>

        <div className={styles.vetInfo}>
          <div className={styles.vetDetails}>
            <h2 className={styles.vetName}>
              Dr. {vet.user.first_name} {vet.user.last_name}
            </h2>
            <div className={styles.contactInfo}>
              <p className={styles.email}>
                <strong>Email:</strong> {vet.user.email}
              </p>
              <p className={styles.username}>
                <strong>Username:</strong> @{vet.user.username}
              </p>
              {vet.user.address && (
                <p className={styles.address}>
                  <strong>Address:</strong> {vet.user.address}
                </p>
              )}
            </div>
          </div>
        </div>

        <div className={styles.details}>
          <div className={styles.workingHours}>
            <h3>Working Hours</h3>
            <p>{vet.working_hour}</p>
          </div>

          <div className={styles.services}>
            <h3>Services Offered</h3>
            <p>{vet.services_offered}</p>
          </div>

          <div className={styles.status}>
            <span className={vet.user.is_active ? styles.active : styles.inactive}>
              {vet.user.is_active ? "Available" : "Unavailable"}
            </span>
          </div>
        </div>

        <div className={styles.footer}>
          <BookNowButton onClick={handleBookNow} disabled={!vet.user.is_active} />
        </div>
      </div>

      {isModalOpen && (
        <BookingModal
          providerId={vet.id}
          providerName={`Dr. ${vet.user.first_name} ${vet.user.last_name}`}
          providerType="vet"
          onClose={handleCloseModal}
        />
      )}
    </>
  )
}

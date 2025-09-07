"use client"

import { useRouter } from "next/navigation"
import styles from "./VetCard.module.css"

export default function VetCard({ vet }) {
  const router = useRouter()

  const handleCardClick = () => {
    router.push(`/appointment/vet-appointment/${vet.id}`)
  }

  return (
    <div className={styles.card} onClick={handleCardClick}>
      <div className={styles.header}>
        <h3 className={styles.clinicName}>{vet.clinic_name}</h3>
        <span className={styles.specialization}>{vet.specialization}</span>
      </div>

      <div className={styles.vetInfo}>
        <h4 className={styles.vetName}>
          Dr. {vet.user.first_name} {vet.user.last_name}
        </h4>
        <p className={styles.email}>{vet.user.email}</p>
        <p className={styles.username}>@{vet.user.username}</p>
      </div>

      <div className={styles.details}>
        <div className={styles.workingHours}>
          <strong>Working Hours:</strong>
          <span>{vet.working_hour}</span>
        </div>

        <div className={styles.services}>
          <strong>Services:</strong>
          <p>{vet.services_offered}</p>
        </div>
      </div>

      <div className={styles.footer}>
        <button className={styles.bookButton}>Book Appointment</button>
      </div>
    </div>
  )
}

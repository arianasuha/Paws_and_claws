"use client"

import { useState, useEffect } from "react"
import { useRouter } from "next/navigation"
import BookingForm from "@/components/forms/BookingForm"
import styles from "./BookingModal.module.css"

export default function BookingModal({ providerId, providerName, providerType, onClose }) {
  const router = useRouter()
  const [isClosing, setIsClosing] = useState(false)

  useEffect(() => {
    document.body.style.overflow = "hidden"
    return () => {
      document.body.style.overflow = "unset"
    }
  }, [])

  const handleClose = () => {
    setIsClosing(true)
    setTimeout(() => {
      onClose()
    }, 200)
  }

  const handleBackdropClick = (e) => {
    if (e.target === e.currentTarget) {
      handleClose()
    }
  }

  const handleBookingSuccess = () => {
    handleClose()
    router.push("/appointment/my-appointment")
  }

  return (
    <div className={`${styles.backdrop} ${isClosing ? styles.closing : ""}`} onClick={handleBackdropClick}>
      <div className={`${styles.modal} ${isClosing ? styles.modalClosing : ""}`}>
        <div className={styles.header}>
          <h2 className={styles.title}>Book Appointment</h2>
          <p className={styles.subtitle}>with {providerName}</p>
          <button className={styles.closeButton} onClick={handleClose} aria-label="Close modal">
            ×
          </button>
        </div>

        <div className={styles.content}>
          <BookingForm
            providerId={providerId}
            providerType={providerType}
            onSuccess={handleBookingSuccess}
            onCancel={handleClose}
          />
        </div>
      </div>
    </div>
  )
}

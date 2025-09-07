"use client"

import { useRouter } from "next/navigation"
import AppointmentCard from "@/components/cards/AppointmentCard"
import styles from "./page.module.css"

export default function AppointmentPage() {
  const router = useRouter()

  const appointmentCards = [
    {
      id: "my-appointments",
      title: "My Appointments",
      description: "View and manage all your scheduled appointments.",
      image: "https://images.unsplash.com/photo-1624969862293-b749659ccc4e?w=400&h=300&fit=crop",
      onClick: () => router.push("/appointment/my-appointment"),
    },
    {
      id: "vet-appointments",
      title: "Vet Appointment",
      description: "Schedule and manage veterinary appointments for your pets.",
      image: "https://plus.unsplash.com/premium_photo-1677165479692-180fac4c0832?w=400&h=300&fit=crop",
      onClick: () => router.push("/appointment/vet-appointment"),
    },
    {
      id: "service-provider-appointments",
      title: "Service Provider Appointment",
      description: "Book appointments with pet service providers and groomers.",
      image: "https://plus.unsplash.com/premium_photo-1663012822996-ba7e04f3627a?w=400&h=300&fit=crop",
      onClick: () => router.push("/appointment/service-provider-appointment"),
    },
  ]

  return (
    <div className={styles.container}>
      <div className={styles.header}>
        <h1 className={styles.title}>Appointments</h1>
        <p className={styles.subtitle}>Manage all your pet care appointments in one place</p>
      </div>

      <div className={styles.cardsGrid}>
        {appointmentCards.map((card) => (
          <AppointmentCard
            key={card.id}
            title={card.title}
            description={card.description}
            image={card.image}
            onClick={card.onClick}
          />
        ))}
      </div>
    </div>
  )
}

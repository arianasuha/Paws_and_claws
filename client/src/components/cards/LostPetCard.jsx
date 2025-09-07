"use client"

import { useState } from "react"
import { useRouter } from "next/navigation"
import styles from "./LostPetCard.module.css"

export default function LostPetCard({ lostPet }) {
  const router = useRouter()
  const [imageError, setImageError] = useState(false)
  const imageUrl = lostPet.pet.image_url
    ? `${process.env.NEXT_PUBLIC_BASE_URL}${lostPet.pet.image_url}`
    : "/placeholder.svg?height=200&width=200&query=pet";

  const handleClick = () => {
    router.push(`/lost-pet/${lostPet.id}`)
  }

  const formatGender = (gender) => {
    return gender.charAt(0).toUpperCase() + gender.slice(1)
  }

  const formatBreed = (breed) => {
    return breed.charAt(0).toUpperCase() + breed.slice(1)
  }

  const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString()
  }

  const getStatusColor = (status) => {
    switch (status) {
      case "missing":
        return styles.statusAvailable
      case "found":
        return styles.statusAdopted
      default:
        return styles.statusDefault
    }
  }

  return (
    <div className={styles.card} onClick={handleClick}>
      <div className={styles.imageContainer}>
        {!imageError ? (
          <img
            src={imageUrl}
            alt={lostPet.pet.name}
            className={styles.image}
            onError={() => setImageError(true)}
          />
        ) : (
          <div className={styles.imagePlaceholder}>
            <span>No Image</span>
          </div>
        )}
        <div className={`${styles.status} ${getStatusColor(lostPet.status)}`}>{lostPet.status}</div>
      </div>

      <div className={styles.content}>
        <h3 className={styles.name}>{lostPet.pet.name}</h3>

        <div className={styles.details}>
          <div className={styles.detail}>
            <span className={styles.label}>Species:</span>
            <span className={styles.value}>{formatBreed(lostPet.pet.species)}</span>
          </div>

          <div className={styles.detail}>
            <span className={styles.label}>Breed:</span>
            <span className={styles.value}>{formatBreed(lostPet.pet.breed)}</span>
          </div>

          <div className={styles.detail}>
            <span className={styles.label}>Gender:</span>
            <span className={styles.value}>{formatGender(lostPet.pet.gender)}</span>
          </div>

          {lostPet.location && (
            <div className={styles.price}>
              <span className={styles.priceLabel}>Location:</span>
              <span className={styles.priceValue}>{lostPet.location}</span>
            </div>
          )}

          {lostPet.date_lost && (
            <div className={styles.price}>
              <span className={styles.priceLabel}>Date Lost:</span>
              <span className={styles.priceValue}>{formatDate(lostPet.date_lost)}</span>
            </div>
          )}
        </div>

        <div className={styles.footer}>
          <span className={styles.type}>Listed by</span>
          <span className={styles.owner}>{lostPet.user.username}</span>
        </div>
      </div>
    </div>
  )
}

"use client"

import { useState } from "react"
import { useRouter } from "next/navigation"
import styles from "./PetMarketCard.module.css"

export default function PetMarketCard({ petMarket }) {
  const router = useRouter()
  const [imageError, setImageError] = useState(false)
  const imageUrl = petMarket.pet.image_url
    ? `${process.env.NEXT_PUBLIC_BASE_URL}${petMarket.pet.image_url}`
    : "/placeholder.svg?height=200&width=200&query=pet";

  const handleClick = () => {
    router.push(`/shop/pet-market/${petMarket.pet.id}`)
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
      case "available":
        return styles.statusAvailable
      case "adopted":
        return styles.statusAdopted
      case "sold":
        return styles.statusSold
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
            alt={petMarket.pet.name}
            className={styles.image}
            onError={() => setImageError(true)}
          />
        ) : (
          <div className={styles.imagePlaceholder}>
            <span>No Image</span>
          </div>
        )}
        <div className={`${styles.status} ${getStatusColor(petMarket.status)}`}>{petMarket.status}</div>
      </div>

      <div className={styles.content}>
        <h3 className={styles.name}>{petMarket.pet.name}</h3>

        <div className={styles.details}>
          <div className={styles.detail}>
            <span className={styles.label}>Species:</span>
            <span className={styles.value}>{formatBreed(petMarket.pet.species)}</span>
          </div>

          <div className={styles.detail}>
            <span className={styles.label}>Breed:</span>
            <span className={styles.value}>{formatBreed(petMarket.pet.breed)}</span>
          </div>

          <div className={styles.detail}>
            <span className={styles.label}>Gender:</span>
            <span className={styles.value}>{formatGender(petMarket.pet.gender)}</span>
          </div>

          {petMarket.fee && (
            <div className={styles.price}>
              <span className={styles.priceLabel}>Price:</span>
              <span className={styles.priceValue}>${petMarket.fee}</span>
            </div>
          )}

          {petMarket.date && (
            <div className={styles.price}>
              <span className={styles.priceLabel}>Date:</span>
              <span className={styles.priceValue}>{formatDate(petMarket.date)}</span>
            </div>
          )}
        </div>

        <div className={styles.footer}>
          <span className={styles.type}>For {petMarket.type === "sale" ? "Sale" : "Adoption"}</span>
          <span className={styles.owner}>by {petMarket.user.username}</span>
        </div>
      </div>
    </div>
  )
}

"use client"

import { useState } from "react"
import styles from "./PetCard.module.css"

export default function PetCard({ pet, onClick }) {
  const [imageError, setImageError] = useState(false)
  const imageUrl = pet.image_url
    ? `${process.env.NEXT_PUBLIC_BASE_URL}${pet.image_url}`
    : "/placeholder.svg?height=200&width=200&query=pet";

  const formatGender = (gender) => {
    return gender.charAt(0).toUpperCase() + gender.slice(1)
  }

  const formatBreed = (breed) => {
    return breed.charAt(0).toUpperCase() + breed.slice(1)
  }

  const formatSpecies = (species) => {
    return species.charAt(0).toUpperCase() + species.slice(1)
  }

  const handleClick = () => {
    if (onClick) {
      onClick(pet.id)
    }
  }

  return (
    <div className={styles.petCard} onClick={handleClick}>
      {!imageError ? (
        <img
          src={imageUrl}
          alt={pet.name}
          className={styles.petImage}
          onError={() => setImageError(true)}
        />
      ) : (
        <div className={styles.imagePlaceholder}>
          <span>No Image</span>
        </div>
      )}
      <h3 className={styles.petName}>{pet.name}</h3>
      <div className={styles.petDetails}>
        <div className={styles.petDetail}>
          <span className={styles.petDetailLabel}>Species:</span> {formatSpecies(pet.species)}
        </div>
        <div className={styles.petDetail}>
          <span className={styles.petDetailLabel}>Breed:</span> {formatBreed(pet.breed)}
        </div>
        <div className={styles.petDetail}>
          <span className={styles.petDetailLabel}>Gender:</span> {formatGender(pet.gender)}
        </div>
      </div>
    </div>
  )
}

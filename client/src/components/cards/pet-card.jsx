"use client"

import {DetailButton} from "@/components/buttons/buttons"
import styles from "./pet-card.module.css"

export default function PetCard({ pet }) {
  return (
    <div className={styles["pet-card"]}>
      <img
        src={pet.image_url || "/placeholder.svg?height=150&width=150&query=pet"}
        alt={`Image of ${pet.name}`}
        className={styles["pet-image"]}
      />
      <h3 className={styles["pet-name"]}>{pet.name}</h3>
      <p className={styles["pet-info"]}>Species: {pet.species}</p>
      <p className={styles["pet-info"]}>Breed: {pet.breed}</p>
      <DetailButton petId={pet.id} />
    </div>
  )
}

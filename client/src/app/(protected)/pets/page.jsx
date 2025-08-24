"use client"

import { useState, useEffect } from "react"
import { getPetsAction } from "@/actions/petActions"
import PetCard from "@/components/cards/pet-card"
import CreatePetButton from "@/components/buttons/create-pet-button"
import styles from "./page.module.css"

export default function MyPetsPage() {
  const [pets, setPets] = useState([])
  const [isLoading, setIsLoading] = useState(true)
  const [error, setError] = useState(null)

  useEffect(() => {
    async function fetchPets() {
      setIsLoading(true)
      setError(null)
      const result = await getPetsAction()
      if (result.data) {
        setPets(result.data)
      } else if (result.error) {
        setError(result.error.general || "Failed to fetch pets.")
      }
      setIsLoading(false)
    }
    fetchPets()
  }, [])

  if (isLoading) {
    return (
      <div className={styles["pets-page"]}>
        <div className={styles["pets-container"]}>
          <div className={styles["loading-message"]}>Loading pets...</div>
        </div>
      </div>
    )
  }

  if (error) {
    return (
      <div className={styles["pets-page"]}>
        <div className={styles["pets-container"]}>
          <div className={styles["error-message"]}>{error}</div>
        </div>
      </div>
    )
  }

  return (
    <div className={styles["pets-page"]}>
      <div className={styles["pets-container"]}>
        <h1 className={styles["main-title"]}>My Pets</h1>
        <CreatePetButton />
        {pets.length === 0 ? (
          <p className={styles["no-pets-message"]}>You don't have any pets registered yet.</p>
        ) : (
          <div className={styles["pets-grid"]}>
            {pets.map((pet) => (
              <PetCard key={pet.id} pet={pet} />
            ))}
          </div>
        )}
      </div>
    </div>
  )
}

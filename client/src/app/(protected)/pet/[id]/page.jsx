"use client"

import { useState, useEffect } from "react"
import { useParams, useRouter } from "next/navigation"
import { getPetAction } from "@/actions/petActions"
import { getUserIdAction } from "@/actions/authActions"
import {UpdatePetButton, DeletePetButton} from "@/components/buttons/buttons"
import styles from "./page.module.css"

export default function PetDetailPage() {
  const params = useParams()
  const [userId, setUserId] = useState(null)
  const [pet, setPet] = useState(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState("")
  const router = useRouter()
  const [imageUrl, setImageUrl] = useState(null);
  const [imageError, setImageError] = useState(false)
  
  const fetchPet = async () => {
      setLoading(true)
      setError("")

      try {
        const result = await getPetAction(params.id)
        console.log(result)
        if (result.error) {
          setError(result.error)
          setPet(null)
        } else {
          setPet(result.data)
          setImageUrl(result.data.image_url ? `${process.env.NEXT_PUBLIC_BASE_URL}${result.data.image_url}` : "/placeholder.svg?height=200&width=200&query=pet");
        }
      } catch (err) {
        setError("Failed to fetch pet details")
        setPet(null)
      } finally {
        setLoading(false)
      }
    }

  useEffect(() => {
    const fetchUserId = async () => {
      try {
        const result = await getUserIdAction()
        if (result.error) {
          setError(result.error)
        } else {
          setUserId(result)
        }
      } catch (err) {
        setError("Failed to fetch user ID")
      }
    }

    fetchUserId()

    if (params.id) {
      fetchPet()
    }
  }, [params.id])

  const handleBackToPets = () => {
    router.push("/pet-medical-log")
  }

  if (loading) {
    return (
      <div className={styles.container}>
        <div className={styles.loading}>Loading pet details...</div>
      </div>
    )
  }

  if (error) {
    return (
      <div className={styles.container}>
        <div className={styles.error}>{typeof error === "object" ? JSON.stringify(error) : error}</div>
        <button className={styles.backButton} onClick={handleBackToPets}>
          Back to My Pets
        </button>
      </div>
    )
  }

  if (!pet) {
    return (
      <div className={styles.container}>
        <div className={styles.notFound}>
          <h2>Pet not found</h2>
          <p>The pet you're looking for doesn't exist or you don't ha64ve permission to view it.</p>
          <button className={styles.backButton} onClick={handleBackToPets}>
            Back to My Pets
          </button>
        </div>
      </div>
    )
  }

  return (
    <div className={styles.container}>
      <div className={styles.header}>
        <button className={styles.backButton} onClick={handleBackToPets}>
          ← Back to My Pets
        </button>
      </div>

      <div className={styles.petDetailCard}>
        {!imageError ? (
          <img
            src={imageUrl}
            alt={pet.name}
            className={styles.petDetailImage}
            onError={() => setImageError(true)}
          />
        ) : (
          <div className={styles.imagePlaceholder}>
            <span>No Image</span>
          </div>
        )}
        <h1 className={styles.petDetailName}>{pet.name}</h1>

        <div className={styles.petDetailGrid}>
          <div className={styles.petDetailItem}>
            <span className={styles.petDetailLabel}>Species</span>
            <div className={styles.petDetailValue}>{pet.species}</div>
          </div>
          <div className={styles.petDetailItem}>
            <span className={styles.petDetailLabel}>Breed</span>
            <div className={styles.petDetailValue}>{pet.breed}</div>
          </div>
          <div className={styles.petDetailItem}>
            <span className={styles.petDetailLabel}>Gender</span>
            <div className={styles.petDetailValue}>{pet.gender}</div>
          </div>
          <div className={styles.petDetailItem}>
            <span className={styles.petDetailLabel}>Date of Birth</span>
            <div className={styles.petDetailValue}>{new Date(pet.dob).toLocaleDateString()}</div>
          </div>
          <div className={styles.petDetailItem}>
            <span className={styles.petDetailLabel}>Weight</span>
            <div className={styles.petDetailValue}>{pet.weight} kg</div>
          </div>
          <div className={styles.petDetailItem}>
            <span className={styles.petDetailLabel}>Height</span>
            <div className={styles.petDetailValue}>{pet.height} cm</div>
          </div>
        </div>

        {userId == pet.user_id && (
          <div className={styles.buttonContainer}>
            <UpdatePetButton onSuccess={fetchPet} pet={pet} />
            <DeletePetButton onSuccess={fetchPet} pet={pet} />
          </div>
        )}
      </div>
    </div>
  )
}

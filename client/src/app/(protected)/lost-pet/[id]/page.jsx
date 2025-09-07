"use client"

import { useState, useEffect } from "react"
import { useParams, useRouter } from "next/navigation"
import { getLostPetAction } from "@/actions/lostPetActions"
import { getUserIdAction, getUserRoleAction } from "@/actions/authActions"
import { UpdateLostPetButton, DeleteLostPetButton } from "@/components/buttons/buttons"
import styles from "./page.module.css"

export default function LostPetDetailPage() {
  const params = useParams()
  const [userId, setUserId] = useState(null)
  const [userRole, setUserRole] = useState(null)
  const [lostPet, setLostPet] = useState(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState("")
  const [imageUrl, setImageUrl] = useState(null);
  const [imageError, setImageError] = useState(false)
  const router = useRouter()
  
  const fetchLostPet = async () => {
    setLoading(true)
    setError("")

    try {
      const result = await getLostPetAction(params.id)
      if (result.error) {
        setError(result.error)
        setLostPet(null)
      } else {
        setLostPet(result.data)
        setImageUrl(result.data.pet.image_url ? `${process.env.NEXT_PUBLIC_BASE_URL}${result.data.pet.image_url}` : "/placeholder.svg?height=200&width=200&query=pet");
      }
    } catch (err) {
      setError("Failed to fetch pet details")
      setLostPet(null)
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

    const fetchUserRole = async () => {
      try {
        const result = await getUserRoleAction()
        if (result.error) {
          setError(result.error)
        } else {
          setUserRole(result)
        }
      } catch (err) {
        setError("Failed to fetch user role")
      }
    }

    fetchUserId()
    fetchUserRole()
    
    if (params.id) {
      fetchLostPet()
    }
  }, [params.id])

  const handleBackToPets = () => {
    router.back()
  }

  if (loading) {
    return (
      <div className={styles.container}>
        <div className={styles.loading}>Loading lost pet details...</div>
      </div>
    )
  }

  if (error) {
    return (
      <div className={styles.container}>
        <div className={styles.error}>{typeof error === "object" ? JSON.stringify(error) : error}</div>
        <button className={styles.backButton} onClick={handleBackToPets}>
          Back to Lost Pets
        </button>
      </div>
    )
  }

  if (!lostPet) {
    return (
      <div className={styles.container}>
        <div className={styles.notFound}>
          <h2>Pet not found</h2>
          <p>The pet you're looking for doesn't exist or you don't have permission to view it.</p>
          <button className={styles.backButton} onClick={handleBackToPets}>
            Back to Lost Pets
          </button>
        </div>
      </div>
    )
  }

  return (
    <div className={styles.container}>
      <div className={styles.header}>
        <button className={styles.backButton} onClick={handleBackToPets}>
          ← Back to Shop
        </button>
      </div>

      <div className={styles.petDetailCard}>
        {!imageError ? (
          <img
            src={imageUrl}
            alt={lostPet.pet.name}
            className={styles.petDetailImage}
            onError={() => setImageError(true)}
          />
        ) : (
          <div className={styles.imagePlaceholder}>
            <span>No Image</span>
          </div>
        )}
        <h1 className={styles.petDetailName}>{lostPet.pet.name}</h1>

        <div className={styles.petDetailItemBig}>
          <span className={styles.petDetailLabel}>Contact Email</span>
          <div className={styles.petDetailValue}>{lostPet.user.email}</div>
        </div>
        <div className={styles.petDetailGrid}>
          <div className={styles.petDetailItem}>
            <span className={styles.petDetailLabel}>Species</span>
            <div className={styles.petDetailValue}>{lostPet.pet.species}</div>
          </div>
          <div className={styles.petDetailItem}>
            <span className={styles.petDetailLabel}>Breed</span>
            <div className={styles.petDetailValue}>{lostPet.pet.breed}</div>
          </div>
          <div className={styles.petDetailItem}>
            <span className={styles.petDetailLabel}>Gender</span>
            <div className={styles.petDetailValue}>{lostPet.pet.gender}</div>
          </div>
          <div className={styles.petDetailItem}>
            <span className={styles.petDetailLabel}>Date of Birth</span>
            <div className={styles.petDetailValue}>{new Date(lostPet.pet.dob).toLocaleDateString()}</div>
          </div>
          <div className={styles.petDetailItem}>
            <span className={styles.petDetailLabel}>Weight</span>
            <div className={styles.petDetailValue}>{lostPet.pet.weight} kg</div>
          </div>
          <div className={styles.petDetailItem}>
            <span className={styles.petDetailLabel}>Height</span>
            <div className={styles.petDetailValue}>{lostPet.pet.height} cm</div>
          </div>
          <div className={styles.petDetailItem}>
            <span className={styles.petDetailLabel}>Location</span>
            <div className={styles.petDetailValue}>{lostPet.location}</div>
          </div>
          <div className={styles.petDetailItem}>
            <span className={styles.petDetailLabel}>Status</span>
            <div className={styles.petDetailValue}>{lostPet.status}</div>
          </div>
        </div>
        <div className={styles.petDetailItemBig}>
          <span className={styles.petDetailLabel}>Date Lost</span>
          <div className={styles.petDetailValue}>{new Date(lostPet.date_lost).toLocaleDateString()}</div>
        </div>

        {(userId == lostPet.user.id || userRole == "admin") && (
          <div className={styles.buttonContainer}>
            <UpdateLostPetButton lostPet={lostPet} onSuccess={fetchLostPet} />
            <DeleteLostPetButton lostPet={lostPet} onSuccess={fetchLostPet} />
          </div>
        )}
      </div>
    </div>
  )
}

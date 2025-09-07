"use client"

import { useState, useEffect } from "react"
import { useParams, useRouter } from "next/navigation"
import { getPetMarketAction } from "@/actions/petMarketActions"
import { getUserIdAction, getUserRoleAction } from "@/actions/authActions"
import { UpdatePetMarketButton, DeletePetMarketButton } from "@/components/buttons/buttons"
import styles from "./page.module.css"

export default function PetDetailPage() {
  const params = useParams()
  const [userId, setUserId] = useState(null)
  const [userRole, setUserRole] = useState(null)
  const [petMarket, setPetMarket] = useState(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState("")
  const [imageUrl, setImageUrl] = useState(null);
  const [imageError, setImageError] = useState(false)
  const router = useRouter()
  
  const fetchPetMarket = async () => {
    setLoading(true)
    setError("")

    try {
      const result = await getPetMarketAction(params.pet_id)
      console.log(result)
      if (result.error) {
        setError(result.error)
        setPetMarket(null)
      } else {
        setPetMarket(result.data)
        setImageUrl(result.data.pet.image_url ? `${process.env.NEXT_PUBLIC_BASE_URL}${result.data.pet.image_url}` : "/placeholder.svg?height=200&width=200&query=pet");
      }
    } catch (err) {
      setError("Failed to fetch pet details")
      setPetMarket(null)
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
    
    if (params.pet_id) {
      fetchPetMarket()
    }
  }, [params.pet_id])

  const handleBackToPets = () => {
    router.back()
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
          Back to Shop
        </button>
      </div>
    )
  }

  if (!petMarket) {
    return (
      <div className={styles.container}>
        <div className={styles.notFound}>
          <h2>Pet not found</h2>
          <p>The pet you're looking for doesn't exist or you don't ha64ve permission to view it.</p>
          <button className={styles.backButton} onClick={handleBackToPets}>
            Back to Shop
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
            alt={petMarket.pet.name}
            className={styles.petDetailImage}
            onError={() => setImageError(true)}
          />
        ) : (
          <div className={styles.imagePlaceholder}>
            <span>No Image</span>
          </div>
        )}
        <h1 className={styles.petDetailName}>{petMarket.pet.name}</h1>

        <div className={styles.petDetailItemBig}>
          <span className={styles.petDetailLabel}>Contact Email</span>
          <div className={styles.petDetailValue}>{petMarket.user.email}</div>
        </div>
        <div className={styles.petDetailGrid}>
          <div className={styles.petDetailItem}>
            <span className={styles.petDetailLabel}>Species</span>
            <div className={styles.petDetailValue}>{petMarket.pet.species}</div>
          </div>
          <div className={styles.petDetailItem}>
            <span className={styles.petDetailLabel}>Breed</span>
            <div className={styles.petDetailValue}>{petMarket.pet.breed}</div>
          </div>
          <div className={styles.petDetailItem}>
            <span className={styles.petDetailLabel}>Gender</span>
            <div className={styles.petDetailValue}>{petMarket.pet.gender}</div>
          </div>
          <div className={styles.petDetailItem}>
            <span className={styles.petDetailLabel}>Date of Birth</span>
            <div className={styles.petDetailValue}>{new Date(petMarket.pet.dob).toLocaleDateString()}</div>
          </div>
          <div className={styles.petDetailItem}>
            <span className={styles.petDetailLabel}>Weight</span>
            <div className={styles.petDetailValue}>{petMarket.pet.weight} kg</div>
          </div>
          <div className={styles.petDetailItem}>
            <span className={styles.petDetailLabel}>Height</span>
            <div className={styles.petDetailValue}>{petMarket.pet.height} cm</div>
          </div>
          <div className={styles.petDetailItem}>
            <span className={styles.petDetailLabel}>For</span>
            <div className={styles.petDetailValue}>{petMarket.type}</div>
          </div>
          <div className={styles.petDetailItem}>
            <span className={styles.petDetailLabel}>Status</span>
            <div className={styles.petDetailValue}>{petMarket.status}</div>
          </div>
        </div>
        {petMarket.fee && (
          <div className={styles.petDetailItem}>
            <span className={styles.petDetailLabel}>Price:</span>
            <span className={styles.petDetailValue}>${petMarket.fee}</span>
          </div>
        )}
        <div className={styles.petDetailItemBig}>
          <span className={styles.petDetailLabel}>Description</span>
          <div className={styles.petDetailValue}>{petMarket.description}</div>
        </div>

        {(userId == petMarket.user.id || userRole == "admin") && (
          <div className={styles.buttonContainer}>
            <UpdatePetMarketButton petMarket={petMarket} onSuccess={fetchPetMarket} />
            <DeletePetMarketButton petMarket={petMarket} onSuccess={fetchPetMarket} />
          </div>
        )}
      </div>
    </div>
  )
}

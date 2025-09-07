"use client"

import { useState, useEffect } from "react"
import { useParams, useRouter } from "next/navigation"
import { getPetAction } from "@/actions/petActions"
import { getMedicalLogsAction } from "@/actions/medicalLogActions"
import { getUserIdAction } from "@/actions/authActions"
import { UpdatePetButton, DeletePetButton, UpdateMedicalLogButton, DeleteMedicalLogButton } from "@/components/buttons/buttons"
import styles from "./page.module.css"

export default function PetDetailPage() {
  const params = useParams()
  const [userId, setUserId] = useState(null)
  const [pet, setPet] = useState(null)
  const [medicalLogs, setMedicalLogs] = useState([])
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState("")
  const router = useRouter()
  const [imageUrl, setImageUrl] = useState(null);
  const [imageError, setImageError] = useState(false)
  
  const fetchPet = async () => {
    setLoading(true)
    setError("")

    try {
      const result = await getPetAction(params.pet_id)
      console.log("re", result)
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

  const fetchMedicalLogs = async () => {
    setLoading(true)
    setError("")

    try {
      const result = await getMedicalLogsAction(params.pet_id)
      console.log("result", result)
      if (result.error) {
        setError(result.error)
        setMedicalLogs(null)
      } else {
        setMedicalLogs(result.data)
      }
    } catch (err) {
      setError("Failed to fetch pet details")
      setMedicalLogs(null)
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

    if (params.pet_id) {
      fetchPet()
      fetchMedicalLogs()
    }
  }, [params.pet_id])

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
          <p>The pet you're looking for doesn't exist or you don't have permission to view it.</p>
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

      {medicalLogs.length > 0 &&
        medicalLogs.map((log) => (
          <div className={styles.petDetailCard}>
            <h1 className={styles.petDetailName}>Medical log #{log.id}</h1>

            <div className={styles.petDetailGrid}>
              <div className={styles.petDetailItem}>
                <span className={styles.petDetailLabel}>Visit date</span>
                <div className={styles.petDetailValue}>{new Date(log.visit_date).toLocaleDateString()}</div>
              </div>
              <div className={styles.petDetailItem}>
                <span className={styles.petDetailLabel}>Vet Name</span>
                <div className={styles.petDetailValue}>{log.vet_name}</div>
              </div>
              <div className={styles.petDetailItem}>
                <span className={styles.petDetailLabel}>Clinic Name</span>
                <div className={styles.petDetailValue}>{log.clinic_name}</div>
              </div>
              <div className={styles.petDetailItem}>
                <span className={styles.petDetailLabel}>Diagnosis</span>
                <div className={styles.petDetailValue}>{log.diagnosis}</div>
              </div>
            </div>
            {log.notes && (
              <div className={styles.petDetailItemBig}>
                <span className={styles.petDetailLabel}>Notes</span>
                <span className={styles.petDetailValue}>{log.notes}</span>
              </div>
            )}
            {log.reason_for_visit && (
              <div className={styles.petDetailItemBig}>
                <span className={styles.petDetailLabel}>Reason for visit</span>
                <span className={styles.petDetailValue}>{log.reason_for_visit}</span>
              </div>
            )}
            {log.treatment_prescribed && (
              <div className={styles.petDetailItemBig}>
                <span className={styles.petDetailLabel}>Treatment prescribed</span>
                <span className={styles.petDetailValue}>{log.treatment_prescribed}</span>
              </div>
            )}
            {log.attachment_url && (
              <div className={styles.petDetailItemBig}>
                <span className={styles.petDetailLabel}>Attachment URL</span>
                <span className={styles.petDetailValue}>{log.attachment_url}</span>
              </div>
            )}

            {userId == pet.user_id && (
              <div className={styles.buttonContainer}>
                <UpdateMedicalLogButton onSuccess={fetchMedicalLogs} medicalLog={log} />
                <DeleteMedicalLogButton onSuccess={fetchMedicalLogs} medicalLog={log} />
              </div>
            )}
          </div>
        )) 
      }
    </div>
  )
}

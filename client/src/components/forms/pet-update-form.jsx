"use client"

import { useState, useEffect } from "react"
import { useRouter } from "next/navigation"
import { getPetAction, updatePetAction, deletePetAction } from "@/actions/petActions"
import {UpdatePetButton, DeletePetButton} from "@/components/buttons/buttons"
import ConfirmationModal from "@/components/modals/confirmation-modal"
import styles from "./pet-update-form.module.css"

export default function PetUpdateForm({ petId }) {
  const [formData, setFormData] = useState({
    name: "",
    species: "",
    breed: "",
    dob: "",
    gender: "",
    weight: "",
    height: "",
    image_url: "",
  })
  const [errors, setErrors] = useState({})
  const [successMessage, setSuccessMessage] = useState("")
  const [isLoading, setIsLoading] = useState(true)
  const [isModalOpen, setIsModalOpen] = useState(false)
  const router = useRouter()

  useEffect(() => {
    async function fetchPetData() {
      if (!petId) {
        setErrors({ general: "Pet ID not found." })
        setIsLoading(false)
        return
      }
      const result = await getPetAction(petId)
      if (result.data) {
        setFormData({
          name: result.data.name || "",
          species: result.data.species || "",
          breed: result.data.breed || "",
          dob: result.data.dob ? result.data.dob.split("T")[0] : "", // Format date for input type="date"
          gender: result.data.gender || "",
          weight: result.data.weight || "",
          height: result.data.height || "",
          image_url: result.data.image_url || "",
        })
        setSuccessMessage("")
      } else if (result.error) {
        setErrors({ general: result.error.general || "Failed to load pet data." })
      }
      setIsLoading(false)
    }
    fetchPetData()
  }, [petId])

  const handleChange = (e) => {
    const { name, value } = e.target
    setFormData((prev) => ({ ...prev, [name]: value }))
  }

  const handleSubmit = async (event) => {
    event.preventDefault()
    setErrors({})
    setSuccessMessage("")

    const dataToUpdate = new FormData(event.currentTarget)

    const result = await updatePetAction(petId, dataToUpdate)

    if (result.success) {
      setSuccessMessage(result.success)
      setErrors({})
      // Re-fetch data to ensure UI is up-to-date after successful update
      const updatedResult = await getPetAction(petId)
      if (updatedResult.data) {
        setFormData({
          name: updatedResult.data.name || "",
          species: updatedResult.data.species || "",
          breed: updatedResult.data.breed || "",
          dob: updatedResult.data.dob ? updatedResult.data.dob.split("T")[0] : "",
          gender: updatedResult.data.gender || "",
          weight: updatedResult.data.weight || "",
          height: updatedResult.data.height || "",
          image_url: updatedResult.data.image_url || "",
        })
      }
    } else if (result.error) {
      setErrors(result.error)
    } else {
      setErrors({ general: "An unexpected error occurred during update." })
    }
  }

  const handleDeleteClick = () => {
    setIsModalOpen(true)
  }

  const handleConfirmDelete = async () => {
    setIsModalOpen(false)
    const result = await deletePetAction(petId)
    if (result.success) {
      alert(result.success)
      router.push("/profile/pets") // Redirect to pets list after deletion
    } else if (result.error) {
      setErrors({ general: result.error.general || "Failed to delete pet." })
    }
  }

  if (isLoading) {
    return <div className={styles["loading-message"]}>Loading pet profile...</div>
  }

  return (
    <div className={styles["pet-form-container"]}>
      <form onSubmit={handleSubmit} className={styles["pet-form"]}>
        <h2 className={styles["form-title"]}>Pet Profile</h2>

        {errors.general && <div className={styles["error-message"]}>{errors.general}</div>}
        {successMessage && <div className={styles["success-message"]}>{successMessage}</div>}

        <div className={styles["form-group"]}>
          <label htmlFor="name" className={styles["form-label"]}>
            Pet Name
          </label>
          <input
            id="name"
            name="name"
            type="text"
            placeholder="e.g., Buddy"
            value={formData.name}
            onChange={handleChange}
            required
            className={styles["form-input"]}
          />
          {errors.name && <p className={styles["field-error"]}>{errors.name}</p>}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="species" className={styles["form-label"]}>
            Species
          </label>
          <input
            id="species"
            name="species"
            type="text"
            placeholder="e.g., Dog, Cat"
            value={formData.species}
            onChange={handleChange}
            className={styles["form-input"]}
          />
          {errors.species && <p className={styles["field-error"]}>{errors.species}</p>}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="breed" className={styles["form-label"]}>
            Breed
          </label>
          <input
            id="breed"
            name="breed"
            type="text"
            placeholder="e.g., Golden Retriever"
            value={formData.breed}
            onChange={handleChange}
            className={styles["form-input"]}
          />
          {errors.breed && <p className={styles["field-error"]}>{errors.breed}</p>}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="dob" className={styles["form-label"]}>
            Date of Birth
          </label>
          <input
            id="dob"
            name="dob"
            type="date"
            value={formData.dob}
            onChange={handleChange}
            className={styles["form-input"]}
          />
          {errors.dob && <p className={styles["field-error"]}>{errors.dob}</p>}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="gender" className={styles["form-label"]}>
            Gender
          </label>
          <select
            id="gender"
            name="gender"
            value={formData.gender}
            onChange={handleChange}
            className={styles["form-input"]}
          >
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Unknown">Unknown</option>
          </select>
          {errors.gender && <p className={styles["field-error"]}>{errors.gender}</p>}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="weight" className={styles["form-label"]}>
            Weight (kg)
          </label>
          <input
            id="weight"
            name="weight"
            type="number"
            step="0.1"
            placeholder="e.g., 25.5"
            value={formData.weight}
            onChange={handleChange}
            className={styles["form-input"]}
          />
          {errors.weight && <p className={styles["field-error"]}>{errors.weight}</p>}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="height" className={styles["form-label"]}>
            Height (cm)
          </label>
          <input
            id="height"
            name="height"
            type="number"
            step="0.1"
            placeholder="e.g., 50"
            value={formData.height}
            onChange={handleChange}
            className={styles["form-input"]}
          />
          {errors.height && <p className={styles["field-error"]}>{errors.height}</p>}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="image_url" className={styles["form-label"]}>
            Image URL
          </label>
          <input
            id="image_url"
            name="image_url"
            type="url"
            placeholder="https://example.com/pet.jpg"
            value={formData.image_url}
            onChange={handleChange}
            className={styles["form-input"]}
          />
          {errors.image_url && <p className={styles["field-error"]}>{errors.image_url}</p>}
        </div>

        <UpdatePetButton />
        <DeletePetButton onClick={handleDeleteClick} />
      </form>

      <ConfirmationModal
        isOpen={isModalOpen}
        onClose={() => setIsModalOpen(false)}
        onConfirm={handleConfirmDelete}
        title="Confirm Deletion"
        message="Are you sure you want to delete this pet? This action cannot be undone."
      />
    </div>
  )
}

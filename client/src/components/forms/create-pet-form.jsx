"use client"

import { useState } from "react"
import { useRouter } from "next/navigation"
import { createPetAction } from "@/app/actions/petActions"
import UpdatePetButton from "@/components/buttons/update-pet-button" // Reusing the update button for submission
import styles from "./create-pet-form.module.css"

export default function CreatePetForm() {
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
  const router = useRouter()

  const handleChange = (e) => {
    const { name, value } = e.target
    setFormData((prev) => ({ ...prev, [name]: value }))
  }

  const handleSubmit = async (event) => {
    event.preventDefault()
    setErrors({})
    setSuccessMessage("")

    const dataToCreate = new FormData(event.currentTarget)

    const result = await createPetAction(dataToCreate)

    if (result.success) {
      setSuccessMessage(result.success)
      setErrors({})
      // Optionally clear form or redirect
      setFormData({
        name: "",
        species: "",
        breed: "",
        dob: "",
        gender: "",
        weight: "",
        height: "",
        image_url: "",
      })
      router.push("/profile/pets") // Redirect to pets list after creation
    } else if (result.error) {
      setErrors(result.error)
    } else {
      setErrors({ general: "An unexpected error occurred during pet creation." })
    }
  }

  return (
    <div className={styles["pet-form-container"]}>
      <form onSubmit={handleSubmit} className={styles["pet-form"]}>
        <h2 className={styles["form-title"]}>Add New Pet</h2>
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
        <UpdatePetButton /> {/* Reusing the update button for submission */}
      </form>
    </div>
  )
}

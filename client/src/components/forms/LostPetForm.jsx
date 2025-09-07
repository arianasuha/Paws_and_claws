"use client"

import { useState } from "react"
import {CreateLostPetSubmitButton} from "@/components/buttons/buttons"
import styles from "./LostPetForm.module.css"

export default function LostPetForm({ onSubmit, errors = {}, isSubmitting = false, initialData = {} }) {
  const [formData, setFormData] = useState({
    location: initialData.location || "",
    date_lost: initialData.date_lost || new Date().toISOString().split("T")[0],
  })

  const handleChange = (e) => {
    const { name, value } = e.target
    setFormData((prev) => ({
      ...prev,
      [name]: value,
    }))
  }

  const handleSubmit = (e) => {
    e.preventDefault()
    onSubmit(formData, formData.type)
  }

  return (
    <form onSubmit={handleSubmit} className={styles.form}>
      <div className={styles.formGroup}>
        <label className={styles.label}>Location *</label>
        <input
          type="text"
          name="location"
          className={styles.input}
          required
          onChange={handleChange}
          placeholder="Enter Location"
        />
        {errors.location && <span className={styles.errorText}>{errors.location}</span>}
      </div>

      <div className={styles.formGroup}>
        <label htmlFor="date_lost" className={styles.label}>
          Date Pet Lost *
        </label>
        <input 
          type="date" 
          name="date_lost"
          onChange={handleChange}
          className={styles.input} 
          required 
          />
        {errors.date_lost && <div className={styles.errorText}>{errors.date_lost}</div>}
      </div>

      {errors.error && <div className={styles.generalError}>{errors.error}</div>}

      <CreateLostPetSubmitButton isSubmitting={isSubmitting} />
    </form>
  )
}

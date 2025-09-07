"use client"

import { useState } from "react"
import {CreatePetMarketSubmitButton} from "@/components/buttons/buttons"
import styles from "./PetMarketForm.module.css"

export default function PetMarketForm({ onSubmit, errors = {}, isSubmitting = false, initialData = {} }) {
  const [formData, setFormData] = useState({
    type: initialData.type || "sale",
    description: initialData.description || "",
    fee: initialData.fee || null,
    date: initialData.date || new Date().toISOString().split("T")[0],
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
        <label htmlFor="type" className={styles.label}>
          Listing Type *
        </label>
        <select id="type" name="type" onChange={handleChange} className={styles.select} required>
          <option value="sale">For Sale</option>
          <option value="adoption">For Adoption</option>
        </select>
        {errors.type && <div className={styles.error}>{errors.type}</div>}
      </div>

      <div className={styles.formGroup}>
        <label htmlFor="description" className={styles.label}>
          Description *
        </label>
        <textarea
          id="description"
          name="description"
          onChange={handleChange}
          placeholder="Describe your pet and any special requirements..."
          className={styles.textarea}
          rows="4"
          required
        />
        {errors.description && <div className={styles.error}>{errors.description}</div>}
      </div>

      {formData.type === "sale" && (
        <div className={styles.formGroup}>
          <label htmlFor="fee" className={styles.label}>
            Price ($) *
          </label>
          <input
            type="number"
            id="fee"
            name="fee"
            onChange={handleChange}
            placeholder="0.00"
            min="0"
            step="0.01"
            className={styles.input}
            required
          />
          {errors.fee && <div className={styles.error}>{errors.fee}</div>}
        </div>
      )}

      {errors.error && <div className={styles.generalError}>{errors.error}</div>}

      <CreatePetMarketSubmitButton isSubmitting={isSubmitting} />
    </form>
  )
}

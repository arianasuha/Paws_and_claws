"use client"

import { useState } from "react"
import styles from "./UpdatePetMarketForm.module.css"

export default function UpdateLostPetForm({ lostPet, onClose, action, success, errors }) {
  const [isSubmitting, setIsSubmitting] = useState(false);
  const handleSubmit = async (formData) => {
    setIsSubmitting(true);
    await action(formData);
    setIsSubmitting(false);
  };

  return (
    <form action={handleSubmit} className={styles.form}>
      <div className={styles.formGroup}>
        <label className={styles.label}>Location</label>
        <input
          type="text"
          name="location"
          className={styles.input}
          required
          defaultValue={lostPet.location}
          placeholder="Enter Location"
        />
        {errors.location && <span className={styles.errorText}>{errors.location}</span>}
      </div>

      <div className={styles.formGroup}>
        <label htmlFor="date_lost" className={styles.label}>
          Date Pet Lost
        </label>
        <input 
          type="date" 
          name="date_lost"
          defaultValue={lostPet.date_lost}
          className={styles.input} 
          required 
          />
        {errors.date_lost && <div className={styles.errorText}>{errors.date_lost}</div>}
      </div>

      <div className={styles.formGroup}>
        <label htmlFor="status" className={styles.label}>
          Update Status
        </label>
        <select id="status" name="status" defaultValue={lostPet.status} className={styles.select}>
          <option value="missing">Missing</option>
          <option value="found">Found</option>
        </select>
        {errors.status && <div className={styles.errorText}>{errors.status}</div>}
      </div>

      {success && <div className={styles.successMessage}>{success}</div>}
      {errors.error && <div className={styles.errorMessage}>{errors.error}</div>}
      

      <div className={styles.modalActions}>
        <button type="button" onClick={onClose} className={styles.cancelButton} disabled={isSubmitting}>
          Cancel
        </button>
        <button type="submit" className={styles.submitButton} disabled={isSubmitting}>
          {isSubmitting ? "Updating..." : "Update Lost Details"}
        </button>
      </div>
    </form>
  )
}

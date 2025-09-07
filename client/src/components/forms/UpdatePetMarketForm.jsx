"use client"

import { useState } from "react"
import styles from "./UpdatePetMarketForm.module.css"

export default function UpdatePetMarketForm({ petMarket, onClose, action, success, errors }) {
  const [isSubmitting, setIsSubmitting] = useState(false);
  const handleSubmit = async (formData) => {
    setIsSubmitting(true);
    await action(formData);
    setIsSubmitting(false);
  };

  return (
    <form action={handleSubmit} className={styles.form}>
      <div className={styles.formGroup}>
        <label htmlFor="description" className={styles.label}>
          Description
        </label>
        <textarea
          id="description"
          name="description"
          defaultValue={petMarket.description}
          placeholder="Describe your pet and any special requirements..."
          className={styles.textarea}
          rows="4"
          required
        />
        {errors.description && <div className={styles.errorText}>{errors.description}</div>}
      </div>

      <div className={styles.formGroup}>
        <label htmlFor="status" className={styles.label}>
          Update Status
        </label>
        <select id="status" name="status" defaultValue={petMarket.status} className={styles.select}>
          <option value="">None</option>
          {petMarket.type === "adoption" && <option value="adopted">Adopted</option>}
          {petMarket.type === "sale" && <option value="sold">Sold</option>}
        </select>
        {errors.status && <div className={styles.errorText}>{errors.status}</div>}
      </div>

      {petMarket.type === "sale" && (
        <div className={styles.formGroup}>
          <label htmlFor="fee" className={styles.label}>
            Price ($)
          </label>
          <input
            type="number"
            id="fee"
            name="fee"
            defaultValue={petMarket.fee}
            placeholder="0.00"
            min="0"
            step="0.01"
            className={styles.input}
            required
          />
          {errors.fee && <div className={styles.errorText}>{errors.fee}</div>}
        </div>
      )}

      {success && <div className={styles.successMessage}>{success}</div>}
      {errors.error && <div className={styles.errorMessage}>{errors.error}</div>}
      

      <div className={styles.modalActions}>
        <button type="button" onClick={onClose} className={styles.cancelButton} disabled={isSubmitting}>
          Cancel
        </button>
        <button type="submit" className={styles.submitButton} disabled={isSubmitting}>
          {isSubmitting ? "Updating..." : "Update Market Details"}
        </button>
      </div>
    </form>
  )
}

"use client"

import { useState } from "react"
import styles from "./UpdatePetMarketForm.module.css"

export default function UpdatePetMarketForm({ medicalLog, onClose, action, success, errors }) {
  const [isSubmitting, setIsSubmitting] = useState(false);
  const handleSubmit = async (formData) => {
    setIsSubmitting(true);
    await action(formData);
    setIsSubmitting(false);
  };

  return (
    <form action={handleSubmit} className={styles.form}>
      <div className={styles.formGroup}>
        <label htmlFor="visit_date" className={styles.label}>
          Visit Date *
        </label>
        <input 
          type="date" 
          name="visit_date"
          defaultValue={medicalLog.visit_date}
          className={styles.input} 
          required 
          />
        {errors.visit_date && <div className={styles.errorText}>{errors.visit_date}</div>}
      </div>

      <div className={styles.formGroup}>
        <label className={styles.label}>Diagnosis *</label>
        <input
          type="text"
          name="diagnosis"
          defaultValue={medicalLog.diagnosis}
          className={styles.input}
          required
          placeholder="Enter Diagnosis"
        />
        {errors.diagnosis && <span className={styles.errorText}>{errors.diagnosis}</span>}
      </div>

      <div className={styles.formGroup}>
        <label className={styles.label}>Clinic Name *</label>
        <input
          type="text"
          name="clinic_name"
          defaultValue={medicalLog.clinic_name}
          className={styles.input}
          required
          placeholder="Enter Clinic Name"
        />
        {errors.clinic_name && <span className={styles.errorText}>{errors.clinic_name}</span>}
      </div>

      <div className={styles.formGroup}>
        <label className={styles.label}>Vet Name *</label>
        <input
          type="text"
          name="vet_name"
          defaultValue={medicalLog.vet_name}
          className={styles.input}
          required
          placeholder="Enter Vet Name"
        />
        {errors.vet_name && <span className={styles.errorText}>{errors.vet_name}</span>}
      </div>

      <div className={styles.formGroup}>
        <label className={styles.label}>Attachment URL</label>
        <input
          type="text"
          name="attachment_url"
          defaultValue={medicalLog.attachment_url}
          className={styles.input}
          placeholder="Enter Attachment URL"
        />
        {errors.attachment_url && <span className={styles.errorText}>{errors.attachment_url}</span>}
      </div>

      <div className={styles.formGroup}>
        <label htmlFor="notes" className={styles.label}>
          Notes
        </label>
        <textarea
          id="notes"
          name="notes"
          defaultValue={medicalLog.notes}
          placeholder="Describe your notes..."
          className={styles.textarea}
          rows="4"
        />
        {errors.notes && <div className={styles.error}>{errors.notes}</div>}
      </div>

      <div className={styles.formGroup}>
        <label htmlFor="reason_for_visit" className={styles.label}>
          Reason for Visit
        </label>
        <textarea
          id="reason_for_visit"
          name="reason_for_visit"
          defaultValue={medicalLog.reason_for_visit}
          placeholder="Describe your reason for visit..."
          className={styles.textarea}
          rows="4"
        />
        {errors.reason_for_visit && <div className={styles.error}>{errors.reason_for_visit}</div>}
      </div>

      <div className={styles.formGroup}>
        <label htmlFor="treatment_prescribed" className={styles.label}>
          Treatment Prescribed
        </label>
        <textarea
          id="treatment_prescribed"
          name="treatment_prescribed"
          defaultValue={medicalLog.treatment_prescribed}
          placeholder="Describe your pet and any special requirements..."
          className={styles.textarea}
          rows="4"
        />
        {errors.treatment_prescribed && <div className={styles.error}>{errors.treatment_prescribed}</div>}
      </div>

      {success && <div className={styles.successMessage}>{success}</div>}
      {errors.error && <div className={styles.errorMessage}>{errors.error}</div>}
      

      <div className={styles.modalActions}>
        <button type="button" onClick={onClose} className={styles.cancelButton} disabled={isSubmitting}>
          Cancel
        </button>
        <button type="submit" className={styles.submitButton} disabled={isSubmitting}>
          {isSubmitting ? "Updating..." : "Update Medical Log"}
        </button>
      </div>
    </form>
  )
}

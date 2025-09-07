"use client"

import { useState } from "react"
import { CreateMedicalSubmitButton } from "@/components/buttons/buttons"
import styles from "./MedicalForm.module.css"

export default function MedicalForm({ onSubmit, errors = {}, isSubmitting = false, initialData = {} }) {
  const [formData, setFormData] = useState({
    visit_date: initialData.visit_date || new Date().toISOString().split("T")[0],
    vet_name: initialData.vet_name || "",
    clinic_name: initialData.clinic_name || "",
    diagnosis: initialData.diagnosis || "",
    attachment_url: initialData.attachment_url || "",
    notes: initialData.notes || "",
    reason_for_visit: initialData.reason_for_visit || "",
    treatment_prescribed: initialData.treatment_prescribed || "",
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
        <label htmlFor="visit_date" className={styles.label}>
          Visit Date *
        </label>
        <input 
          type="date" 
          name="visit_date"
          onChange={handleChange}
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
          className={styles.input}
          required
          onChange={handleChange}
          placeholder="Enter Diagnosis"
        />
        {errors.diagnosis && <span className={styles.errorText}>{errors.diagnosis}</span>}
      </div>

      <div className={styles.formGroup}>
        <label className={styles.label}>Clinic Name *</label>
        <input
          type="text"
          name="clinic_name"
          className={styles.input}
          required
          onChange={handleChange}
          placeholder="Enter Clinic Name"
        />
        {errors.clinic_name && <span className={styles.errorText}>{errors.clinic_name}</span>}
      </div>

      <div className={styles.formGroup}>
        <label className={styles.label}>Vet Name *</label>
        <input
          type="text"
          name="vet_name"
          className={styles.input}
          required
          onChange={handleChange}
          placeholder="Enter Vet Name"
        />
        {errors.vet_name && <span className={styles.errorText}>{errors.vet_name}</span>}
      </div>

      <div className={styles.formGroup}>
        <label className={styles.label}>Attachment URL</label>
        <input
          type="text"
          name="attachment_url"
          className={styles.input}
          onChange={handleChange}
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
          onChange={handleChange}
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
          onChange={handleChange}
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
          onChange={handleChange}
          placeholder="Describe your pet and any special requirements..."
          className={styles.textarea}
          rows="4"
        />
        {errors.treatment_prescribed && <div className={styles.error}>{errors.treatment_prescribed}</div>}
      </div>

      {errors.error && <div className={styles.generalError}>{errors.error}</div>}

      <CreateMedicalSubmitButton isSubmitting={isSubmitting} />
    </form>
  )
}

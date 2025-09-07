"use client"

import { useState, useEffect } from "react"
import { getCategoriesAction } from "@/actions/categoryActions"
import styles from "./PetProductForm.module.css"

export default function PetProductForm({ action, onClose, errors, success }) {
  const [isSubmitting, setIsSubmitting] = useState(false)
  const [fileName, setFileName] = useState("No file chosen")
  const [categories, setCategories] = useState([])

  const fetchCategories = async () => {
    setCategories([])

    try {
      const result = await getCategoriesAction()

      if (result.error) {
        console.error("Error fetching categories:", result.error)
      } else {
        setCategories(result.data)
      }
    } catch (error) {
      console.error("Error fetching categories:", error)
    }
  }
  
  useEffect(() => {
    fetchCategories()
  }, [])

  const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
      setFileName(file.name);
    } else {
      setFileName("No file chosen");
    }
  }

  const handleSubmit = async (formData) => {
    setIsSubmitting(true);
    await action(formData);
    setIsSubmitting(false);
  };

  return (
    <form action={handleSubmit} className={styles.form}>
      <div className={styles.formGroup}>
        <label className={styles.label}>Product Name *</label>
        <input
          type="text"
          id="name"
          name="name"
          className={`${styles.input} ${errors.name ? styles.inputError : ""}`}
          required
          placeholder="Enter product name"
        />
        {errors.name && <span className={styles.errorText}>{errors.name}</span>}
      </div>

      <div className={styles.formGroup}>
        <label htmlFor="description" className={styles.label}>
          Description *
        </label>
        <textarea
          id="description"
          name="description"
          placeholder="Describe your product"
          className={`${styles.textarea} ${errors.description ? styles.inputError : ""}`}
          rows={4}
          required
        />
        {errors.description && <span className={styles.errorText}>{errors.description}</span>}
      </div>

      <div className={styles.formRow}>
        <div className={styles.formGroup}>
          <label htmlFor="price" className={styles.label}>
            Price ($) *
          </label>
          <input
            type="number"
            id="price"
            name="price"
            step="0.01"
            min="0"
            className={`${styles.input} ${errors.price ? styles.inputError : ""}`}
            required
          />
          {errors.price && <span className={styles.errorText}>{errors.price}</span>}
        </div>

        <div className={styles.formGroup}>
          <label htmlFor="stock" className={styles.label}>
            Stock Quantity *
          </label>
          <input
            type="number"
            id="stock"
            name="stock"
            min="0"
            className={`${styles.input} ${errors.stock ? styles.inputError : ""}`}
            required
          />
          {errors.stock && <span className={styles.errorText}>{errors.stock}</span>}
        </div>
      </div>

      <div className={styles.formGroup}>
        <label htmlFor="category" className={styles.label}>Category</label>
        <select id="category" name="category_id" className={styles.select}>
          <option value="">All Categories</option>
          {categories.map((cat) => (
            <option key={cat.id} value={cat.id}>
              {cat.name}
            </option>
          ))}
        </select>
        {errors.category && <span className={styles.errorText}>{errors.category}</span>}
      </div>

     <div className={styles.formGroup}>
        <div className={styles.inputGroup}>
          <label htmlFor="image" className={styles.label}>Pet Image</label>
          <input
            type="file"
            id="image"
            name="image_url"
            accept="image/*"
            onChange={handleFileChange}
            className={styles.inputHidden}
          />

          <div className={styles.inputUpload}>
            <label htmlFor="image" className={styles.inputButton}>
              Choose File
            </label>
            
            <span id="fileNameDisplay" className={styles.inputFileName}>{fileName}</span>
          </div>
        </div>
        {errors.image && <div className={styles.errorText}>{errors.image}</div>}
      </div>

      {success && <div className={styles.successMessage}>{success}</div>}

      {errors.error && <div className={styles.generalError}>{errors.error}</div>}

      <div className={styles.modalActions}>
        <button type="button" onClick={onClose} className={styles.cancelButton} disabled={isSubmitting}>
          Cancel
        </button>
        <button type="submit" className={styles.submitButton} disabled={isSubmitting}>
          {isSubmitting ? "Creating..." : "Create Product"}
        </button>
      </div>
    </form>
  )
}

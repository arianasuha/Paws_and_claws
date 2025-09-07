"use client";

import { useState } from "react";
import styles from "./PetForm.module.css";

export default function UpdatePetForm({
  pet,
  action,
  onClose,
  errors,
  success,
}) {
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [fileName, setFileName] = useState("");

  const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
      setFileName(file.name);
    } else {
      setFileName("No file chosen");
    }
  };

  const handleSubmit = async (formData) => {
    setIsSubmitting(true);
    await action(formData);
    setIsSubmitting(false);
  };

  return (
    <form action={handleSubmit} className={styles.form}>
      <div className={styles.formRow}>
        <div className={styles.formGroup}>
          <label className={styles.label}>Pet Name</label>
          <input
            type="text"
            name="name"
            defaultValue={pet.name}
            className={`${styles.input} ${
              errors.name ? styles.inputError : ""
            }`}
            placeholder="Enter pet name"
          />
          {errors.name && (
            <span className={styles.errorText}>{errors.name}</span>
          )}
        </div>

        <div className={styles.formGroup}>
          <label className={styles.label}>Species</label>
          <select 
            name="species" 
            className={styles.input}
            defaultValue={pet.species}
            >
            <option value="">Select species</option>
            <option value="Dog">Dog</option>
            <option value="Cat">Cat</option>
            <option value="Bird">Bird</option>
            <option value="Fish">Fish</option>
            <option value="Rabbit">Rabbit</option>
            <option value="Other">Other</option>
          </select>
          {errors.species && (
            <span className={styles.errorText}>{errors.species}</span>
          )}
        </div>
      </div>

      <div className={styles.formRow}>
        <div className={styles.formGroup}>
          <label className={styles.label}>Breed</label>
          <input
            type="text"
            name="breed"
            className={styles.input}
            defaultValue={pet.breed}
            placeholder="Enter breed"
          />
          {errors.breed && (
            <span className={styles.errorText}>{errors.breed}</span>
          )}
        </div>

        <div className={styles.formGroup}>
          <label className={styles.label}>Gender</label>
          <select
            name="gender"
            className={styles.input}
            defaultValue={pet.gender}
          >
            <option value="">Select gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
          </select>
          {errors.gender && (
            <span className={styles.errorText}>{errors.gender}</span>
          )}
        </div>
      </div>

      <div className={styles.formRow}>
        <div className={styles.formGroup}>
          <label htmlFor="height" className={styles.label}>
            Height (cm)
          </label>
          <input
            type="number"
            id="height"
            name="height"
            className={styles.input}
            defaultValue={pet.height || 0}
            step="0.1"
            min="0"
          />
          {errors.height && <div className={styles.errorText}>{errors.height}</div>}
        </div>

        <div className={styles.formGroup}>
          <label htmlFor="weight" className={styles.label}>
            Weight (kg)
          </label>
          <input
            type="number"
            id="weight"
            name="weight"
            className={styles.input}
            defaultValue={pet.weight || 0}
            step="0.1"
            min="0"
          />
          {errors.weight && <div className={styles.errorText}>{errors.weight}</div>}
        </div>
      </div>

      <div className={styles.formGroup}>
        <label htmlFor="dob" className={styles.label}>
          Date of Birth
        </label>
        <input
          type="date"
          id="dob"
          name="dob"
          className={styles.input}
          defaultValue={pet.dob}
        />
        {errors.dob && <div className={styles.errorText}>{errors.dob}</div>}
      </div>
      <div className={styles.formGroup}>
        <label className={styles.label}>Description</label>
        <textarea
          name="description"
          className={styles.textarea}
          defaultValue={pet.description}
          placeholder="Additional information about your pet"
          rows="3"
        />
        {errors.description && (
          <span className={styles.errorText}>{errors.description}</span>
        )}
      </div>

      <div className={styles.formGroup}>
        <div className={styles.inputGroup}>
          <label htmlFor="image" className={styles.label}>
            Pet Image
          </label>
          <input
            type="file"
            id="image"
            name="image_url"
            accept="image/*"
            onChange={handleFileChange}
            className={styles.inputHidden}
            defaultChecked={pet.image_url}
          />

          <div className={styles.inputUpload}>
            <label htmlFor="image" className={styles.inputButton}>
              Choose File
            </label>

            <span id="fileNameDisplay" className={styles.inputFileName}>
              {fileName}
            </span>
          </div>
        </div>
        {errors.image && <div className={styles.errorText}>{errors.image}</div>}
      </div>

      {success && <div className={styles.successMessage}>{success}</div>}

      <div className={styles.modalActions}>
        <button type="button" onClick={onClose} className={styles.cancelButton} disabled={isSubmitting}>
          Cancel
        </button>
        <button type="submit" className={styles.submitButton} disabled={isSubmitting}>
          {isSubmitting ? "Updating..." : "Update Pet"}
        </button>
      </div>
    </form>
  );
}

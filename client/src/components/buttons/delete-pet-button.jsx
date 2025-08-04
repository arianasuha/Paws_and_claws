"use client"

import styles from "./delete-pet-button.module.css"

export default function DeletePetButton({ onClick }) {
  return (
    <button type="button" onClick={onClick} className={styles["delete-pet-btn"]}>
      Delete Pet
    </button>
  )
}

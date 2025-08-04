"use client"

import { useFormStatus } from "react-dom"
import styles from "./update-pet-button.module.css"

export default function UpdatePetButton() {
  const { pending } = useFormStatus()

  return (
    <button type="submit" className={styles["update-pet-btn"]} disabled={pending}>
      {pending ? "Updating Pet..." : "Update Pet"}
    </button>
  )
}

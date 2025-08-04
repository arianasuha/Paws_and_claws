"use client"

import { useRouter } from "next/navigation"
import styles from "./show-pets-button.module.css"

export default function ShowPetsButton() {
  const router = useRouter()

  const handleClick = () => {
    router.push("/pets") // This page will be created later
  }

  return (
    <button type="button" onClick={handleClick} className={styles["show-pets-btn"]}>
      Show My Pets
    </button>
  )
}

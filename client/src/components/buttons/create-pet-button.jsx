"use client"

import { useRouter } from "next/navigation"
import styles from "./create-pet-button.module.css"

export default function CreatePetButton() {
  const router = useRouter()

  const handleClick = () => {
    router.push("/pets/create")
  }

  return (
    <button type="button" onClick={handleClick} className={styles["create-pet-btn"]}>
      Add New Pet
    </button>
  )
}

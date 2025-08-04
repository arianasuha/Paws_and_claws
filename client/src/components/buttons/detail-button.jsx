"use client"

import { useRouter } from "next/navigation"
import styles from "./detail-button.module.css"

export default function DetailButton({ petId }) {
  const router = useRouter()

  const handleClick = () => {
    router.push(`/pets/${petId}`)
  }

  return (
    <button type="button" onClick={handleClick} className={styles["detail-btn"]}>
      View Details
    </button>
  )
}

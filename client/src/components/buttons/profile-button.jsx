"use client"

import { useRouter } from "next/navigation"
import styles from "./profile-button.module.css"

export default function ProfileButton({ userId }) {
  const router = useRouter()

  const handleClick = () => {
    if (userId) {
      router.push(`/profile/${userId}`) // Navigate to the dynamic profile page
    } else {
      router.push("/auth/login") // Fallback if no user ID (shouldn't happen if rendered conditionally)
    }
  }

  return (
    <button onClick={handleClick} className={styles["profile-btn"]}>
      Profile
    </button>
  )
}

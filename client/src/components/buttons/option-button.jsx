"use client"

import { useRouter } from "next/navigation"
import styles from "./option-button.module.css"

export default function OptionButton({ href, children }) {
  const router = useRouter()

  const handleClick = () => {
    router.push(href)
  }

  return (
    <button onClick={handleClick} className={styles["option-btn"]}>
      {children}
    </button>
  )
}

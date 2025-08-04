"use client"

import { useFormStatus } from "react-dom"
import styles from "./update-button.module.css"

export default function UpdateButton() {
  const { pending } = useFormStatus()

  return (
    <button type="submit" className={styles["update-btn"]} disabled={pending}>
      {pending ? "Updating..." : "Update Profile"}
    </button>
  )
}

"use client"

import { useFormStatus } from "react-dom"
import styles from "./vet-signup-button.module.css"

export default function VetSignupButton() {
  const { pending } = useFormStatus()

  return (
    <button type="submit" className={styles["vet-signup-btn"]} disabled={pending}>
      {pending ? "Signing Up Vet..." : "Sign Up as Vet"}
    </button>
  )
}

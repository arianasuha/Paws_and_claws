"use client"

import { useFormStatus } from "react-dom"
import styles from "./customer-signup-button.module.css"

export default function SignupButton() {
  const { pending } = useFormStatus()

  return (
    <button type="submit" className={styles["signup-btn"]} disabled={pending}>
      {pending ? "Signing Up..." : "Sign Up"}
    </button>
  )
}

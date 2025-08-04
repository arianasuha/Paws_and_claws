"use client"

import { useFormStatus } from "react-dom"
import styles from "./login-button.module.css"

export default function LoginButton() {
  const { pending } = useFormStatus()

  return (
    <button type="submit" className={styles["login-btn"]} disabled={pending}>
      {pending ? "Logging in..." : "Login"}
    </button>
  )
}
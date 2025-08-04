"use client"

import { useFormStatus } from "react-dom"
import { logoutAction } from "@/app/actions/authActions" // Adjust path as needed
import styles from "./logout-button.module.css"

export default function LogoutButton() {
  const { pending } = useFormStatus()

  return (
    <form action={logoutAction}>
      <button type="submit" className={styles["logout-btn"]} disabled={pending}>
        {pending ? "Logging Out..." : "Logout"}
      </button>
    </form>
  )
}

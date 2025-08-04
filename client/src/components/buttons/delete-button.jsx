"use client"

import styles from "./delete-button.module.css"

export default function DeleteButton({ onClick }) {
  return (
    <button type="button" onClick={onClick} className={styles["delete-btn"]}>
      Delete Account
    </button>
  )
}

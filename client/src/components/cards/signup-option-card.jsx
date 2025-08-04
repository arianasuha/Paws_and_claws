"use client"

import OptionButton from "@/components/buttons/option-button"
import styles from "./signup-option-card.module.css"

export default function SignupOptionCard({ title, description, buttonText, buttonHref }) {
  return (
    <div className={styles["signup-option-card"]}>
      <h3 className={styles["card-title"]}>{title}</h3>
      <p className={styles["card-description"]}>{description}</p>
      <OptionButton href={buttonHref}>{buttonText}</OptionButton>
    </div>
  )
}

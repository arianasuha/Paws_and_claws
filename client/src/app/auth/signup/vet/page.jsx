import VetSignupForm from "@/components/forms/vet-signup-form"
import styles from "./page.module.css"

export default function VetSignupPage() {
  return (
    <div className={styles["vet-signup-page"]}>
      <div className={styles["vet-signup-container"]}>
        <VetSignupForm />
      </div>
    </div>
  )
}

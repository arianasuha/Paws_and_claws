import ServiceSignupForm from "@/components/forms/service-signup-form"
import styles from "./page.module.css"

export default function ServiceSignupPage() {
  return (
    <div className={styles["service-signup-page"]}>
      <div className={styles["service-signup-container"]}>
        <ServiceSignupForm />
      </div>
    </div>
  )
}

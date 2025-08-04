import CustomerSignupForm from "@/components/forms/customer-signup-form"
import styles from "./page.module.css"

export default function SignupPage() {
  return (
    <div className={styles["signup-page"]}>
      <div className={styles["signup-container"]}>
        <CustomerSignupForm />
      </div>
    </div>
  )
}

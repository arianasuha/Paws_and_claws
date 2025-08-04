import LoginForm from "@/components/forms/login-form"
import styles from "./page.module.css"

export default function LoginPage() {
  return (
    <div className={styles["login-page"]}>
      <div className={styles["login-container"]}>
        <LoginForm />
      </div>
    </div>
  )
}

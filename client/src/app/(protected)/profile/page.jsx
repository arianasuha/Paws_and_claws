import { getUserIdAction, getUserRoleAction } from "@/app/actions/authActions"
import CustomerProfileForm from "@/components/forms/customer-profile-form"
import VetProfileForm from "@/components/forms/vet-profile-form"
import styles from "./page.module.css"

export default async function ProfilePage() {
  const userId = await getUserIdAction()
  const userRole = await getUserRoleAction()

  if (!userId || !userRole) {
    return (
      <div className={styles["profile-page"]}>
        <div className={styles["profile-container"]}>
          <div className={styles["error-message"]}>
            Please log in to view your profile.
            <br />
            <a href="/auth/login" className={styles["login-link"]}>
              Go to Login
            </a>
          </div>
        </div>
      </div>
    )
  }

  return (
    <div className={styles["profile-page"]}>
      <div className={styles["profile-container"]}>
        {userRole === "user" && <CustomerProfileForm userId={userId} />}
        {userRole === "vet" && <VetProfileForm userId={userId} />}
        {!["user", "vet"].includes(userRole) && (
          <div className={styles["error-message"]}>Unknown user role. Cannot display profile.</div>
        )}
      </div>
    </div>
  )
}

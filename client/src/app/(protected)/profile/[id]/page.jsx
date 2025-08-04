import { getUserIdAction, getUserRoleAction } from "@/app/actions/authActions"
import CustomerProfileForm from "@/components/forms/customer-profile-form"
import VetProfileForm from "@/components/forms/vet-profile-form"
import styles from "../page.module.css"

export default async function DynamicProfilePage({ params }) {
  const { id } = params // Get the ID from the dynamic route
  const currentSessionUserId = await getUserIdAction()
  const userRole = await getUserRoleAction()

  // Security check: Ensure the ID in the URL matches the logged-in user's ID
  if (!currentSessionUserId || currentSessionUserId !== id) {
    return (
      <div className={styles["profile-page"]}>
        <div className={styles["profile-container"]}>
          <div className={styles["error-message"]}>
            Access Denied: You can only view your own profile.
            <br />
            <a href="/auth/login" className={styles["login-link"]}>
              Go to Login
            </a>
          </div>
        </div>
      </div>
    )
  }

  if (!userRole) {
    return (
      <div className={styles["profile-page"]}>
        <div className={styles["profile-container"]}>
          <div className={styles["error-message"]}>
            User role not found. Please log in again.
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
        {userRole === "user" && <CustomerProfileForm userId={id} />}
        {userRole === "vet" && <VetProfileForm userId={id} />}
        {!["user", "vet"].includes(userRole) && (
          <div className={styles["error-message"]}>Unknown user role. Cannot display profile.</div>
        )}
      </div>
    </div>
  )
}

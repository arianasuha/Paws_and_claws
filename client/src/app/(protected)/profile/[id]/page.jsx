import { getUserIdAction, getUserRoleAction } from "@/actions/authActions"
import { redirect } from "next/navigation"
import ServiceProfileForm from "@/components/forms/service-profile-form"
import CustomerProfileForm from "@/components/forms/customer-profile-form"
import VetProfileForm from "@/components/forms/vet-profile-form"
import styles from "./page.module.css"

export default async function DynamicProfilePage({ params }) {
  const parameters = await params
  const currentSessionUserId = await getUserIdAction()
  const userRole = await getUserRoleAction()

  if (!currentSessionUserId) {
    redirect("/auth/login")
  }

  if (!parameters.id) {
    redirect(`/profile/${currentSessionUserId}`)
  }

  const { id: profileId } = parameters

  if (currentSessionUserId !== profileId) {
    return (
      <div className={styles["profile-page"]}>
        <div className={styles["profile-container"]}>
          <div className={styles["error-message"]}>
            Access Denied: You can only view your own profile.
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
          </div>
        </div>
      </div>
    )
  }

  return (
    <div className={styles["profile-page"]}>
      <div className={styles["profile-container"]}>
        {(userRole === "user" || userRole === "admin") && <CustomerProfileForm userId={profileId} />}
        {userRole === "vet" && <VetProfileForm userId={profileId} />}
        {userRole === "provider" && <ServiceProfileForm userId={profileId} />}

        {!["user", "vet", "provider", "admin"].includes(userRole) && (
          <div className={styles["error-message"]}>
            Unknown user role. Cannot display profile.
          </div>
        )}
      </div>
    </div>
  )
}
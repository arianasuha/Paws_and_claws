"use client"

import ProfileButton from "@/components/buttons/profile-button"
import LogoutButton from "@/components/buttons/logout-button" // Import the new LogoutButton
import styles from "./navbar.module.css"

export default function Navbar({ currentUserId }) {
  return (
    <nav className={styles.navbar}>
      <div className={styles["company-name"]}>Paws and Claws</div>
      <div className={styles["nav-right"]}>
        {currentUserId && <ProfileButton userId={currentUserId} />}
        {currentUserId && <LogoutButton />}
        {!currentUserId && (
          <a href="/auth/login" className={styles["login-link"]}>
            Login
          </a>
        )}
      </div>
    </nav>
  )
}

import SignupOptionCard from "@/components/cards/signup-option-card"
import styles from "./page.module.css"

export default function SignupSelectionPage() {
  return (
    <div className={styles["signup-selection-page"]}>
      <div className={styles["selection-container"]}>
        <h1 className={styles["main-title"]}>Choose Your Account Type</h1>
        <p className={styles["main-description"]}>
          Are you looking for pet care, or are you a veterinary professional?
        </p>
        <div className={styles["cards-wrapper"]}>
          <SignupOptionCard
            title="Customer"
            description="Find trusted vets and manage your pet's health appointments."
            buttonText="Sign Up as Customer"
            buttonHref="/auth/signup/customer"
          />
          <SignupOptionCard
            title="Veterinarian"
            description="Expand your practice and connect with pet owners in need."
            buttonText="Sign Up as Vet"
            buttonHref="/auth/signup/vet"
          />
        </div>
      </div>
    </div>
  )
}

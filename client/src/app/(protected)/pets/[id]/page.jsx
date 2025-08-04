import PetUpdateForm from "@/components/forms/pet-update-form"
import styles from "./page.module.css"

export default function PetDetailPage({ params }) {
  const { id } = params // Get the pet ID from the dynamic route

  return (
    <div className={styles["pet-detail-page"]}>
      <div className={styles["pet-detail-container"]}>
        <PetUpdateForm petId={id} />
      </div>
    </div>
  )
}

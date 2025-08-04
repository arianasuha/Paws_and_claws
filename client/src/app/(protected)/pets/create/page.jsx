import CreatePetForm from "@/components/forms/create-pet-form"
import styles from "./page.module.css"

export default function CreatePetPage() {
  return (
    <div className={styles["create-pet-page"]}>
      <div className={styles["create-pet-container"]}>
        <CreatePetForm />
      </div>
    </div>
  )
}

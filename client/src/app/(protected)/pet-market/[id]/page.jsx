import { getPetMarketAction } from "@/actions/petMarketActions";
import { getUserIdAction } from "@/actions/authActions";
import PetMarketDetail from "@/components/cards/pet-market-detail"; // Assuming you create this new component
import styles from "./page.module.css";

// This is now an async Server Component - no "use client"
export default async function PetMarketDetailPage({ params }) {
  const { id } = await params;

  // Fetch data directly on the server
  const [petMarketResult, userId] = await Promise.all([
    getPetMarketAction(id),
    getUserIdAction(),
  ]);

  // Handle cases where the pet listing isn't found
  if (!petMarketResult.data) {
    return (
      <div className={styles["pet-market-detail-page"]}>
        <div className={styles["pet-market-detail-container"]}>
          <div className={styles["error-message"]}>
            {petMarketResult.error?.general || "Pet market listing not found."}
          </div>
        </div>
      </div>
    );
  }

  // Pass the fetched data as props to the client component
  return (
    <PetMarketDetail
      initialPetMarket={petMarketResult.data}
      currentUserId={userId}
    />
  );
}
"use client";

import { useRouter } from "next/navigation";
import styles from "./pet-market-card.module.css";

export default function PetMarketCard({ petMarket }) {
  const router = useRouter();
  const imageUrl = petMarket.pet.image_url
    ? `${process.env.NEXT_PUBLIC_BASE_URL}${petMarket.pet.image_url}`
    : "/placeholder.svg?height=200&width=200&query=pet";
  const handleCardClick = () => {
    router.push(`/pet-market/${petMarket.id}`);
  };

  const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString();
  };

  const getStatusBadgeClass = (status) => {
    switch (status) {
      case "available":
        return styles["status-available"];
      case "adopted":
        return styles["status-adopted"];
      case "sold":
        return styles["status-sold"];
      default:
        return styles["status-default"];
    }
  };

  const getTypeBadgeClass = (type) => {
    switch (type) {
      case "adoption":
        return styles["type-adoption"];
      case "sale":
        return styles["type-sale"];
      default:
        return styles["type-default"];
    }
  };

  return (
    <div className={styles["pet-market-card"]} onClick={handleCardClick}>
      <input type="hidden" value={petMarket.id} />

      <div className={styles["card-header"]}>
        <img
          src={imageUrl}
          alt={`Image of ${petMarket.pet.name}`}
          className={styles["pet-image"]}
        />
        <div className={styles["badges"]}>
          <span
            className={`${styles["badge"]} ${getTypeBadgeClass(
              petMarket.type
            )}`}
          >
            {petMarket.type}
          </span>
          <span
            className={`${styles["badge"]} ${getStatusBadgeClass(
              petMarket.status
            )}`}
          >
            {petMarket.status}
          </span>
        </div>
      </div>

      <div className={styles["card-content"]}>
        <h3 className={styles["pet-name"]}>{petMarket.pet.name}</h3>
        <p className={styles["pet-details"]}>
          {petMarket.pet.species} • {petMarket.pet.breed}
        </p>
        <p className={styles["pet-info"]}>
          {petMarket.pet.gender} • {petMarket.pet.weight}kg
        </p>

        {petMarket.fee && (
          <p className={styles["fee"]}>Fee: ${petMarket.fee}</p>
        )}

        <p className={styles["description"]}>
          {petMarket.description.length > 100
            ? `${petMarket.description.substring(0, 100)}...`
            : petMarket.description}
        </p>

        <p className={styles["date"]}>Listed: {formatDate(petMarket.date)}</p>
      </div>
    </div>
  );
}

"use client";

import { useState } from "react";
import {
  UpdatePetMarketButton,
  UpdateListingButton,
  DeleteListingButton,
} from "@/components/buttons/buttons";

import styles from "./pet-market-detail.module.css"; // You might need to create or move this CSS file

export default function PetMarketDetail({ initialPetMarket, currentUserId }) {
  // The component now starts with data, no initial loading state needed
  const petMarket = initialPetMarket;

  // Guard against null petMarket object before trying to access its properties
  if (!petMarket) {
    return (
      <div className={styles["pet-market-detail-page"]}>
        <div className={styles["pet-market-detail-container"]}>
          <div className={styles["error-message"]}>
            Pet market listing not found.
          </div>
        </div>
      </div>
    );
  }

  const imageUrl = petMarket.pet.image_url
    ? `${process.env.NEXT_PUBLIC_BASE_URL}${petMarket.pet.image_url}`
    : "/placeholder.svg?height=200&width=200&query=pet";

  const isOwner =
    currentUserId && currentUserId.toString() === petMarket.user_id.toString();

  const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("en-US", {
      year: "numeric",
      month: "long",
      day: "numeric",
    });
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

  // The entire JSX from your original page is moved here
  return (
    <div className={styles["pet-market-detail-page"]}>
      <div className={styles["pet-market-detail-container"]}>
        
        <div className={styles["detail-content"]}>
          <div className={styles["image-section"]}>
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

          <div className={styles["info-section"]}>
            <h1 className={styles["pet-name"]}>{petMarket.pet.name}</h1>

            <div className={styles["pet-details"]}>
              <div className={styles["detail-row"]}>
                <span className={styles["label"]}>Species:</span>
                <span className={styles["value"]}>{petMarket.pet.species}</span>
              </div>
              <div className={styles["detail-row"]}>
                <span className={styles["label"]}>Breed:</span>
                <span className={styles["value"]}>{petMarket.pet.breed}</span>
              </div>
              <div className={styles["detail-row"]}>
                <span className={styles["label"]}>Gender:</span>
                <span className={styles["value"]}>{petMarket.pet.gender}</span>
              </div>
              <div className={styles["detail-row"]}>
                <span className={styles["label"]}>Date of Birth:</span>
                <span className={styles["value"]}>
                  {formatDate(petMarket.pet.dob)}
                </span>
              </div>
              <div className={styles["detail-row"]}>
                <span className={styles["label"]}>Weight:</span>
                <span className={styles["value"]}>
                  {petMarket.pet.weight} kg
                </span>
              </div>
              <div className={styles["detail-row"]}>
                <span className={styles["label"]}>Height:</span>
                <span className={styles["value"]}>
                  {petMarket.pet.height} cm
                </span>
              </div>
            </div>

            {petMarket.fee && (
              <div className={styles["fee-section"]}>
                <span className={styles["fee-label"]}>Fee:</span>
                <span className={styles["fee-value"]}>${petMarket.fee}</span>
              </div>
            )}

            <div className={styles["description-section"]}>
              <h3 className={styles["section-title"]}>Description</h3>
              <p className={styles["description"]}>{petMarket.description}</p>
            </div>

            <div className={styles["listing-info"]}>
              <p className={styles["listing-date"]}>
                Listed on: {formatDate(petMarket.date)}
              </p>
            </div>

            {isOwner && (
              <div className={styles["owner-actions"]}>
                <h3 className={styles["section-title"]}>Manage Listing</h3>
                <UpdatePetMarketButton petMarketId={petMarket.id} />
                <UpdateListingButton petMarketId={petMarket.id} />
                <DeleteListingButton petMarketId={petMarket.id} />
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}

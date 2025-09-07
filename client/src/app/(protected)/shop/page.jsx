"use client"

import { useRouter } from "next/navigation"
import ShopCard from "@/components/cards/ShopCard"
import styles from "./page.module.css"

export default function ShopPage() {
  const router = useRouter()

  const shopCards = [
    {
      id: "pets-for-sale",
      title: "Pets for Sale",
      description: "Find your perfect companion from our selection of pets available for purchase.",
      image: "https://images.unsplash.com/photo-1584290867415-527a8475726d?w=400&h=300&fit=crop",
      onClick: () => router.push("/shop/pet-market?type=sale"),
    },
    {
      id: "pets-for-adoption",
      title: "Pets for Adoption",
      description: "Give a loving home to pets looking for their forever family.",
      image: "https://images.unsplash.com/photo-1583337130417-3346a1be7dee?w=400&h=300&fit=crop",
      onClick: () => router.push("/shop/pet-market?type=adoption"),
    },
    {
      id: "pet-products",
      title: "Pet Products",
      description: "Everything you need to keep your pets happy and healthy.",
      image: "https://images.unsplash.com/photo-1589924691995-400dc9ecc119?w=400&h=300&fit=crop",
      onClick: () => router.push("/shop/pet-product"),
    },
  ]

  return (
    <div className={styles.container}>
      <div className={styles.header}>
        <h1 className={styles.title}>Pet Shop</h1>
        <p className={styles.subtitle}>Discover everything you need for your beloved pets</p>
      </div>

      <div className={styles.cardsGrid}>
        {shopCards.map((card) => (
          <ShopCard
            key={card.id}
            title={card.title}
            description={card.description}
            image={card.image}
            onClick={card.onClick}
          />
        ))}
      </div>
    </div>
  )
}

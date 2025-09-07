"use client"

import { useState } from "react"
import { useRouter } from "next/navigation"
import {AddToCartButton} from "@/components/buttons/buttons"
import styles from "./PetProductCard.module.css"

export default function PetProductCard({ product }) {
  const router = useRouter()
  const [imageError, setImageError] = useState(false)
  const imageUrl = product.image_url
    ? `${process.env.NEXT_PUBLIC_BASE_URL}${product.image_url}`
    : "/placeholder.svg?height=200&width=200&query=pet";

  const handleCardClick = (e) => {
    if (e.target.closest("button")) {
      return
    }
    router.push(`/shop/pet-product/${product.id}`)
  }

  const formatPrice = (price) => {
    return new Intl.NumberFormat("en-US", {
      style: "currency",
      currency: "USD",
    }).format(Number.parseFloat(price))
  }

  return (
    <div className={styles.card} onClick={handleCardClick}>
      <div className={styles.imageContainer}>
        {!imageError ? (
          <img
            src={imageUrl}
            alt={product.name}
            className={styles.image}
            onError={() => setImageError(true)}
          />
        ) : (
          <div className={styles.imagePlaceholder}>
            <span>No Image</span>
          </div>
        )}
        {product.stock <= 5 && product.stock > 0 && (
          <div className={styles.lowStockBadge}>Only {product.stock} left!</div>
        )}
        {product.stock === 0 && <div className={styles.outOfStockBadge}>Out of Stock</div>}
      </div>

      <div className={styles.content}>
        <div className={styles.category}>{product.category?.name || "Uncategorized"}</div>

        <h3 className={styles.name}>{product.name}</h3>

        <p className={styles.description}>
          {product.description?.length > 100 ? `${product.description.substring(0, 100)}...` : product.description}
        </p>

        <div className={styles.priceSection}>
          <span className={styles.price}>{formatPrice(product.price)}</span>
          <span className={styles.stock}>Stock: {product.stock}</span>
        </div>

        <div className={styles.actions}>
          <AddToCartButton productId={product.id} disabled={product.stock === 0} />
        </div>
      </div>
    </div>
  )
}

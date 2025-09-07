"use client"

import styles from "./ShopCard.module.css"

export default function ShopCard({ title, description, image, onClick }) {
  return (
    <div className={styles.card} onClick={onClick}>
      <div className={styles.imageContainer}>
        <img src={image || "/placeholder.svg"} alt={title} className={styles.image} />
      </div>
      <div className={styles.content}>
        <h3 className={styles.title}>{title}</h3>
        <p className={styles.description}>{description}</p>
      </div>
    </div>
  )
}

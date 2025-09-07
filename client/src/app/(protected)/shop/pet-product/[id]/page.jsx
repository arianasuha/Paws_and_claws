"use client"

import { useState, useEffect } from "react"
import { useParams, useRouter } from "next/navigation"
import { getUserRoleAction } from "@/actions/authActions"
import { getPetProductAction } from "@/actions/petProductActions"
import { UpdatePetProductButton, DeletePetProductButton } from "@/components/buttons/buttons"
import styles from "./page.module.css"

export default function PetProductDetailPage() {
  const params = useParams()
  const [product, setProduct] = useState(null)
  const [userRole, setUserRole] = useState(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState("")
  const [imageUrl, setImageUrl] = useState(null);
  const [imageError, setImageError] = useState(false)
  const router = useRouter()

  const fetchProduct = async () => {
    setLoading(true)
    setError("")

    try {
      const result = await getPetProductAction(params.id)
      if (result.error) {
        setError(result.error)
        setProduct(null)
      } else {
        setProduct(result.data)
        setImageUrl(result.data.image_url ? `${process.env.NEXT_PUBLIC_BASE_URL}${result.data.image_url}` : "/placeholder.svg?height=200&width=200&query=product");
      }
    } catch (err) {
      setError("Failed to fetch product details")
      setProduct(null)
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    const fetchUserRole = async () => {
      try {
        const result = await getUserRoleAction()
        if (result.error) {
          setError(result.error)
        } else {
          setUserRole(result)
        }
      } catch (err) {
        setError("Failed to fetch user role")
      }
    }

    fetchUserRole()

    if (params.id) {
      fetchProduct()
    }
  }, [params.id])

  const handleBackToPetProducts = () => {
    router.back()
  }

  if (loading) {
    return (
      <div className={styles.container}>
        <div className={styles.loading}>Loading product details...</div>
      </div>
    )
  }

  if (error) {
    return (
      <div className={styles.container}>
        <div className={styles.error}>{typeof error === "object" ? JSON.stringify(error) : error}</div>
        <button className={styles.backButton} onClick={() => router.push("/shop/pet-product")}>
          Back to Products
        </button>
      </div>
    )
  }

  if (!product) {
    return (
      <div className={styles.container}>
        <div className={styles.notFound}>Product not found</div>
        <button className={styles.backButton} onClick={() => router.push("/shop/pet-product")}>
          Back to Products
        </button>
      </div>
    )
  }

  return (
    <div className={styles.container}>
      <div className={styles.header}>
        <button className={styles.backButton} onClick={handleBackToPetProducts}>
          ← Back to Shop
        </button>
      </div>

      <div className={styles.petDetailCard}>
        {!imageError ? (
          <img
            src={imageUrl}
            alt={product.name}
            className={styles.petDetailImage}
            onError={() => setImageError(true)}
          />
        ) : (
          <div className={styles.imagePlaceholder}>
            <span>No Image</span>
          </div>
        )}
        <h1 className={styles.petDetailName}>{product.name}</h1>

        <div className={styles.petDetailGrid}>
          <div className={styles.petDetailItem}>
            <span className={styles.petDetailLabel}>Category</span>
            <div className={styles.petDetailValue}>{product.category.name}</div>
          </div>
          <div className={styles.petDetailItem}>
            <span className={styles.petDetailLabel}>Stock</span>
            <div className={styles.petDetailValue}>{product.stock}</div>
          </div>
        </div>
        {product && (
          <div className={styles.petDetailItem}>
            <span className={styles.petDetailLabel}>Price</span>
            <span className={styles.petDetailValue}>${product.price}</span>
          </div>
        )}
        <div className={styles.petDetailItemBig}>
          <span className={styles.petDetailLabel}>Description</span>
          <div className={styles.petDetailValue}>{product.description}</div>
        </div>

        {userRole == "admin" && (
          <div className={styles.buttonContainer}>
            <UpdatePetProductButton product={product} onSuccess={fetchProduct} />
            <DeletePetProductButton product={product} onSuccess={fetchProduct} />
          </div>
        )}
      </div>
    </div>
  )
}

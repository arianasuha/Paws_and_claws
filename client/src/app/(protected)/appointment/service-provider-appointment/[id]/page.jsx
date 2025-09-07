"use client"

import { useState, useEffect } from "react"
import { useParams } from "next/navigation"
import { getServiceProviderAction } from "@/actions/serviceActions"
import ServiceProviderDetailCard from "@/components/cards/ServiceProviderDetailCard"
import styles from "./page.module.css"

export default function ServiceProviderDetailPage() {
  const params = useParams()
  const [provider, setProvider] = useState(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState("")

  useEffect(() => {
    const fetchProvider = async () => {
      if (!params.id) return

      setLoading(true)
      setError("")

      try {
        const response = await getServiceProviderAction(params.id)

        if (response.error) {
          setError(response.error)
          setProvider(null)
        } else {
          setProvider(response.data)
        }
      } catch (err) {
        setError("Failed to fetch service provider details")
        setProvider(null)
      } finally {
        setLoading(false)
      }
    }

    fetchProvider()
  }, [params.id])

  if (loading) {
    return (
      <div className={styles.container}>
        <div className={styles.loading}>Loading service provider details...</div>
      </div>
    )
  }

  if (error) {
    return (
      <div className={styles.container}>
        <div className={styles.error}>{error}</div>
      </div>
    )
  }

  if (!provider) {
    return (
      <div className={styles.container}>
        <div className={styles.error}>Service provider not found</div>
      </div>
    )
  }

  return (
    <div className={styles.container}>
      <ServiceProviderDetailCard provider={provider} />
    </div>
  )
}

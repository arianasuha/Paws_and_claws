"use client"

import { useState, useEffect } from "react"
import { useParams } from "next/navigation"
import { getVetAction } from "@/actions/vetActions"
import VetDetailCard from "@/components/cards/VetDetailCard"
import styles from "./page.module.css"

export default function VetDetailPage() {
  const params = useParams()
  const [vet, setVet] = useState(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState("")

  useEffect(() => {
    const fetchVet = async () => {
      if (!params.id) return

      setLoading(true)
      setError("")

      try {
        const response = await getVetAction(params.id)

        if (response.error) {
          setError(response.error)
          setVet(null)
        } else {
          setVet(response.data)
        }
      } catch (err) {
        setError("Failed to fetch vet details")
        setVet(null)
      } finally {
        setLoading(false)
      }
    }

    fetchVet()
  }, [params.id])

  if (loading) {
    return (
      <div className={styles.container}>
        <div className={styles.loading}>Loading vet details...</div>
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

  if (!vet) {
    return (
      <div className={styles.container}>
        <div className={styles.error}>Vet not found</div>
      </div>
    )
  }

  return (
    <div className={styles.container}>
      <VetDetailCard vet={vet} />
    </div>
  )
}

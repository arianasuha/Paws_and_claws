"use client"

import { useState, useEffect } from "react"
import { getPetMarketsAction } from "@/actions/petMarketActions"
import PetMarketCard from "@/components/cards/pet-market-card"
import Pagination from "@/components/pagination/pagination"
import styles from "./page.module.css"

export default function PetMarketPage() {
  const [petMarkets, setPetMarkets] = useState([])
  const [pagination, setPagination] = useState({})
  const [currentPage, setCurrentPage] = useState(1)
  const [isLoading, setIsLoading] = useState(true)
  const [error, setError] = useState(null)
  const [successMessage, setSuccessMessage] = useState("")

  const fetchPetMarkets = async (page = 1) => {
    setIsLoading(true)
    setError(null)
    setSuccessMessage("")

    const result = await getPetMarketsAction({ page })

    if (result.data) {
      setPetMarkets(result.data)
      setPagination(result.pagination)
      console.log("Pagination:", result.pagination)
      setSuccessMessage("")
    } else if (result.error) {
      setError(result.error.general || "Failed to fetch pet markets.")
    }

    setIsLoading(false)
  }

  useEffect(() => {
    fetchPetMarkets(currentPage)
  }, [currentPage])

  const handlePageChange = (page) => {
    setCurrentPage(page)
  }

  if (isLoading) {
    return (
      <div className={styles["pet-market-page"]}>
        <div className={styles["pet-market-container"]}>
          <div className={styles["loading-message"]}>Loading pet markets...</div>
        </div>
      </div>
    )
  }

  return (
    <div className={styles["pet-market-page"]}>
      <div className={styles["pet-market-container"]}>
        <h1 className={styles["main-title"]}>Pet Market</h1>

        {error && <div className={styles["error-message"]}>{error}</div>}
        {successMessage && <div className={styles["success-message"]}>{successMessage}</div>}

        {petMarkets.length === 0 ? (
          <p className={styles["no-pets-message"]}>No pets available in the market.</p>
        ) : (
          <>
            <div className={styles["pet-market-grid"]}>
              {petMarkets.map((petMarket) => (
                <PetMarketCard key={petMarket.id} petMarket={petMarket} />
              ))}
            </div>

            <Pagination
              currentPage={currentPage}
              totalPages={pagination.total_pages || 1}
              onPageChange={handlePageChange}
            />
          </>
        )}
      </div>
    </div>
  )
}

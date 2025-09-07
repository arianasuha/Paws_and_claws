"use client"

import { useState, useEffect } from "react"
import { getVetsAction } from "@/actions/vetActions"
import VetCard from "@/components/cards/VetCard"
import Pagination from "@/components/pagination/Pagination"
import styles from "./page.module.css"

export default function VetAppointmentPage() {
  const [vets, setVets] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState("")
  const [searchQuery, setSearchQuery] = useState("")
  const [currentPage, setCurrentPage] = useState(1)
  const [pagination, setPagination] = useState(null)

  const fetchVets = async (page = 1, search = "") => {
    setLoading(true)
    setError("")

    try {
      const queryParams = new URLSearchParams()
      if (page > 1) queryParams.append("page", page.toString())
      if (search.trim()) queryParams.append("search", search.trim())

      const response = await getVetsAction(queryParams.toString() ? `?${queryParams.toString()}` : "")

      if (response.error) {
        setError(response.error)
        setVets([])
        setPagination(null)
      } else {
        setVets(response.data || [])
        setPagination(response.pagination)
      }
    } catch (err) {
      setError("Failed to fetch vets")
      setVets([])
      setPagination(null)
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    fetchVets(currentPage, searchQuery)
  }, [currentPage])

  const handleSearch = (e) => {
    e.preventDefault()
    setCurrentPage(1)
    fetchVets(1, searchQuery)
  }

  const handlePageChange = (page) => {
    setCurrentPage(page)
  }

  return (
    <div className={styles.container}>
      <div className={styles.header}>
        <h1 className={styles.title}>Book Vet Appointment</h1>
        <p className={styles.subtitle}>Choose from our qualified veterinarians</p>
      </div>

      <form onSubmit={handleSearch} className={styles.searchForm}>
        <div className={styles.searchContainer}>
          <input
            type="text"
            placeholder="Search by name, email, or username..."
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            className={styles.searchInput}
          />
          <button type="submit" className={styles.searchButton}>
            Search
          </button>
        </div>
      </form>

      {error && <div className={styles.error}>{error}</div>}

      {loading ? (
        <div className={styles.loading}>Loading vets...</div>
      ) : (
        <>
          <div className={styles.vetsGrid}>
            {vets.length > 0 ? (
              vets.map((vet) => <VetCard key={vet.id} vet={vet} />)
            ) : (
              <div className={styles.noResults}>
                {searchQuery ? "No vets found matching your search." : "No vets available."}
              </div>
            )}
          </div>

          {pagination && pagination.total_pages > 1 && (
            <div className={styles.paginationContainer}>
              <Pagination
                currentPage={currentPage}
                totalPages={pagination.total_pages}
                onPageChange={handlePageChange}
              />
            </div>
          )}
        </>
      )}
    </div>
  )
}

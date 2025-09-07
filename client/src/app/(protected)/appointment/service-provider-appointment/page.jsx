"use client"

import { useState, useEffect } from "react"
import { getServiceProvidersAction } from "@/actions/serviceActions"
import ServiceProviderCard from "@/components/cards/ServiceProviderCard"
import Pagination from "@/components/pagination/Pagination"
import styles from "./page.module.css"

export default function ServiceProviderAppointmentPage() {
  const [serviceProviders, setServiceProviders] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState("")
  const [searchQuery, setSearchQuery] = useState("")
  const [currentPage, setCurrentPage] = useState(1)
  const [pagination, setPagination] = useState(null)

  const fetchServiceProviders = async (page = 1, search = "") => {
    setLoading(true)
    setError("")

    try {
      const queryParams = new URLSearchParams()
      if (page > 1) queryParams.append("page", page.toString())
      if (search.trim()) queryParams.append("search", search.trim())

      const response = await getServiceProvidersAction(queryParams.toString() ? `?${queryParams.toString()}` : "")

      if (response.error) {
        setError(response.error)
        setServiceProviders([])
        setPagination(null)
      } else {
        setServiceProviders(response.data || [])
        setPagination(response.pagination)
      }
    } catch (err) {
      setError("Failed to fetch service providers")
      setServiceProviders([])
      setPagination(null)
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    fetchServiceProviders(currentPage, searchQuery)
  }, [currentPage])

  const handleSearch = (e) => {
    e.preventDefault()
    setCurrentPage(1)
    fetchServiceProviders(1, searchQuery)
  }

  const handlePageChange = (page) => {
    setCurrentPage(page)
  }

  return (
    <div className={styles.container}>
      <div className={styles.header}>
        <h1 className={styles.title}>Book Service Provider Appointment</h1>
        <p className={styles.subtitle}>Choose from our qualified service providers</p>
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
        <div className={styles.loading}>Loading service providers...</div>
      ) : (
        <>
          <div className={styles.providersGrid}>
            {serviceProviders.length > 0 ? (
              serviceProviders.map((provider) => <ServiceProviderCard key={provider.id} provider={provider} />)
            ) : (
              <div className={styles.noResults}>
                {searchQuery ? "No service providers found matching your search." : "No service providers available."}
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

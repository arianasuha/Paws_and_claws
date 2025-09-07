"use client"

import { useState, useEffect } from "react"
import { useSearchParams, useRouter } from "next/navigation"
import { getLostPetsAction } from "@/actions/lostPetActions"
import LostPetCard from "@/components/cards/LostPetCard"
import LostPetSidebar from "@/components/sidebars/LostPetSidebar"
import Pagination from "@/components/pagination/Pagination"
import { CreateLostPetButton } from "@/components/buttons/buttons"
import styles from "./page.module.css"

export default function LostPetPage() {
  const [lostPets, setLostPets] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState("")
  const [pagination, setPagination] = useState({})
  const [searchQuery, setSearchQuery] = useState("")
  const [isSidebarOpen, setIsSidebarOpen] = useState(false)

  const router = useRouter()
  const searchParams = useSearchParams()

  const page = Number.parseInt(searchParams.get("page")) || 1
  const status = searchParams.get("status") || ""
  const location = searchParams.get("location") || ""
  const sort_date = searchParams.get("sort_date") || ""

  useEffect(() => {
    setSearchQuery(location)
  }, [location])

  const fetchPetMarkets = async () => {
    setLoading(true)
    setError("")

    const queryParams = {
      page,
      ...(status && { status }),
      ...(location && { location }),
      ...(sort_date && { sort_date }),
    }

    const result = await getLostPetsAction(queryParams)

    if (result.error) {
      setError(result.error)
    } else {
      setLostPets(result.data || [])
      setPagination(result.pagination || {})
    }

    setLoading(false)
  }

  useEffect(() => {
    fetchPetMarkets()
  }, [page, status, location, sort_date])

  const handleSearch = (e) => {
    e.preventDefault()
    const params = new URLSearchParams(searchParams)
    if (searchQuery.trim()) {
      params.set("location", searchQuery.trim())
    } else {
      params.delete("location")
    }
    params.set("page", "1")
    router.push(`?${params.toString()}`)
  }

  const handleFilterChange = (filterType, value) => {
    const params = new URLSearchParams(searchParams)
    if (value) {
      params.set(filterType, value)
    } else {
      params.delete(filterType)
    }
    params.set("page", "1")
    router.push(`?${params.toString()}`)
  }

  const handlePageChange = (newPage) => {
    const params = new URLSearchParams(searchParams)
    params.set("page", newPage.toString())
    router.push(`?${params.toString()}`)
  }

  const toggleSidebar = () => {
    setIsSidebarOpen(!isSidebarOpen)
  }

  return (
    <div className={styles.container}>
      <div className={styles.header}>
        <div className={styles.titleSection}>
          <button className={styles.menuButton} onClick={toggleSidebar}>
            <img src="/menu-black.svg" alt="Menu" />
          </button>
          <h1 className={styles.title}>Lost Pets</h1>
        </div>
        <CreateLostPetButton />
      </div>

      <div className={styles.filters}>
        <form onSubmit={handleSearch} className={styles.searchForm}>
          <input
            type="text"
            placeholder="Search by location"
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            className={styles.searchInput}
          />
          <button type="submit" className={styles.searchButton}>
            Search
          </button>
        </form>
      </div>

      <div className={styles.content}>
        <LostPetSidebar
          isOpen={isSidebarOpen}
          onClose={() => setIsSidebarOpen(false)}
          currentFilters={{
            status,
            location,
            sort_date,
          }}
          onFilterChange={handleFilterChange}
        />

        <div className={styles.mainContent}>
          {error && <div className={styles.error}>{error}</div>}

          {loading ? (
            <div className={styles.loading}>Loading lost pets...</div>
          ) : (
            <>
              <div className={styles.results}>
                <p className={styles.resultsCount}>{pagination.count || 0} pets found</p>
              </div>

              <div className={styles.cardsGrid}>
                {lostPets.length > 0 ? (
                  lostPets.map((lostPet) => <LostPetCard key={lostPet.id} lostPet={lostPet} />)
                ) : (
                  <div className={styles.noResults}>No lost pets found matching your criteria.</div>
                )}
              </div>

              {pagination.total_pages > 1 && (
                <Pagination
                  currentPage={page}
                  totalPages={pagination.total_pages}
                  onPageChange={handlePageChange}
                  totalItems={pagination.count}
                />
              )}
            </>
          )}
        </div>
      </div>
    </div>
  )
}

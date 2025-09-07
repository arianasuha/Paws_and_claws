"use client"

import { useState, useEffect } from "react"
import { useSearchParams, useRouter } from "next/navigation"
import { getPetMarketsAction } from "@/actions/petMarketActions"
import PetMarketCard from "@/components/cards/PetMarketCard"
import PetMarketSidebar from "@/components/sidebars/PetMarketSidebar"
import Pagination from "@/components/pagination/Pagination"
import {CreatePetMarketButton} from "@/components/buttons/buttons"
import styles from "./page.module.css"

export default function PetMarketPage() {
  const [petMarkets, setPetMarkets] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState("")
  const [pagination, setPagination] = useState({})
  const [searchQuery, setSearchQuery] = useState("")
  const [isSidebarOpen, setIsSidebarOpen] = useState(false)

  const router = useRouter()
  const searchParams = useSearchParams()

  const type = searchParams.get("type") || "sale"
  const page = Number.parseInt(searchParams.get("page")) || 1
  const gender = searchParams.get("gender") || ""
  const status = searchParams.get("status") || ""
  const sortBy = searchParams.get("sortBy") || ""
  const sortDirection = searchParams.get("sortDirection") || ""
  const search = searchParams.get("search") || ""

  useEffect(() => {
    setSearchQuery(search)
  }, [search])

  const fetchPetMarkets = async () => {
    setLoading(true)
    setError("")

    const queryParams = {
      type,
      page,
      ...(gender && { gender }),
      ...(status && { status }),
      ...(sortBy && { sortBy }),
      ...(sortDirection && { sortDirection }),
      ...(search && { search }),
    }

    const result = await getPetMarketsAction(queryParams)

    if (result.error) {
      setError(result.error)
    } else {
      setPetMarkets(result.data || [])
      setPagination(result.pagination || {})
    }

    setLoading(false)
  }

  useEffect(() => {
    fetchPetMarkets()
  }, [type, page, gender, status, sortBy, sortDirection, search])

  const handleSearch = (e) => {
    e.preventDefault()
    const params = new URLSearchParams(searchParams)
    if (searchQuery.trim()) {
      params.set("search", searchQuery.trim())
    } else {
      params.delete("search")
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
          <h1 className={styles.title}>Pet {type === "sale" ? "Sales" : "Adoptions"}</h1>
        </div>
        <CreatePetMarketButton />
      </div>

      <div className={styles.filters}>
        <form onSubmit={handleSearch} className={styles.searchForm}>
          <input
            type="text"
            placeholder="Search by name, species, or breed..."
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
        <PetMarketSidebar
          isOpen={isSidebarOpen}
          onClose={() => setIsSidebarOpen(false)}
          currentFilters={{
            gender,
            status,
            sortBy,
            sortDirection,
          }}
          onFilterChange={handleFilterChange}
        />

        <div className={styles.mainContent}>
          {error && <div className={styles.error}>{error}</div>}

          {loading ? (
            <div className={styles.loading}>Loading pet markets...</div>
          ) : (
            <>
              <div className={styles.results}>
                <p className={styles.resultsCount}>{pagination.count || 0} pets found</p>
              </div>

              <div className={styles.cardsGrid}>
                {petMarkets.length > 0 ? (
                  petMarkets.map((petMarket) => <PetMarketCard key={petMarket.id} petMarket={petMarket} />)
                ) : (
                  <div className={styles.noResults}>No pets found matching your criteria.</div>
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

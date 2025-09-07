"use client"

import { useState, useEffect } from "react"
import { useRouter, useSearchParams } from "next/navigation"
import { getPetsAction } from "@/actions/petActions"
import PetCard from "@/components/cards/PetCard"
import Pagination from "@/components/pagination/Pagination"

import styles from "./page.module.css"
import { CreatePetMedicalButton } from "@/components/buttons/buttons"
import PetMedicalSidebar from "@/components/sidebars/PetMedicalSidebar"

export default function PetMedicalLogPage() {
  const [pets, setPets] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState("")
  const [pagination, setPagination] = useState({})
  const [searchQuery, setSearchQuery] = useState("")
  const [isSidebarOpen, setIsSidebarOpen] = useState(false)

  const router = useRouter()
  const searchParams = useSearchParams()

  const page = Number.parseInt(searchParams.get("page")) || 1
  const gender = searchParams.get("gender") || ""
  const my_pets = searchParams.get("my_pets") || ""
  const search = searchParams.get("search") || ""

  useEffect(() => {
    setSearchQuery(search)
  }, [search])

  const fetchPetMarkets = async () => {
    setLoading(true)
    setError("")

    const queryParams = {
      page,
      ...(gender && { gender }),
      ...(my_pets && { my_pets }),
      ...(search && { search }),
    }

    const result = await getPetsAction(queryParams)

    if (result.error) {
      setError(result.error)
    } else {
      setPets(result.data || [])
      setPagination(result.pagination || {})
    }

    setLoading(false)
  }

  useEffect(() => {
    fetchPetMarkets()
  }, [page, gender, my_pets, search])

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

  const handlePetClick = (petId) => {
    router.push(`/pet-medical-log/${petId}`)
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
          <h1 className={styles.title}>Pet and Medical Logs</h1>
        </div>
        <CreatePetMedicalButton />
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
        <PetMedicalSidebar
          isOpen={isSidebarOpen}
          onClose={() => setIsSidebarOpen(false)}
          currentFilters={{
            gender,
            my_pets,
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
                {pets.length > 0 ? (
                  pets.map((pet) => (
                    <PetCard key={pet.id} pet={pet} onClick={handlePetClick} />
                  ))
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

"use client"

import { useState, useEffect } from "react"
import { useRouter } from "next/navigation"
import { getPetsAction } from "@/actions/petActions"
import PetCard from "@/components/cards/PetCard"
import Pagination from "@/components/pagination/Pagination"
import {CreatePetButton} from "@/components/buttons/buttons"
import styles from "./page.module.css"

export default function PetsPage() {
  const [pets, setPets] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState("")
  const [searchTerm, setSearchTerm] = useState("")
  const [genderFilter, setGenderFilter] = useState("")
  const [currentPage, setCurrentPage] = useState(1)
  const [pagination, setPagination] = useState(null)
  const router = useRouter()

  const fetchPets = async (page = 1, search = "", gender = "") => {
    setLoading(true)
    setError("")

    const queryParams = {
      my_pets: "1",
      page: page.toString(),
      ...(search && { search }),
      ...(gender && { gender }),
    }

    try {
      const result = await getPetsAction(queryParams)

      if (result.error) {
        setError(result.error)
        setPets([])
      } else {
        setPets(result.data || [])
        setPagination(result.pagination)
      }
    } catch (err) {
      setError("Failed to fetch pets")
      setPets([])
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    fetchPets(currentPage, searchTerm, genderFilter)
  }, [currentPage, searchTerm, genderFilter])

  const handleSearch = (e) => {
    e.preventDefault()
    setCurrentPage(1)
    fetchPets(1, searchTerm, genderFilter)
  }

  const handleGenderChange = (e) => {
    setGenderFilter(e.target.value)
    setCurrentPage(1)
  }

  const handlePetClick = (petId) => {
    router.push(`/pet/${petId}`)
  }

  const handlePageChange = (page) => {
    setCurrentPage(page)
  }

  return (
    <div className={styles.container}>
      <div className={styles.header}>
        <h1 className={styles.title}>My Pets</h1>
        <CreatePetButton onSuccess={fetchPets} />
      </div>

      <div className={styles.filters}>
        <form onSubmit={handleSearch} className={styles.searchForm}>
          <input
            type="text"
            placeholder="Search by name, species, or breed..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className={styles.searchInput}
          />
          <button type="submit" className={styles.searchButton}>
            Search
          </button>
        </form>

        <select value={genderFilter} onChange={handleGenderChange} className={styles.genderFilter}>
          <option value="">All Genders</option>
          <option value="male">Male</option>
          <option value="female">Female</option>
        </select>
      </div>

      {error && <div className={styles.error}>{typeof error === "object" ? JSON.stringify(error) : error}</div>}

      {loading ? (
        <div className={styles.loading}>Loading pets...</div>
      ) : (
        <>
          {pets.length === 0 ? (
            <div className={styles.noPets}>
              <p>No pets found. Add your first pet to get started!</p>
            </div>
          ) : (
            <div className={styles.petsGrid}>
              {pets.map((pet) => (
                <PetCard key={pet.id} pet={pet} onClick={handlePetClick} />
              ))}
            </div>
          )}

          {pagination && pagination.total_pages > 1 && (
            <Pagination currentPage={currentPage} totalPages={pagination.total_pages} onPageChange={handlePageChange} />
          )}
        </>
      )}
    </div>
  )
}

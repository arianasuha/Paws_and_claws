"use client"

import { useState, useEffect } from "react"
import { useRouter } from "next/navigation"
import { getPetsAction } from "@/actions/petActions"
import { createShelterAction } from "@/actions/emergencyShelterActions"
import PetCard from "@/components/cards/PetCard"
import Pagination from "@/components/pagination/Pagination"
import styles from "./page.module.css"

export default function NewPetMarketPage() {
  const [pets, setPets] = useState([])
  const router = useRouter()
  const [loading, setLoading] = useState(true)
  const [success, setSuccess] = useState("")
  const [error, setError] = useState("")
  const [searchTerm, setSearchTerm] = useState("")
  const [genderFilter, setGenderFilter] = useState("")
  const [currentPage, setCurrentPage] = useState(1)
  const [pagination, setPagination] = useState(null)

  const fetchPets = async (page = 1, search = "", gender = "") => {
    setLoading(true)
    setSuccess("")
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

  const handleBack = () => {
    router.push("/")
  }

  const handleSearch = (e) => {
    e.preventDefault()
    setCurrentPage(1)
    fetchPets(1, searchTerm, genderFilter)
  }

  const handleGenderChange = (e) => {
    setGenderFilter(e.target.value)
    setCurrentPage(1)
  }

  const handleShelter = async (petId) => {
    setSuccess("")
    setError("")
    
    try {
      const formData = new FormData()
      formData.append("pet", petId)
      formData.append("request_date", new Date().toISOString())

      const result = await createShelterAction(formData)

      if (result.error) {
        setError(result.error.error)
      } else {
        setSuccess(result.success)
      }
    } catch (err) {
      setError("Failed to request for shelter")
    }
  }

  const handlePageChange = (page) => {
    setCurrentPage(page)
  }

  return (
    <div className={styles.container}>
      <div className={styles.header}>
        <button className={styles.backButton} onClick={handleBack}>
          ← Back to Home
        </button>
      </div>
      <div className={styles.header}>
        <h1 className={styles.title}>Emergency Shelter</h1>
        <p className={styles.subtitle}>Choose the pet you want to keep in emergency shelter</p>
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

      {success && <div className={styles.success}>{success}</div>}
      {error && <div className={styles.error}>{error}</div>}

      {loading ? (
        <div className={styles.loading}>Loading your pets...</div>
      ) : (
        <>
          <div className={styles.petsGrid}>
            {pets.length > 0 ? (
              pets.map((pet) => (
                <div key={pet.id} onClick={() => handleShelter(pet.id)}>
                  <PetCard pet={pet} />
                </div>
              ))
            ) : (
              <div className={styles.noPets}>
                <p>You don't have any pets yet.</p>
                <p>Please add a pet first before requesting for shelter.</p>
              </div>
            )}
          </div>

          {pagination && pagination.total_pages > 1 && (
            <Pagination currentPage={currentPage} totalPages={pagination.total_pages} onPageChange={handlePageChange} />
          )}
        </>
      )}
    </div>
  )
}

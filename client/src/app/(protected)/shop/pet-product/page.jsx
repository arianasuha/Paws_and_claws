"use client"

import { useState, useEffect } from "react"
import { useRouter, useSearchParams } from "next/navigation"
import { getPetProductsAction } from "@/actions/petProductActions"
import PetProductCard from "@/components/cards/PetProductCard"
import PetProductSidebar from "@/components/sidebars/PetProductSidebar"
import Pagination from "@/components/pagination/Pagination"
import {CreatePetProductButton} from "@/components/buttons/buttons"
import styles from "./page.module.css"

export default function PetProductPage() {
  const [products, setProducts] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState("")
  const [pagination, setPagination] = useState({})
  const [searchQuery, setSearchQuery] = useState("")
  const [isSidebarOpen, setIsSidebarOpen] = useState(false)

  const router = useRouter()
  const searchParams = useSearchParams()

  const page = Number.parseInt(searchParams.get("page")) || 1
  const category = searchParams.get("category")
  const sort_by_price = searchParams.get("sort_by_price")
  const search = searchParams.get("search")

  useEffect(() => {
    setSearchQuery(search)
  }, [search])

  const fetchProducts = async () => {
    setLoading(true)
    setError("")

    const queryParams = {
      page,
      ...(category && { category }),
      ...(sort_by_price && { sort_by_price }),
      ...(search && { search }),
    }

    try {
      const result = await getPetProductsAction(queryParams)

      if (result.error) {
        setError(result.error)
        setProducts([])
        setPagination({})
      } else {
        setProducts(result.data || [])
        setPagination(result.pagination || {})
      }
    } catch (err) {
      setError("Failed to fetch pet products")
      setProducts([])
      setPagination({})
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    fetchProducts()
  }, [page, category, sort_by_price, search])

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
          <h1 className={styles.title}>Pet Products</h1>
        </div>
        <CreatePetProductButton onSuccess={fetchProducts}/>
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
        <PetProductSidebar
          isOpen={isSidebarOpen}
          onClose={() => setIsSidebarOpen(false)}
          onFilterChange={handleFilterChange}
          currentFilters={{
            category,
            sort_by_price,
          }}
        />

        <div className={styles.mainContent}>
          {error && <div className={styles.error}>{typeof error === "object" ? JSON.stringify(error) : error}</div>}

          {loading ? (
            <div className={styles.loading}>Loading pet products...</div>
          ) : products.length === 0 ? (
            <div className={styles.noProducts}>No pet products found. Try adjusting your search or filters.</div>
          ) : (
            <>
              <div className={styles.productsGrid}>
                {products.map((product) => (
                  <PetProductCard key={product.id} product={product} />
                ))}
              </div>

              {pagination.total_pages > 1 && (
                <Pagination
                  currentPage={Number.parseInt(searchParams.get("page")) || 1}
                  totalPages={pagination.total_pages}
                  onPageChange={handlePageChange}
                />
              )}
            </>
          )}
        </div>
      </div>
    </div>
  )
}

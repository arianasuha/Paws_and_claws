"use client"

import { useState, useEffect } from "react"
import { getCategoriesAction } from "@/actions/categoryActions"
import styles from "./PetProductSidebar.module.css"

export default function PetProductSidebar({ isOpen, onClose, onFilterChange, currentFilters }) {
  const [categories, setCategories] = useState([])

  const fetchCategories = async () => {
    setCategories([])

    try {
      const result = await getCategoriesAction()

      if (result.error) {
        console.error("Error fetching categories:", result.error)
      } else {
        setCategories(result.data)
      }
    } catch (error) {
      console.error("Error fetching categories:", error)
    }
  }

  useEffect(() => {
    fetchCategories()
  }, [])

  const handleFilterChange = (filterType, value) => {
    onFilterChange(filterType, value)
  }

  return (
    <>
      {isOpen && <div className={styles.overlay} onClick={onClose}></div>}

      <div className={`${styles.sidebar} ${isOpen ? styles.open : ""}`}>
        <div className={styles.header}>
          <h3 className={styles.title}>Filters</h3>
          <button className={styles.closeButton} onClick={onClose}>
            ×
          </button>
        </div>

        <div className={styles.content}>
          <div className={styles.filterGroup}>
            <label className={styles.filterLabel}>Category</label>
            <select onChange={(e) => handleFilterChange("category", (e.target.value))} className={styles.select}>
              <option value="">All Categories</option>
              {categories.map((cat) => (
                <option key={cat.id} value={cat.name}>
                  {cat.name}
                </option>
              ))}
            </select>
          </div>

          <div className={styles.filterGroup}>
            <h4 className={styles.filterTitle}>Sort By Price</h4>
            <div className={styles.filterOptions}>
              <label className={styles.filterOption}>
                <input
                  type="radio"
                  name="sort_by_price"
                  value=""
                  checked={!currentFilters.sort_by_price}
                  onChange={(e) => handleFilterChange("sort_by_price", e.target.value)}
                />
                <span>Default</span>
              </label>
              <label className={styles.filterOption}>
                <input
                  type="radio"
                  name="sort_by_price"
                  value="asc"
                  checked={currentFilters.sort_by_price === "asc"}
                  onChange={(e) => handleFilterChange("sort_by_price", e.target.value)}
                />
                <span>Low to High</span>
              </label>
              <label className={styles.filterOption}>
                <input
                  type="radio"
                  name="sort_by_price"
                  value="desc"
                  checked={currentFilters.sort_by_price === "desc"}
                  onChange={(e) => handleFilterChange("sort_by_price", e.target.value)}
                />
                <span>High to Low</span>
              </label>
            </div>
          </div>
        </div>
      </div>
    </>
  )
}

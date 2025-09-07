"use client"

import styles from "./LostPetSidebar.module.css"

export default function LostPetSidebar({ isOpen, onClose, currentFilters, onFilterChange}) {
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
            <h4 className={styles.filterTitle}>Status</h4>
            <div className={styles.filterOptions}>
              <label className={styles.filterOption}>
                <input
                  type="radio"
                  name="status"
                  value=""
                  checked={currentFilters.status === ""}
                  onChange={(e) => handleFilterChange("status", e.target.value)}
                />
                <span>All</span>
              </label>
              <label className={styles.filterOption}>
                <input
                  type="radio"
                  name="status"
                  value="missing"
                  checked={currentFilters.status === "missing"}
                  onChange={(e) => handleFilterChange("status", e.target.value)}
                />
                <span>Missing</span>
              </label>
              <label className={styles.filterOption}>
                <input
                  type="radio"
                  name="status"
                  value="found"
                  checked={currentFilters.status === "found"}
                  onChange={(e) => handleFilterChange("status", e.target.value)}
                />
                <span>Found</span>
              </label>
            </div>
          </div>

          <div className={styles.filterGroup}>
            <h4 className={styles.filterTitle}>Sort By Date</h4>
            <div className={styles.filterOptions}>
              <label className={styles.filterOption}>
                <input
                  type="radio"
                  name="sort_date"
                  value=""
                  checked={currentFilters.sort_date === ""}
                  onChange={(e) => handleFilterChange("sort_date", e.target.value)}
                />
                <span>Default</span>
              </label>
              <label className={styles.filterOption}>
                <input
                  type="radio"
                  name="sort_date"
                  value="asc"
                  checked={currentFilters.sort_date === "asc"}
                  onChange={(e) => handleFilterChange("sort_date", e.target.value)}
                />
                <span>Low to High</span>
              </label>
              <label className={styles.filterOption}>
                <input
                  type="radio"
                  name="sort_date"
                  value="desc"
                  checked={currentFilters.sort_date === "desc"}
                  onChange={(e) => handleFilterChange("sort_date", e.target.value)}
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

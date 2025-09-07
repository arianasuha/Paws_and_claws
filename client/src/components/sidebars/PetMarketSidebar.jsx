"use client"

import styles from "./PetMarketSidebar.module.css"

export default function PetMarketSidebar({ isOpen, onClose, currentFilters, onFilterChange}) {
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
            <h4 className={styles.filterTitle}>Gender</h4>
            <div className={styles.filterOptions}>
              <label className={styles.filterOption}>
                <input
                  type="radio"
                  name="gender"
                  value=""
                  checked={currentFilters.gender === ""}
                  onChange={(e) => handleFilterChange("gender", e.target.value)}
                />
                <span>All</span>
              </label>
              <label className={styles.filterOption}>
                <input
                  type="radio"
                  name="gender"
                  value="male"
                  checked={currentFilters.gender === "male"}
                  onChange={(e) => handleFilterChange("gender", e.target.value)}
                />
                <span>Male</span>
              </label>
              <label className={styles.filterOption}>
                <input
                  type="radio"
                  name="gender"
                  value="female"
                  checked={currentFilters.gender === "female"}
                  onChange={(e) => handleFilterChange("gender", e.target.value)}
                />
                <span>Female</span>
              </label>
            </div>
          </div>

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
                  value="available"
                  checked={currentFilters.status === "available"}
                  onChange={(e) => handleFilterChange("status", e.target.value)}
                />
                <span>Available</span>
              </label>
              <label className={styles.filterOption}>
                <input
                  type="radio"
                  name="status"
                  value="adopted"
                  checked={currentFilters.status === "adopted"}
                  onChange={(e) => handleFilterChange("status", e.target.value)}
                />
                <span>Adopted</span>
              </label>
              <label className={styles.filterOption}>
                <input
                  type="radio"
                  name="status"
                  value="sold"
                  checked={currentFilters.status === "sold"}
                  onChange={(e) => handleFilterChange("status", e.target.value)}
                />
                <span>Sold</span>
              </label>
            </div>
          </div>

          <div className={styles.filterGroup}>
            <h4 className={styles.filterTitle}>Sort By</h4>
            <div className={styles.filterOptions}>
              <label className={styles.filterOption}>
                <input
                  type="radio"
                  name="sortBy"
                  value=""
                  checked={currentFilters.sortBy === ""}
                  onChange={(e) => handleFilterChange("sortBy", e.target.value)}
                />
                <span>Default</span>
              </label>
              <label className={styles.filterOption}>
                <input
                  type="radio"
                  name="sortBy"
                  value="fee"
                  checked={currentFilters.sortBy === "fee"}
                  onChange={(e) => handleFilterChange("sortBy", e.target.value)}
                />
                <span>Price</span>
              </label>
              <label className={styles.filterOption}>
                <input
                  type="radio"
                  name="sortBy"
                  value="date"
                  checked={currentFilters.sortBy === "date"}
                  onChange={(e) => handleFilterChange("sortBy", e.target.value)}
                />
                <span>Date</span>
              </label>
            </div>
          </div>

          <div className={styles.filterGroup}>
            <h4 className={styles.filterTitle}>Sort Direction</h4>
            <div className={styles.filterOptions}>
              <label className={styles.filterOption}>
                <input
                  type="radio"
                  name="sortDirection"
                  value=""
                  checked={currentFilters.sortDirection === ""}
                  onChange={(e) => handleFilterChange("sortDirection", e.target.value)}
                />
                <span>Default</span>
              </label>
              <label className={styles.filterOption}>
                <input
                  type="radio"
                  name="sortDirection"
                  value="asc"
                  checked={currentFilters.sortDirection === "asc"}
                  onChange={(e) => handleFilterChange("sortDirection", e.target.value)}
                />
                <span>Low to High</span>
              </label>
              <label className={styles.filterOption}>
                <input
                  type="radio"
                  name="sortDirection"
                  value="desc"
                  checked={currentFilters.sortDirection === "desc"}
                  onChange={(e) => handleFilterChange("sortDirection", e.target.value)}
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

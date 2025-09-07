"use client"

import styles from "./PetMedicalSidebar.module.css"

export default function PetMedicalSidebar({ isOpen, onClose, currentFilters, onFilterChange}) {
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
            <h4 className={styles.filterTitle}>Pets</h4>
            <div className={styles.filterOptions}>
              <label className={styles.filterOption}>
                <input
                  type="radio"
                  name="my_pets"
                  value=""
                  checked={currentFilters.my_pets === ""}
                  onChange={(e) => handleFilterChange("my_pets", e.target.value)}
                />
                <span>All</span>
              </label>
              <label className={styles.filterOption}>
                <input
                  type="radio"
                  name="my_pets"
                  value="1"
                  checked={currentFilters.my_pets === "1"}
                  onChange={(e) => handleFilterChange("my_pets", e.target.value)}
                />
                <span>My Pets</span>
              </label>
            </div>
          </div>          
        </div>
      </div>
    </>
  )
}

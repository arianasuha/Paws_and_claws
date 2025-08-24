"use client"

import styles from "./pagination.module.css"

export default function Pagination({ currentPage, totalPages, onPageChange }) {
  const handlePageClick = (page) => {
    if (page !== currentPage && page >= 1 && page <= totalPages) {
      onPageChange(page)
    }
  }

  const renderPageNumbers = () => {
    const pages = []
    const maxVisiblePages = 5
    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2))
    const endPage = Math.min(totalPages, startPage + maxVisiblePages - 1)

    if (endPage - startPage + 1 < maxVisiblePages) {
      startPage = Math.max(1, endPage - maxVisiblePages + 1)
    }

    for (let i = startPage; i <= endPage; i++) {
      pages.push(
        <button
          key={i}
          onClick={() => handlePageClick(i)}
          className={`${styles["page-btn"]} ${i === currentPage ? styles["active"] : ""}`}
        >
          {i}
        </button>,
      )
    }

    return pages
  }

  if (totalPages <= 1) return null

  return (
    <div className={styles["pagination-container"]}>
      <button
        onClick={() => handlePageClick(currentPage - 1)}
        disabled={currentPage === 1}
        className={`${styles["nav-btn"]} ${currentPage === 1 ? styles["disabled"] : ""}`}
      >
        Previous
      </button>

      {renderPageNumbers()}

      <button
        onClick={() => handlePageClick(currentPage + 1)}
        disabled={currentPage === totalPages}
        className={`${styles["nav-btn"]} ${currentPage === totalPages ? styles["disabled"] : ""}`}
      >
        Next
      </button>
    </div>
  )
}

"use client"

import { useState, useEffect } from "react"
import { useRouter } from "next/navigation"
import { getSheltersAction, deleteShelterAction } from "@/actions/emergencyShelterActions"
import EmergencyCard from "@/components/cards/EmergencyCard"
import Pagination from "@/components/pagination/Pagination"
import styles from "./page.module.css"

export default function EmergencyPage() {
  const [emergencyRequests, setEmergencyRequests] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState(null)
  const [success, setSuccess] = useState(null)
  const [currentPage, setCurrentPage] = useState(1)
  const [totalPages, setTotalPages] = useState(1)
  const [showDeleteModal, setShowDeleteModal] = useState(false)
  const [requestToDelete, setRequestToDelete] = useState(null)
  const [deleting, setDeleting] = useState(false)
  const router = useRouter()

  const loadEmergencyRequests = async (page = 1) => {
    setLoading(true)
    setError(null)

    try {
      const result = await getSheltersAction({ page })

      if (result.error) {
        setError(result.error)
      } else {
        setEmergencyRequests(result.data.data || [])
        setCurrentPage(result.data.current_page || 1)
        setTotalPages(result.data.last_page || 1)
      }
    } catch (err) {
      setError("Failed to load emergency requests")
    } finally {
      setLoading(false)
    }
  }

  const handleDeleteClick = (request) => {
    setRequestToDelete(request)
    setShowDeleteModal(true)
  }

  const handleDeleteConfirm = async () => {
    if (!requestToDelete) return

    setDeleting(true)
    setError(null)
    setSuccess(null)

    try {
      const result = await deleteShelterAction(requestToDelete.id)

      if (result.error) {
        setError(result.error)
      } else {
        setSuccess("Emergency request deleted successfully")
        setShowDeleteModal(false)
        setRequestToDelete(null)
        // Reload current page
        loadEmergencyRequests(currentPage)
      }
    } catch (err) {
      setError("Failed to delete emergency request")
    } finally {
      setDeleting(false)
    }
  }

  const handleDeleteCancel = () => {
    setShowDeleteModal(false)
    setRequestToDelete(null)
  }

  const handlePageChange = (page) => {
    setCurrentPage(page)
    loadEmergencyRequests(page)
  }

  const handleCardClick = (requestId) => {
    router.push(`/admin-dashboard/emergency/${requestId}`)
  }

  useEffect(() => {
    loadEmergencyRequests()
  }, [])

  return (
    <div className={styles.container}>
      <main className={styles.main}>
        <div className={styles.header}>
          <button onClick={() => router.back()} className={styles.backButton}>
            ← Back
          </button>
          <h1 className={styles.title}>Emergency Shelter Requests</h1>
          <p className={styles.subtitle}>Monitor and manage emergency pet shelter requests</p>
        </div>

        {error && <div className={styles.error}>{typeof error === "object" ? JSON.stringify(error) : error}</div>}

        {success && <div className={styles.success}>{success}</div>}

        {loading ? (
          <div className={styles.loading}>Loading emergency requests...</div>
        ) : (
          <>
            <div className={styles.requestGrid}>
              {emergencyRequests.length > 0 ? (
                emergencyRequests.map((request) => (
                  <EmergencyCard
                    key={request.id}
                    request={request}
                    onClick={() => handleCardClick(request.id)}
                    onDelete={() => handleDeleteClick(request)}
                  />
                ))
              ) : (
                <div className={styles.noResults}>No emergency requests found</div>
              )}
            </div>

            {totalPages > 1 && (
              <div className={styles.paginationContainer}>
                <Pagination currentPage={currentPage} totalPages={totalPages} onPageChange={handlePageChange} />
              </div>
            )}
          </>
        )}
      </main>

      {showDeleteModal && (
        <div className={styles.modalOverlay}>
          <div className={styles.modal}>
            <h3 className={styles.modalTitle}>Delete Emergency Request</h3>
            <p className={styles.modalMessage}>
              Are you sure you want to delete this emergency request for {requestToDelete?.pet?.name}? This action
              cannot be undone.
            </p>
            <div className={styles.modalButtons}>
              <button className={styles.cancelButton} onClick={handleDeleteCancel} disabled={deleting}>
                No
              </button>
              <button className={styles.confirmButton} onClick={handleDeleteConfirm} disabled={deleting}>
                {deleting ? "Deleting..." : "Yes"}
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  )
}

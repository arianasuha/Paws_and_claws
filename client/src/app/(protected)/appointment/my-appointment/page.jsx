"use client"

import { useState, useEffect } from "react"
import { getAppointmentsAction } from "@/actions/appointmentActions"
import MyAppointmentCard from "@/components/cards/MyAppointmentCard"
import Pagination from "@/components/pagination/Pagination"
import styles from "./page.module.css"

export default function MyAppointmentPage() {
  const [appointments, setAppointments] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState("")
  const [currentPage, setCurrentPage] = useState(1)
  const [totalPages, setTotalPages] = useState(1)
  const [totalItems, setTotalItems] = useState(0)

  const fetchAppointments = async (page = 1) => {
    setLoading(true)
    setError("")

    try {
      const result = await getAppointmentsAction({ page })

      if (result.error) {
        setError(typeof result.error === "string" ? result.error : "Failed to fetch appointments")
      } else {
        setAppointments(result.data || [])
        setTotalPages(result.pagination?.total_pages || 1)
        setTotalItems(result.pagination?.count || 0)
      }
    } catch (err) {
      setError("An unexpected error occurred")
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    fetchAppointments(currentPage)
  }, [currentPage])

  const handlePageChange = (page) => {
    setCurrentPage(page)
  }

  if (loading) {
    return (
      <div className={styles.container}>
        <div className={styles.loading}>Loading appointments...</div>
      </div>
    )
  }

  return (
    <div className={styles.container}>
      <div className={styles.header}>
        <h1 className={styles.title}>My Appointments</h1>
        <p className={styles.subtitle}>View and manage all your scheduled appointments</p>
      </div>

      {error && <div className={styles.error}>{error}</div>}

      {appointments.length === 0 && !error ? (
        <div className={styles.noAppointments}>
          <p>No appointments found.</p>
        </div>
      ) : (
        <>
          <div className={styles.appointmentsGrid}>
            {appointments.map((appointment) => (
              <MyAppointmentCard key={appointment.id} appointment={appointment} />
            ))}
          </div>

          <Pagination
            currentPage={currentPage}
            totalPages={totalPages}
            onPageChange={handlePageChange}
            totalItems={totalItems}
            itemsPerPage={10}
          />
        </>
      )}
    </div>
  )
}

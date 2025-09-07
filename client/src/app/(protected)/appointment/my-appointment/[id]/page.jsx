"use client"

import { useState, useEffect } from "react"
import { useRouter, useParams } from "next/navigation"
import {
  getAppointmentAction,
  updateAppointmentAction,
  deleteAppointmentAction,
} from "@/actions/appointmentActions"
import { 
  getUserIdAction,
  getUserRoleAction
} from "@/actions/authActions"
import AppointmentDetailCard from "@/components/cards/AppointmentDetailCard"
import {UpdateAppointmentButton, DeleteAppointmentButton} from "@/components/buttons/buttons"
import UpdateAppointmentModal from "@/components/modals/UpdateAppointmentModal"
import DeleteAppointmentModal from "@/components/modals/DeleteAppointmentModal"
import styles from "./page.module.css"

export default function AppointmentDetailPage() {
  const router = useRouter()
  const params = useParams()
  const [appointment, setAppointment] = useState(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState("")
  const [currentUserId, setCurrentUserId] = useState(null)
  const [currentUserRole, setCurrentUserRole] = useState(null)
  const [showUpdateModal, setShowUpdateModal] = useState(false)
  const [showDeleteModal, setShowDeleteModal] = useState(false)
  const [updateError, setUpdateError] = useState("")
  const [updateSuccess, setUpdateSuccess] = useState("")
  const [deleteError, setDeleteError] = useState("")
  const [deleteSuccess, setDeleteSuccess] = useState("")

  const fetchData = async () => {
    setLoading(true)
    setError("")

    try {
      // Fetch appointment details
      const appointmentResult = await getAppointmentAction(params.id)

      if (appointmentResult.error) {
        setError(
          typeof appointmentResult.error === "string" ? appointmentResult.error : "Failed to fetch appointment",
        )
        return
      }

      setAppointment(appointmentResult.data)

      // Fetch current user info
      const userId = await getUserIdAction()
      const userRole = await getUserRoleAction()

      setCurrentUserId(userId)
      setCurrentUserRole(userRole)
    } catch (err) {
      setError("An unexpected error occurred")
    } finally {
      setLoading(false)
    }
  }
  
  useEffect(() => {
    fetchData()
  }, [params.id])

  const handleUpdateSubmit = async (formData) => {
    setUpdateError("")
    setUpdateSuccess("")

    try {
      const result = await updateAppointmentAction(params.id, formData)
      if (result.error) {
        setUpdateError(result.error.error)
        setShowUpdateModal(false)
      } else {
        setUpdateSuccess("Appointment updated successfully")
        setShowUpdateModal(false)
        // Refresh appointment data
        fetchData()
      }
    } catch (err) {
      setUpdateError("An unexpected error occurred")
    }
  }

  const handleDelete = async () => {
    setDeleteError("")
    setDeleteSuccess("")

    try {
      const result = await deleteAppointmentAction(params.id)
      
      if (result.error) {
        setDeleteError(typeof result.error === "string" ? result.error : "Failed to delete appointment")
      } else {
        setDeleteSuccess("Appointment deleted successfully")
        setShowDeleteModal(false)
        setTimeout(() => {
          router.push("/appointment")
        }, 2000)
      }
    } catch (err) {
      setDeleteError("An unexpected error occurred")
    }
  }

  const canUpdate =
    appointment && currentUserId && (currentUserId == appointment.user_id || currentUserId == appointment.provider_id)

  const canDelete = currentUserRole === "admin"

  if (loading) {
    return (
      <div className={styles.container}>
        <div className={styles.loading}>Loading appointment details...</div>
      </div>
    )
  }

  if (error) {
    return (
      <div className={styles.container}>
        <div className={styles.error}>{error}</div>
      </div>
    )
  }

  return (
    <div className={styles.container}>
      <div className={styles.header}>
        <h1 className={styles.title}>Appointment Details</h1>
      </div>

      {(updateSuccess || deleteSuccess) && <div className={styles.success}>{updateSuccess || deleteSuccess}</div>}

      {(updateError || deleteError) && <div className={styles.error}>{updateError || deleteError}</div>}

      {appointment && (
        <>
          <AppointmentDetailCard appointment={appointment} />

          <div className={styles.actions}>
            {canUpdate && <UpdateAppointmentButton onClick={() => setShowUpdateModal(true)} />}

            {canDelete && <DeleteAppointmentButton onClick={() => setShowDeleteModal(true)} />}
          </div>
        </>
      )}

      {showUpdateModal && (
        <UpdateAppointmentModal
          appointment={appointment}
          onClose={() => setShowUpdateModal(false)}
          onSubmit={handleUpdateSubmit}
        />
      )}

      {showDeleteModal && (
        <DeleteAppointmentModal
          onClose={() => setShowDeleteModal(false)}
          onConfirm={handleDelete}
          title="Delete Appointment"
          message="Are you sure you want to delete this appointment? This action cannot be undone."
        />
      )}
    </div>
  )
}

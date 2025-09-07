"use client"

import { useRouter } from "next/navigation"
import AdminDashboardCard from "@/components/cards/AdminDashboardCard"
import styles from "./page.module.css"

export default function AdminDashboardPage() {
  const router = useRouter()

  const handleCategoryClick = () => {
    router.push("/admin-dashboard/categories")
  }

  const handleOrdersClick = () => {
    router.push("/admin-dashboard/orders")
  }

  const handleEmergencyClick = () => {
    router.push("/admin-dashboard/emergency")
  }

  return (
    <div className={styles.container}>
      <main className={styles.main}>
        <div className={styles.header}>
          <h1 className={styles.title}>Admin Dashboard</h1>
          <p className={styles.subtitle}>Manage your pet care platform</p>
        </div>

        <div className={styles.cardGrid}>
          <AdminDashboardCard
            title="Manage Categories"
            description="Create, edit, and organize pet and product categories"
            icon="📂"
            onClick={handleCategoryClick}
          />

          <AdminDashboardCard
            title="Manage Orders"
            description="View and process customer orders and payments"
            icon="📋"
            onClick={handleOrdersClick}
          />

          <AdminDashboardCard
            title="View Emergency Requests"
            description="Monitor and respond to emergency shelter requests"
            icon="🚨"
            onClick={handleEmergencyClick}
          />
        </div>
      </main>
    </div>
  )
}

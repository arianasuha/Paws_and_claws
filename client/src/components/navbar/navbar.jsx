"use client";
import { useState, useEffect } from "react";
import Link from "next/link";
import Image from "next/image";
import styles from "./navbar.module.css";
import { getAvailableNotificationAction } from "@/actions/notificationsActions";
import {
  getUserIdAction,
  getUserRoleAction,
  logoutAction,
} from "@/actions/authActions";

export default function Navbar() {
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
  const [userId, setUserId] = useState(null);
  const [userRole, setUserRole] = useState(null);
  const [notifications, setNotifications] = useState({ is_read: true });

  const handleLinkClick = (e, targetPath, hash) => {
    e.preventDefault();
    window.location.href = `${targetPath}#${hash}`;
  };

  useEffect(() => {
    const fetchUserId = async () => {
      try {
        const response = await getUserIdAction();
        if (response.error) {
          console.error("Failed to fetch user ID:", response.error);
          return;
        } else {
          setUserId(response);
        }
      } catch (error) {
        console.error("Failed to fetch user ID:", error);
      }
    };

    const fetchUserRole = async () => {
      try {
        const response = await getUserRoleAction();
        if (response.error) {
          console.error("Failed to fetch user role:", response.error);
          return;
        } else {
          setUserRole(response);
        }
      } catch (error) {
        console.error("Failed to fetch user role:", error);
      }
    };

    const fetchNotifications = async () => {
      try {
        const response = await getAvailableNotificationAction(userId);

        if (response.error) {
          console.error("Failed to fetch notifications:", response.error);
          return;
        } else {
          setNotifications(response.data);
        }
      } catch (error) {
        console.error("Failed to fetch notifications:", error);
      }
    };

    fetchUserId();
    fetchUserRole();
    fetchNotifications();
  }, []);

  const handleLogout = async () => {
    try {
      await logoutAction();
    } catch (error) {
      console.error("Logout failed:", error);
    }
  };

  const toggleMobileMenu = () => {
    setIsMobileMenuOpen(!isMobileMenuOpen);
  };

  return (
    <>
      <nav className={styles.navbar}>
        <div className={styles.navContainer}>
          <div className={styles.navLeft}>
            {/* Mobile Menu Button */}
            <button className={styles.mobileMenuBtn} onClick={toggleMobileMenu}>
              <Image src="/menu.svg" alt="Menu" width={24} height={24} />
            </button>
            {/* Logo */}
            <div className={styles.logo}>
              <Link href="/">
                <Image
                  src="/logo.png"
                  alt="Logo"
                  width={40}
                  height={40}
                  className={styles.logoImage}
                />
              </Link>
            </div>
            <Link href="/" className={styles.noUnderlineLink}>
              <h1 className={styles.logoText}>Paws and Claws</h1>
            </Link>
          </div>

          {/* Desktop Menu */}
          <div className={styles.desktopMenu}>
            <Link href="/pet" className={styles.navLink}>
              My Pets
            </Link>
            <Link href="/shop" className={styles.navLink}>
              Shop
            </Link>
            <Link href="/cart" className={styles.navLink}>
              Cart
            </Link>
            <Link 
              href="/services" 
              className={styles.navLink}
              onClick={(e) => handleLinkClick(e, "/", "services")}
              >
              Services
            </Link>
          </div>

          {/* Desktop Buttons & Notification */}
          <div className={styles.desktopActions}>
            <Link href="/appointment" className={styles.bookBtn}>
              Book Now
            </Link>
            <Link href="/emergency" className={styles.emergencyBtn}>
              Emergency
            </Link>

        
            <div className={styles.notificationIcon}>
              <Link href="/notification" className={styles.noUnderlineLink}>
                <Image
                  src="/notification.svg"
                  alt="Notifications"
                  width={24}
                  height={24}
                />
                {!notifications.is_read && (
                  <div className={styles.redDot}></div>
                )}
              </Link>
            </div>
          </div>
        </div>
      </nav>

      {/* Mobile Sidebar */}
      <div
        className={`${styles.mobileSidebar} ${
          isMobileMenuOpen ? styles.open : ""
        }`}
      >
        <div className={styles.sidebarContent}>
          <div className={styles.sidebarHeader}>
            <h2 className={styles.sidebarTitle}>Menu</h2>
            <button className={styles.closeBtn} onClick={toggleMobileMenu}>
              ×
            </button>
          </div>

          <div className={styles.sidebarMenu}>
            <Link
              href="/"
              className={styles.sidebarLink}
              onClick={toggleMobileMenu}
            >
              Home
            </Link>
            <Link
              href={`/profile/${userId}`}
              className={styles.sidebarLink}
              onClick={toggleMobileMenu}
            >
              Profile
            </Link>
            {userRole === "admin" && (
              <Link
                href="/admin-dashboard"
                className={styles.sidebarLink}
                onClick={toggleMobileMenu}
              >
                Admin-Dashboard
              </Link>
            )}
            <Link
              href={"/pet/"}
              className={styles.sidebarLink}
              onClick={toggleMobileMenu}
            >
              My Pets
            </Link>
            <Link
              href="/pet-medical-log"
              className={styles.sidebarLink}
              onClick={toggleMobileMenu}
            >
              Pet and Medical Logs
            </Link>
            <Link
              href="/appointment/my-appointment"
              className={styles.sidebarLink}
              onClick={toggleMobileMenu}
            >
              My Appointments
            </Link>
            <Link
              href="/appointment"
              className={styles.sidebarLink}
              onClick={toggleMobileMenu}
            >
              Book An Appointment
            </Link>
            <Link
              href={{ pathname: "/service", hash: "services" }}
              className={styles.sidebarLink}
              onClick={(e) => handleLinkClick(e, "/", "services")}
            >
              Services
            </Link>
            <Link
              href="/shop"
              className={styles.sidebarLink}
              onClick={toggleMobileMenu}
            >
              Shop
            </Link>
            <Link
              href="/orders"
              className={styles.sidebarLink}
              onClick={toggleMobileMenu}
            >
              Orders
            </Link>
            <Link
              href="/cart"
              className={styles.sidebarLink}
              onClick={toggleMobileMenu}
            >
              Cart
            </Link>
            <Link
              href="/payment"
              className={styles.sidebarLink}
              onClick={toggleMobileMenu}
            >
              Payment History
            </Link>
            <Link
              href="/lost-pet"
              className={styles.sidebarLink}
              onClick={toggleMobileMenu}
            >
              Lost Pets
            </Link>
            <Link
              href="/emergency"
              className={styles.sidebarLink}
              onClick={toggleMobileMenu}
            >
              Emergency Shelter
            </Link>
            <Link
              href="/notification"
              className={styles.sidebarLink}
              onClick={toggleMobileMenu}
            >
              Notification
            </Link>
            <button onClick={handleLogout} className={styles.sidebarLogout}>
              Logout
            </button>
          </div>
        </div>
      </div>

      {/* Overlay */}
      {isMobileMenuOpen && (
        <div className={styles.overlay} onClick={toggleMobileMenu}></div>
      )}
    </>
  );
}

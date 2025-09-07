"use client";
import { useState } from "react"
import { useFormStatus } from "react-dom";
import { useRouter } from "next/navigation";
import styles from "./buttons.module.css";
import { getUserIdAction, logoutAction } from "@/actions/authActions"
import { getCartItemsAction, updateCartItemsAction } from "@/actions/cartActions";
import { deleteNotificationAction } from "@/actions/notificationsActions"
import CreatePetModal from "@/components/modals/CreatePetModal"
import UpdatePetModal from "@/components/modals/UpdatePetModal"
import DeletePetModal from "@/components/modals/DeletePetModal"
import UpdatePetMarketModal from "@/components/modals/UpdatePetMarketModal"
import DeletePetMarketModal from "@/components/modals/DeletePetMarketModal"
import CreatePetProductModal from "@/components/modals/CreatePetProductModal"
import UpdatePetProductModal from "@/components/modals/UpdatePetProductModal"
import DeletePetProductModal from "@/components/modals/DeletePetProductModal"
import UpdateLostPetModal from "@/components/modals/UpdateLostPetModal"
import DeleteLostPetModal from "@/components/modals/DeleteLostPetModal"
import UpdateMedicalLogModal from "@/components/modals/UpdateMedicalLogModal"
import DeleteMedicalLogModal from "@/components/modals/DeleteMedicalLogModal"

export function SignupButton() {
  const { pending } = useFormStatus();

  return (
    <button type="submit" className={styles["signup-btn"]} disabled={pending}>
      {pending ? "Signing Up..." : "Sign Up"}
    </button>
  );
}

export function DeleteButton({ onClick }) {
  return (
    <button type="button" onClick={onClick} className={styles["delete-btn"]}>
      Delete Account
    </button>
  );
}

export function LoginButton() {
  const { pending } = useFormStatus()

  return (
    <button type="submit" className={styles["login-btn"]} disabled={pending}>
      {pending ? "Logging in..." : "Login"}
    </button>
  )
}

export function LogoutButton() {
  const { pending } = useFormStatus()

  return (
    <form action={logoutAction}>
      <button type="submit" className={styles["logout-btn"]} disabled={pending}>
        {pending ? "Logging Out..." : "Logout"}
      </button>
    </form>
  )
}

export function OptionButton({ href, children }) {
  const router = useRouter()

  const handleClick = () => {
    router.push(href)
  }

  return (
    <button onClick={handleClick} className={styles["option-btn"]}>
      {children}
    </button>
  )
}

export function ProfileButton({ userId }) {
  const router = useRouter()

  const handleClick = () => {
    if (userId) {
      router.push(`/profile/${userId}`) // Navigate to the dynamic profile page
    } else {
      router.push("/auth/login") // Fallback if no user ID (shouldn't happen if rendered conditionally)
    }
  }

  return (
    <button onClick={handleClick} className={styles["profile-btn"]}>
      Profile
    </button>
  )
}

export function ShowPetsButton() {
  const router = useRouter()

  const handleClick = () => {
    router.push("/pets") // This page will be created later
  }

  return (
    <button type="button" onClick={handleClick} className={styles["show-pets-btn"]}>
      Show My Pets
    </button>
  )
}

export function UpdateButton() {
  const { pending } = useFormStatus()

  return (
    <button type="submit" className={styles["update-btn"]} disabled={pending}>
      {pending ? "Updating..." : "Update Profile"}
    </button>
  )
}

export function VetSignupButton() {
  const { pending } = useFormStatus()

  return (
    <button type="submit" className={styles["vet-signup-btn"]} disabled={pending}>
      {pending ? "Signing Up Vet..." : "Sign Up as Vet"}
    </button>
  )
}

export function ServiceSignupButton() {
  const { pending } = useFormStatus()

  return (
    <button type="submit" className={styles["vet-signup-btn"]} disabled={pending}>
      {pending ? "Signing Up Provider..." : "Sign Up as a Service Provider"}
    </button>
  )
}

export function HomePageButton({ title, link }) {
  const router = useRouter()

  const handleClick = () => {
    router.push(link)
  }

  return (
    <button
      type="button"
      onClick={handleClick}
      className={styles.homePageButton}
    >
      {title}
    </button>
  )
}

export function CreatePetButton({ onSuccess }) {
  const [isModalOpen, setIsModalOpen] = useState(false)

  const handleOpenModal = () => {
    setIsModalOpen(true)
  }

  const handleCloseModal = () => {
    setIsModalOpen(false)
  }

  return (
    <>
      <button onClick={handleOpenModal} className={`${styles.CreatedPetButtonPrimary}`}>
        Add New Pet
      </button>

      {isModalOpen && <CreatePetModal isOpen={isModalOpen} onClose={handleCloseModal} onSuccess={onSuccess} />}
    </>
  )
}

export function UpdatePetButton({ pet, onSuccess}) {
  const [isModalOpen, setIsModalOpen] = useState(false)

  const handleOpenModal = () => {
    setIsModalOpen(true)
  }

  const handleCloseModal = () => {
    setIsModalOpen(false)
  }

  return (
    <>
      <button onClick={handleOpenModal} className={`${styles.UpdatePetButton} ${styles.UpdatePetSecondary}`}>
        Update
      </button>

      {isModalOpen && <UpdatePetModal isOpen={isModalOpen} pet={pet} onClose={handleCloseModal} onSuccess={onSuccess}/>}
    </>
  )
}

export function DeletePetButton({ onSuccess, pet }) {
  const [isModalOpen, setIsModalOpen] = useState(false)

  const handleOpenModal = () => {
    setIsModalOpen(true)
  }

  const handleCloseModal = () => {
    setIsModalOpen(false)
  }

  return (
    <>
      <button onClick={handleOpenModal} className={`${styles.DeletePetDestructive}`}>
        Delete
      </button>

      {isModalOpen && (
        <DeletePetModal onSuccess={onSuccess} isOpen={isModalOpen} pet={pet} onClose={handleCloseModal} />
      )}
    </>
  )
}

export function CreatePetMarketButton() {
  const router = useRouter()

  const handleClick = () => {
    router.push("/shop/pet-market/new")
  }

  return (
    <button onClick={handleClick} className={`${styles.CPMarketButton}`}>
      Add Pet To Market
    </button>
  )
}

export function CreatePetMarketSubmitButton({ isSubmitting }) {
  return (
    <button type="submit" className={styles.CPMSbutton} disabled={isSubmitting}>
      {isSubmitting ? "Creating..." : "Create Pet Market"}
    </button>
  )
}

export function UpdatePetMarketButton({ petMarket, onSuccess}) {
  const [isModalOpen, setIsModalOpen] = useState(false)

  const handleOpenModal = () => {
    setIsModalOpen(true)
  }

  const handleCloseModal = () => {
    setIsModalOpen(false)
  }

  return (
    <>
      <button onClick={handleOpenModal} className={`${styles.UPMarketButton} ${styles.UPMarketButtonSecondary}`}>
        Update Market Details
      </button>

      {isModalOpen && <UpdatePetMarketModal isOpen={isModalOpen} petMarket={petMarket} onClose={handleCloseModal} onSuccess={onSuccess}/>}
    </>
  )
}

export function DeletePetMarketButton({ onSuccess, petMarket }) {
  const [isModalOpen, setIsModalOpen] = useState(false)

  const handleOpenModal = () => {
    setIsModalOpen(true)
  }

  const handleCloseModal = () => {
    setIsModalOpen(false)
  }

  return (
    <>
      <button onClick={handleOpenModal} className={`${styles.DPMarketButton}`}>
        Delete Pet From Market
      </button>

      {isModalOpen && (
        <DeletePetMarketModal onSuccess={onSuccess} isOpen={isModalOpen} petMarket={petMarket} onClose={handleCloseModal} />
      )}
    </>
  )
}

export function CreatePetProductButton({ onSuccess}) {
  const [isModalOpen, setIsModalOpen] = useState(false)

  const handleOpenModal = () => {
    setIsModalOpen(true)
  }

  const handleCloseModal = () => {
    setIsModalOpen(false)
  }

  return (
    <>
      <button className={styles.CPPButton} onClick={handleOpenModal}>
        Add New Product
      </button>

      {isModalOpen && <CreatePetProductModal isOpen={isModalOpen} onClose={handleCloseModal} onSuccess={onSuccess} />}
    </>
  )
}

export function UpdatePetProductButton({ product, onSuccess}) {
  const [isModalOpen, setIsModalOpen] = useState(false)

  const handleOpenModal = () => {
    setIsModalOpen(true)
  }

  const handleCloseModal = () => {
    setIsModalOpen(false)
  }

  return (
    <>
      <button onClick={handleOpenModal} className={`${styles.UpdatePetButton} ${styles.UpdatePetSecondary}`}>
        Update Product Details
      </button>

      {isModalOpen && <UpdatePetProductModal isOpen={isModalOpen} product={product} onClose={handleCloseModal} onSuccess={onSuccess}/>}
    </>
  )
}

export function DeletePetProductButton({ onSuccess, product }) {
  const [isModalOpen, setIsModalOpen] = useState(false)

  const handleOpenModal = () => {
    setIsModalOpen(true)
  }

  const handleCloseModal = () => {
    setIsModalOpen(false)
  }

  return (
    <>
      <button onClick={handleOpenModal} className={`${styles.DeletePetDestructive}`}>
        Delete Product
      </button>

      {isModalOpen && (
        <DeletePetProductModal onSuccess={onSuccess} isOpen={isModalOpen} product={product} onClose={handleCloseModal} />
      )}
    </>
  )
}

export function CreateLostPetButton() {
  const router = useRouter()

  const handleClick = () => {
    router.push("/lost-pet/new")
  }

  return (
    <button onClick={handleClick} className={`${styles.CPMarketButton}`}>
      Add Your Lost Pet
    </button>
  )
}

export function CreateLostPetSubmitButton({ isSubmitting }) {
  return (
    <button type="submit" className={styles.CPMSbutton} disabled={isSubmitting}>
      {isSubmitting ? "Creating..." : "Create Lost Pet"}
    </button>
  )
}

export function UpdateLostPetButton({ lostPet, onSuccess}) {
  const [isModalOpen, setIsModalOpen] = useState(false)

  const handleOpenModal = () => {
    setIsModalOpen(true)
  }

  const handleCloseModal = () => {
    setIsModalOpen(false)
  }

  return (
    <>
      <button onClick={handleOpenModal} className={`${styles.UPMarketButton} ${styles.UPMarketButtonSecondary}`}>
        Update Lost Details
      </button>

      {isModalOpen && <UpdateLostPetModal isOpen={isModalOpen} lostPet={lostPet} onClose={handleCloseModal} onSuccess={onSuccess}/>}
    </>
  )
}

export function DeleteLostPetButton({ onSuccess, lostPet }) {
  const [isModalOpen, setIsModalOpen] = useState(false)

  const handleOpenModal = () => {
    setIsModalOpen(true)
  }

  const handleCloseModal = () => {
    setIsModalOpen(false)
  }

  return (
    <>
      <button onClick={handleOpenModal} className={`${styles.DPMarketButton}`}>
        Delete Lost Pet Listing
      </button>

      {isModalOpen && (
        <DeleteLostPetModal onSuccess={onSuccess} isOpen={isModalOpen} lostPet={lostPet} onClose={handleCloseModal} />
      )}
    </>
  )
}

export function CreatePetMedicalButton() {
  const router = useRouter()

  const handleClick = () => {
    router.push("/pet-medical-log/new")
  }

  return (
    <button onClick={handleClick} className={`${styles.CPMarketButton}`}>
      Add Pet Medical Log
    </button>
  )
}

export function CreateMedicalSubmitButton({ isSubmitting }) {
  return (
    <button type="submit" className={styles.CPMSbutton} disabled={isSubmitting}>
      {isSubmitting ? "Creating..." : "Create Pet Medical Log"}
    </button>
  )
}

export function UpdateMedicalLogButton({ medicalLog, onSuccess}) {
  const [isModalOpen, setIsModalOpen] = useState(false)

  const handleOpenModal = () => {
    setIsModalOpen(true)
  }

  const handleCloseModal = () => {
    setIsModalOpen(false)
  }

  return (
    <>
      <button onClick={handleOpenModal} className={`${styles.UPMarketButton} ${styles.UPMarketButtonSecondary}`}>
        Update Market Details
      </button>

      {isModalOpen && <UpdateMedicalLogModal isOpen={isModalOpen} medicalLog={medicalLog} onClose={handleCloseModal} onSuccess={onSuccess}/>}
    </>
  )
}

export function DeleteMedicalLogButton({ onSuccess, medicalLog }) {
  const [isModalOpen, setIsModalOpen] = useState(false)

  const handleOpenModal = () => {
    setIsModalOpen(true)
  }

  const handleCloseModal = () => {
    setIsModalOpen(false)
  }

  return (
    <>
      <button onClick={handleOpenModal} className={`${styles.DPMarketButton}`}>
        Delete Pet From Market
      </button>

      {isModalOpen && (
        <DeleteMedicalLogModal onSuccess={onSuccess} isOpen={isModalOpen} medicalLog={medicalLog} onClose={handleCloseModal} />
      )}
    </>
  )
}

export function AddToCartButton({ productId, disabled = false }) {
  const [isAdding, setIsAdding] = useState(false)
  const [added, setAdded] = useState(false)

  const handleAddToCart = async (e) => {
    e.stopPropagation()

    if (disabled || isAdding) return

    setIsAdding(true)

    try {
      const userId = await getUserIdAction()
      if (!userId) {
        console.error("User not logged in")
        return
      }

      const cartResult = await getCartItemsAction(userId)
      let cartId = cartResult['data']['cart_id']
      let existingItems = cartResult['data']['cart_items']

      if (cartResult.data && Array.isArray(cartResult.data)) {
        existingItems = cartResult.data
        if (existingItems.length > 0) {
          cartId = existingItems[0].cart_id
        }
      }

      const existingItemIndex = existingItems.findIndex((item) => item.product_id === productId)

      let updatedItems = []
      if (existingItemIndex >= 0) {
        updatedItems = existingItems.map((item) => ({
          product_id: item.product_id,
          quantity: item.product_id === productId ? item.quantity + 1 : item.quantity,
        }))
      } else {
        updatedItems = [
          ...existingItems.map((item) => ({
            product_id: item.product_id,
            quantity: item.quantity,
          })),
          { product_id: productId, quantity: 1 },
        ]
      }

      if (cartId) {
        const updateResult = await updateCartItemsAction(cartId, updatedItems)
        if (updateResult.error) {
          console.error("Failed to update cart:", updateResult.error)
          return
        }
      }

      setAdded(true)
      setTimeout(() => setAdded(false), 2000)
    } catch (error) {
      console.error("Failed to add to cart:", error)
    } finally {
      setIsAdding(false)
    }
  }

  return (
    <button
      className={`${styles.ATCButton} ${disabled ? styles.ATCdisabled : ""} ${added ? styles.ATCadded : ""}`}
      onClick={handleAddToCart}
      disabled={disabled || isAdding}
    >
      {isAdding ? (
        <span className={styles.ATCloading}>Adding...</span>
      ) : added ? (
        <span className={styles.ATCsuccess}>Added!</span>
      ) : disabled ? (
        "Out of Stock"
      ) : (
        "Add to Cart"
      )}
    </button>
  )
}

export function CheckoutButton({ onClick }) {
  const { pending } = useFormStatus()

  return (
    <button className={styles.CheckoutButton} onClick={onClick} disabled={pending}>
      {pending ? "Processing..." : "Proceed to Checkout"}
    </button>
  )
}

function CancelButton() {
  const { pending } = useFormStatus()

  return (
    <button type="submit" disabled={pending} className={styles.CancelOrdercancelButton}>
      {pending ? "Cancelling..." : "Cancel Order"}
    </button>
  )
}

export function CancelOrderButton({ onCancel }) {
  const [showConfirm, setShowConfirm] = useState(false)

  const handleSubmit = async (formData) => {
    await onCancel()
    setShowConfirm(false)
  }

  if (showConfirm) {
    return (
      <div className={styles.CancelOrderconfirmDialog}>
        <p className={styles.CancelOrderconfirmText}>Are you sure you want to cancel this order?</p>
        <div className={styles.CancelOrderconfirmButtons}>
          <form action={handleSubmit}>
            <CancelButton />
          </form>
          <button onClick={() => setShowConfirm(false)} className={styles.CancelOrderkeepButton}>
            Keep Order
          </button>
        </div>
      </div>
    )
  }

  return (
    <button onClick={() => setShowConfirm(true)} className={styles.CancelOrderbutton}>
      Cancel Order
    </button>
  )
}

export function AdminDashboardButton({ children, onClick, disabled = false }) {
  return (
    <button className={styles.AdminDashboardButton} onClick={onClick} disabled={disabled}>
      {children}
    </button>
  )
}

export function DeleteNotificationButton({ notificationId, onDelete }) {
  const [showModal, setShowModal] = useState(false)
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState("")
  const [success, setSuccess] = useState("")

  const handleDelete = async () => {
    setLoading(true)
    setError("")
    setSuccess("")

    try {
      const result = await deleteNotificationAction(notificationId)

      if (result.error) {
        setError(result.error)
      } else {
        setSuccess(result.success || "Notification deleted successfully")
        setTimeout(() => {
          setShowModal(false)
          onDelete()
        }, 1500)
      }
    } catch (err) {
      setError("Failed to delete notification")
    } finally {
      setLoading(false)
    }
  }

  const handleCancel = () => {
    setShowModal(false)
    setError("")
    setSuccess("")
  }

  return (
    <>
      <button onClick={() => setShowModal(true)} className={styles.DeleteNotificationdeleteButton}>
        Delete
      </button>

      {showModal && (
        <div className={styles.DeleteNotificationmodalOverlay}>
          <div className={styles.DeleteNotificationmodal}>
            <div className={styles.DeleteNotificationmodalHeader}>
              <h3 className={styles.DeleteNotificationmodalTitle}>Delete Notification</h3>
            </div>

            <div className={styles.DeleteNotificationmodalContent}>
              <p className={styles.DeleteNotificationconfirmMessage}>
                Are you sure you want to delete this notification? This action cannot be undone.
              </p>

              {error && <div className={styles.DeleteNotificationerror}>{error}</div>}

              {success && <div className={styles.DeleteNotificationsuccess}>{success}</div>}
            </div>

            <div className={styles.DeleteNotificationmodalActions}>
              <button onClick={handleCancel} className={styles.DeleteNotificationcancelButton} disabled={loading}>
                No, Cancel
              </button>
              <button onClick={handleDelete} className={styles.DeleteNotificationconfirmButton} disabled={loading}>
                {loading ? "Deleting..." : "Yes, Delete"}
              </button>
            </div>
          </div>
        </div>
      )}
    </>
  )
}

export function RemoveButton({ onClick, disabled = false }) {
  const { pending } = useFormStatus()

  return (
    <button
      type="button"
      onClick={onClick}
      disabled={disabled || pending}
      className={styles.removeButton}
      aria-label="Remove item"
    >
      {pending ? (
        <span className={styles.removeButton.spinner}></span>
      ) : (
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
          <path d="M18 6L6 18M6 6l12 12" />
        </svg>
      )}
    </button>
  )
}

export function CreateCategoryButton({ onClick, disabled = false }) {
  return (
    <button type="button" onClick={onClick} disabled={disabled} className={styles.createCategoryButton}>
      <svg
        width="16"
        height="16"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        strokeWidth="2"
        className={styles.icon}
      >
        <path d="M12 5v14M5 12h14" />
      </svg>
      Create Category
    </button>
  )
}

export function DeleteEmergencyButton({ onClick }) {
  return (
    <button className={styles.deleteEmergencyButton} onClick={onClick} type="button">
      🗑️
    </button>
  )
}

export function DeleteAppointmentButton({ onClick }) {
  return (
    <button type="button" onClick={onClick} className={styles.DAbutton}>
      Delete Appointment
    </button>
  )
}

export function UpdateAppointmentButton({ onClick }) {
  const { pending } = useFormStatus()

  return (
    <button type="button" onClick={onClick} disabled={pending} className={styles.UAbutton}>
      {pending ? "Updating..." : "Update Appointment"}
    </button>
  )
}

export function BookNowButton({ onClick, disabled = false }) {
  const { pending } = useFormStatus()

  return (
    <button
      type="button"
      onClick={onClick}
      disabled={disabled || pending}
      className={`${styles.BNbutton} ${disabled ? styles.BNdisabled : ""} ${pending ? styles.BNpending : ""}`}
    >
      {pending ? "Processing..." : "Book Now"}
    </button>
  )
}
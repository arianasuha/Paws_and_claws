"use client";
import { useFormStatus } from "react-dom";
import { useRouter } from "next/navigation";
import styles from "./buttons.module.css";
import { logoutAction } from "@/actions/authActions"

export function CreatePetButton() {
  const router = useRouter();

  const handleClick = () => {
    router.push("/pets/create");
  };

  return (
    <button
      type="button"
      onClick={handleClick}
      className={styles["create-pet-btn"]}
    >
      Add New Pet
    </button>
  );
}

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

export function DeletePetButton({ onClick }) {
  return (
    <button type="button" onClick={onClick} className={styles["delete-pet-btn"]}>
      Delete Pet
    </button>
  )
}

export function DetailButton({ petId }) {
  const router = useRouter()

  const handleClick = () => {
    router.push(`/pets/${petId}`)
  }

  return (
    <button type="button" onClick={handleClick} className={styles["detail-btn"]}>
      View Details
    </button>
  )
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

export function UpdatePetButton() {
  const { pending } = useFormStatus()

  return (
    <button type="submit" className={styles["update-pet-btn"]} disabled={pending}>
      {pending ? "Updating Pet..." : "Update Pet"}
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

export function DeleteListingButton({ petMarketId }) {
  const router = useRouter()

  const handleClick = () => {
    router.push(`/pet-market/${petMarketId}/delete-listing`)
  }

  return (
    <button type="button" onClick={handleClick} className={styles["delete-listing-btn"]}>
      Delete Listing
    </button>
  )
}

export function UpdateListingButton({ petMarketId }) {
  const router = useRouter()

  const handleClick = () => {
    router.push(`/pet-market/${petMarketId}/update-listing`)
  }

  return (
    <button type="button" onClick={handleClick} className={styles["update-listing-btn"]}>
      Update Listing
    </button>
  )
}

export function UpdatePetMarketButton({ petMarketId }) {
  const router = useRouter()

  const handleClick = () => {
    router.push(`/pet-market/${petMarketId}/update-pet`)
  }

  return (
    <button type="button" onClick={handleClick} className={styles["update-pet-market-btn"]}>
      Update Pet
    </button>
  )
}
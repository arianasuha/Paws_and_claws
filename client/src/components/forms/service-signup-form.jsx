"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";
import { createServiceProviderAction } from "@/actions/serviceActions";
import { ServiceSignupButton } from "@/components/buttons/buttons";
import styles from "./service-signup-form.module.css";

export default function ServiceSignupForm() {
  const [errors, setErrors] = useState({});
  const [successMessage, setSuccessMessage] = useState("");
  const router = useRouter();

  const handleSubmit = async (event) => {
    event.preventDefault();

    setErrors({});
    setSuccessMessage("");

    const formData = new FormData(event.currentTarget);
    const result = await createServiceProviderAction(formData);

    if (result.success) {
      setSuccessMessage(result.success);
      setErrors({});
      router.push("/auth/login"); // Redirect to login after successful service signup
    } else if (result.error) {
      setErrors(result.error);
      setSuccessMessage("");
    } else {
      setErrors({ general: "An unexpected error occurred." });
      setSuccessMessage("");
    }
  };

  return (
    <div className={styles["service-signup-form-container"]}>
      <form onSubmit={handleSubmit} className={styles["service-signup-form"]}>
        <h2 className={styles["form-title"]}>Sign Up as a Service Provider</h2>

        {errors.general && (
          <div className={styles["error-message"]}>{errors.general}</div>
        )}
        {errors.error && (
          <div className={styles["error-message"]}>{errors.error}</div>
        )}
        {successMessage && (
          <div className={styles["success-message"]}>{successMessage}</div>
        )}

        <div className={styles["form-group"]}>
          <label htmlFor="email" className={styles["form-label"]}>
            Email
          </label>
          <input
            id="email"
            name="email"
            type="email"
            placeholder="your@example.com"
            required
            className={styles["form-input"]}
          />
          {errors.email && (
            <p className={styles["field-error"]}>{errors.email}</p>
          )}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="username" className={styles["form-label"]}>
            Username (Optional)
          </label>
          <input
            id="username"
            name="username"
            type="text"
            placeholder="Choose a username"
            className={styles["form-input"]}
          />
          {errors.username && (
            <p className={styles["field-error"]}>{errors.username}</p>
          )}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="first_name" className={styles["form-label"]}>
            First Name (Optional)
          </label>
          <input
            id="first_name"
            name="first_name"
            type="text"
            placeholder="Your first name"
            className={styles["form-input"]}
          />
          {errors.first_name && (
            <p className={styles["field-error"]}>{errors.first_name}</p>
          )}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="last_name" className={styles["form-label"]}>
            Last Name (Optional)
          </label>
          <input
            id="last_name"
            name="last_name"
            type="text"
            placeholder="Your last name"
            className={styles["form-input"]}
          />
          {errors.last_name && (
            <p className={styles["field-error"]}>{errors.last_name}</p>
          )}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="address" className={styles["form-label"]}>
            Address (Optional)
          </label>
          <input
            id="address"
            name="address"
            type="text"
            placeholder="Your address"
            className={styles["form-input"]}
          />
          {errors.address && (
            <p className={styles["field-error"]}>{errors.address}</p>
          )}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="service_type" className={styles["form-label"]}>
            Service Type
          </label>
          <select
            id="service_type"
            name="service_type"
            required
            className={styles["form-input"]}
         
            defaultValue=""
          >
            <option value="" disabled>
              Select a service type
            </option>
            <option value="walker">Walker</option>
            <option value="groomer">Groomer</option>
            <option value="trainer">Trainer</option>
          </select>
          {errors.service_type && (
            <p className={styles["field-error"]}>{errors.service_type}</p>
          )}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="service_desc" className={styles["form-label"]}>
            Service Description
          </label>
          <input
            id="service_desc"
            name="service_desc"
            type="text"
            placeholder="Service Description"
            required
            className={styles["form-input"]}
          />
          {errors.service_desc && (
            <p className={styles["field-error"]}>{errors.service_desc}</p>
          )}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="rate_per_hour" className={styles["form-label"]}>
            Rate Per Hour
          </label>
          <input
            id="rate_per_hour"
            name="rate_per_hour"
            type="float"
            placeholder="10.00"
            required
            className={styles["form-input"]}
          />
          {errors.rate_per_hour && (
            <p className={styles["field-error"]}>{errors.rate_per_hour}</p>
          )}
        </div>

        <div className={styles["form-group"]}>
          <label htmlFor="password" className={styles["form-label"]}>
            Password
          </label>
          <input
            id="password"
            name="password"
            type="password"
            placeholder="********"
            required
            className={styles["form-input"]}
          />
          {errors.password && (
            <p className={styles["field-error"]}>{errors.password}</p>
          )}
        </div>

        <div className={styles["form-group"]}>
          <label
            htmlFor="password_confirmation"
            className={styles["form-label"]}
          >
            Confirm Password
          </label>
          <input
            id="password_confirmation"
            name="password_confirmation"
            type="password"
            placeholder="********"
            required
            className={styles["form-input"]}
          />
          {errors.password_confirmation && (
            <p className={styles["field-error"]}>
              {errors.password_confirmation}
            </p>
          )}
        </div>

        <ServiceSignupButton />

        <div className={styles["form-footer"]}>
          Already have an account?{" "}
          <a href="/auth/login" className={styles["login-link"]}>
            Login here
          </a>
        </div>
      </form>
    </div>
  );
}

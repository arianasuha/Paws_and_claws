"use server";
import {
  getVets,
  getVet,
  createVet,
  updateVet,
  deleteVet,
} from "@/libs/api";
import { deleteSessionCookie } from "@/libs/cookie";

export const actionError = async (response) => {
  if (typeof response.error === "object") {
    const errorMessages = {};

    if (response.error.email) {
      errorMessages["email"] = response.error.email;
    }

    if (response.error.username) {
      errorMessages["username"] = response.error.username;
    }

    if (response.error.first_name) {
      errorMessages["first_name"] = response.error.first_name;
    }

    if (response.error.last_name) {
      errorMessages["last_name"] = response.error.last_name;
    }

    if (response.error.address) {
      errorMessages["address"] = response.error.address;
    }

    if (response.error.clinic_name) {
      errorMessages["clinic_name"] = response.error.clinic_name;
    }

    if (response.error.specialization) {
      errorMessages["specialization"] = response.error.specialization;
    }

    if (response.error.services_offered) {
      errorMessages["services_offered"] = response.error.services_offered;
    }

    if (response.error.working_hour) {
      errorMessages["working_hour"] = response.error.working_hour;
    }

    // Check for each possible attribute and append its messages
    if (response.error.password) {
      errorMessages["password"] = response.error.password;
    }

    // Combine messages into a single string with \n between each
    return { error: errorMessages };
  }

  // If it's not an object, return the error as is (string or other type)
  return { error: { error: response.error } };
};

export const getVetsAction = async () => {
  try {
    const response = await getVets();

    if (response.error) {
      return { error: response.error };
    }

    return {
      data: response.data,
      pagination: {
        count: response.total,
        total_pages: Math.ceil(response.total / response.per_page),
        next: response.next_page_url ? new URL(response.next_page_url).search : null,
        previous: response.prev_page_url ? new URL(response.prev_page_url).search : null,
      },
    };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to fetch users." };
  }
};

export const getVetAction = async (id) => {
  try {
    const response = await getVet(id);
    
    if (response.error) {
      return { error: response.error };
    }

    return { data: response };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to fetch user." };
  }
};

export const createVetAction = async (formData) => {
  const email = formData.get("email");
  const username = formData.get("username");
  const first_name = formData.get("first_name");
  const last_name = formData.get("last_name");
  const address = formData.get("address");
  const password = formData.get("password");
  const password_confirmation = formData.get("password_confirmation");
  const clinic_name = formData.get("clinic_name");
  const specialization = formData.get("specialization");
  const services_offered = formData.get("services_offered");
  const working_hour = formData.get("working_hour");

  const errors = {};

  if (!email) {
    errors.email = "Email is required.";
  } else if (!email.includes("@")) {
    errors.email = "Invalid email format.";
  }

  if (!password) {
    errors.password = "Password is required.";
  }

  if (!password_confirmation) {
    errors.password_confirmation = "Password confirmation is required.";
  }

  if (password !== password_confirmation) {
    errors.password_confirmation = "Passwords do not match.";
  }

  if (Object.keys(errors).length > 0) {
    return { error: errors };
  }

  const data = {
    email,
    ...(username && { username }),
    ...(first_name && { first_name }),
    ...(last_name && { last_name }),
    ...(address && { address }),
    ...(clinic_name && { clinic_name }),
    ...(specialization && { specialization }),
    ...(services_offered && { services_offered }),
    ...(working_hour && { working_hour }),
    password,
    password_confirmation,
  };

  try {
    const response = await createVet(data);
    if (response.error) {
      return actionError(response);
    }

    return { success: response.success };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to create user." };
  }
};

export const updateVetAction = async (id, formData) => {
  const email = formData.get("email");
  const username = formData.get("username");
  const first_name = formData.get("first_name");
  const last_name = formData.get("last_name");
  const address = formData.get("address");
  const password = formData.get("password");
  const password_confirmation = formData.get("password_confirmation");
  const clinic_name = formData.get("clinic_name");
  const specialization = formData.get("specialization");
  const services_offered = formData.get("services_offered");
  const working_hour = formData.get("working_hour");

  const data = {
    email,
    ...(username && { username }),
    ...(first_name && { first_name }),
    ...(last_name && { last_name }),
    ...(address && { address }),
    ...(clinic_name && { clinic_name }),
    ...(specialization && { specialization }),
    ...(services_offered && { services_offered }),
    ...(working_hour && { working_hour }),
    ...(password && { password }),
    ...(password_confirmation && { password_confirmation }),
  };

  try {
    const response = await updateVet(id, data);

    if (response.error) {
      return actionError(response);
    }

    return { success: response.success };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to update user." };
  }
};

export const deleteVetAction = async (id) => {
  try {
    const response = await deleteVet(id);

    if (response.error) {
      return { error: response.error };
    }

    await deleteSessionCookie();
    
    return { success: response.success };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to delete user." };
  }
};

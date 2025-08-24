"use server";
import {
  getServiceProviders,
  getServiceProvider,
  createServiceProvider,
  updateServiceProvider,
  deleteServiceProvider
} from "@/libs/api";
import { logoutAction } from "./authActions";

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

    if (response.error.service_type) {
      errorMessages["service_type"] = response.error.service_type;
    }

    if (response.error.service_desc) {
      errorMessages["service_desc"] = response.error.service_desc;
    }

    if (response.error.rate_per_hour) {
      errorMessages["rate_per_hour"] = response.error.rate_per_hour;
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

export const getServiceProvidersAction = async () => {
  try {
    const response = await getServiceProviders();

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

export const getServiceProviderAction = async (id) => {
  try {
    const response = await getServiceProvider(id);
    
    if (response.error) {
      return { error: response.error };
    }

    return { data: response };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to fetch user." };
  }
};

export const createServiceProviderAction = async (formData) => {
  const email = formData.get("email");
  const username = formData.get("username");
  const first_name = formData.get("first_name");
  const last_name = formData.get("last_name");
  const address = formData.get("address");
  const password = formData.get("password");
  const password_confirmation = formData.get("password_confirmation");
  const service_type = formData.get("service_type");
  const service_desc = formData.get("service_desc");
  const rate_per_hour = formData.get("rate_per_hour");

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
    ...(service_type && { service_type }),
    ...(service_desc && { service_desc }),
    ...(rate_per_hour && { rate_per_hour }),
    password,
    password_confirmation,
  };

  try {
    const response = await createServiceProvider(data);
    if (response.error) {
      return actionError(response);
    }

    return { success: response.success };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to create user." };
  }
};

export const updateServiceProviderAction = async (id, formData) => {
  const email = formData.get("email");
  const username = formData.get("username");
  const first_name = formData.get("first_name");
  const last_name = formData.get("last_name");
  const address = formData.get("address");
  const password = formData.get("password");
  const password_confirmation = formData.get("password_confirmation");
  const service_type = formData.get("service_type");
  const service_desc = formData.get("service_desc");
  const rate_per_hour = formData.get("rate_per_hour");

  const data = {
    email,
    ...(username && { username }),
    ...(first_name && { first_name }),
    ...(last_name && { last_name }),
    ...(address && { address }),
    ...(service_type && { service_type }),
    ...(service_desc && { service_desc }),
    ...(rate_per_hour && { rate_per_hour }),
    ...(password && { password }),
    ...(password_confirmation && { password_confirmation }),
  };

  try {
    const response = await updateServiceProvider(id, data);

    if (response.error) {
      return actionError(response);
    }

    return { success: response.success };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to update user." };
  }
};

export const deleteServiceProviderAction = async (id) => {
  try {
    const response = await deleteServiceProvider(id);

    if (response.error) {
      return { error: response.error };
    }

    await logoutAction()
    return { success: response.success };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to delete user." };
  }
};

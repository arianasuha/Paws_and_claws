"use server";
import {
  getAppointments,
  getAppointment,
  createAppointment,
  updateAppointment,
  deleteAppointment,
} from "@/libs/api";

export const actionError = async (response) => {
  if (typeof response.error === "object") {
    const errorMessages = {};

    if (response.error.status) {
      errorMessages["status"] = response.error.status;
    }

    if (response.error.pet_id) {
      errorMessages["pet"] = response.error.pet_id;
    }

    if (response.error.provider_id) {
      errorMessages["provider"] = response.error.provider_id;
    }

    if (response.error.app_date) {
      errorMessages["app_date"] = response.error.app_date;
    }

    if (response.error.app_time) {
      errorMessages["app_time"] = response.error.app_time;
    }

    if (response.error.visit_reason) {
      errorMessages["visit_reason"] = response.error.visit_reason;
    }

    // Combine messages into a single string with \n between each
    return { error: errorMessages };
  }

  // If it's not an object, return the error as is (string or other type)
  return { error: { error: response.error } };
};

export const getAppointmentsAction = async (queryParams = {}) => {
  try {
    const response = await getAppointments(queryParams);

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

export const getAppointmentAction = async (id) => {
  try {
    const response = await getAppointment(id);

    if (response.error) {
      return { error: response.error };
    }

    return { data: response.appointment };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to fetch user." };
  }
};

export const createAppointmentAction = async (formData) => {
  const pet_id = formData["pet"];
  const provider_id = formData["provider"];
  const app_date = formData["app_date"];
  const app_time = formData["app_time"];
  const visit_reason = formData["visit_reason"];

  const data = {
    pet_id,
    provider_id,
    app_date,
    app_time,
    visit_reason,
  };

  try {
    const response = await createAppointment(data);

    if (response.error) {
      return actionError(response);
    }

    return { success: response.success };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to create user." };
  }
};

export const updateAppointmentAction = async (id, formData) => {
  const status = formData["status"];

  const data = {
    ...(status && { status }),
  };

  try {
    const response = await updateAppointment(id, data);

    if (response.error) {
      return actionError(response);
    }

    return { success: response.success };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to update user." };
  }
};

export const deleteAppointmentAction = async (id) => {
  try {
    const response = await deleteAppointment(id);

    if (response.error) {
      return { error: response.error };
    }
    
    return { success: response.success };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to delete user." };
  }
};

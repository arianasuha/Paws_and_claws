"use server";
import {
  getMedicalLogs,
  getMedicalLog,
  createMedicalLog,
  updateMedicalLog,
  deleteMedicalLog,
} from "@/libs/api";

export const actionError = async (response) => {
  if (typeof response.error === "object") {
    const errorMessages = {};

    if (response.error.pet_id) {
      errorMessages["pet"] = response.error.pet_id;
    }

    if (response.error.visit_date) {
      errorMessages["visit_date"] = response.error.visit_date;
    }

    if (response.error.reason_for_visit) {
      errorMessages["reason_for_visit"] = response.error.reason_for_visit;
    }

    if (response.error.diagnosis) {
      errorMessages["diagnosis"] = response.error.diagnosis;
    }

    if (response.error.notes) {
      errorMessages["notes"] = response.error.notes;
    }

    if (response.error.vet_name) {
      errorMessages["vet_name"] = response.error.vet_name;
    }

    if (response.error.clinic_name) {
      errorMessages["clinic_name"] = response.error.clinic_name;
    }

    if (response.error.treatment_prescribed) {
      errorMessages["treatment_prescribed"] = response.error.treatment_prescribed;
    }

    if (response.error.attachment_url) {
      errorMessages["attachment_url"] = response.error.attachment_url;
    }

    // Combine messages into a single string with \n between each
    return { error: errorMessages };
  }

  // If it's not an object, return the error as is (string or other type)
  return { error: { error: response.error } };
};

export const getMedicalLogsAction = async (pet_id, queryParams = {page: 1}) => {
  try {
    const response = await getMedicalLogs(pet_id, queryParams);
    if (response.error) {
      return { error: response.error };
    }

    return {
      data: response.medical_logs.data,
      pagination: {
        count: response.medical_logs.total,
        total_pages: Math.ceil(response.medical_logs.total / response.medical_logs.per_page),
        next: response.next_page_url ? new URL(response.medical_logs.next_page_url).search : null,
        previous: response.prev_page_url ? new URL(response.medical_logs.prev_page_url).search : null,
      },
    };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to fetch users." };
  }
};

export const getMedicalLogAction = async (id) => {
  try {
    const response = await getMedicalLog(id);

    if (response.error) {
      return { error: response.error };
    }

    return { data: response };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to fetch user." };
  }
};

export const createMedicalLogAction = async (formData) => {
  const pet_id = formData["pet"];
  const visit_date = formData["visit_date"];
  const reason_for_visit = formData["reason_for_visit"];
  const diagnosis = formData["diagnosis"];
  const notes = formData["notes"];
  const vet_name = formData["vet_name"];
  const clinic_name = formData["clinic_name"];
  const treatment_prescribed = formData["treatment_prescribed"];
  const attachment_url = formData["attachment_url"];

  const data = {
    pet_id,
    visit_date,
    reason_for_visit,
    diagnosis,
    notes,
    vet_name,
    clinic_name,
    treatment_prescribed,
    attachment_url,
  };

  try {
    const response = await createMedicalLog(data);

    if (response.error) {
      return actionError(response);
    }

    return { success: response.success };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to create user." };
  }
};

export const updateMedicalLogAction = async (id, formData) => {
  const visit_date = formData.get("visit_date");
  const reason_for_visit = formData.get("reason_for_visit");
  const diagnosis = formData.get("diagnosis");
  const notes = formData.get("notes");
  const vet_name = formData.get("vet_name");
  const clinic_name = formData.get("clinic_name");
  const treatment_prescribed = formData.get("treatment_prescribed");
  const attachment_url = formData.get("attachment_url");

  const data = {
    ...(visit_date && { visit_date }),
    ...(reason_for_visit && { reason_for_visit }),
    ...(diagnosis && { diagnosis }),
    ...(notes && { notes }),
    ...(vet_name && { vet_name }),
    ...(clinic_name && { clinic_name }),
    ...(treatment_prescribed && { treatment_prescribed }),
    ...(attachment_url && { attachment_url }),
  };

  try {
    const response = await updateMedicalLog(id, data);

    if (response.error) {
      return actionError(response);
    }

    return { success: response.success };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to update user." };
  }
};

export const deleteMedicalLogAction = async (id) => {
  try {
    const response = await deleteMedicalLog(id);

    if (response.error) {
      return { error: response.error };
    }
    
    return { success: response.success };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to delete user." };
  }
};

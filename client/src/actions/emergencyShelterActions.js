"use server";
import {
  getShelters,
  getShelter,
  createShelter,
  deleteShelter
} from "@/libs/api";

export const actionError = async (response) => {
  if (typeof response.error === "object") {
    const errorMessages = {};

    if (response.error.pet_id) {
      errorMessages["pet_id"] = response.error.pet_id;
    }

    if (response.error.request_date) {
      errorMessages["request_date"] = response.error.request_date;
    }

    return { error: errorMessages };
  }

  return { error: { error: response.error } };
};

export const getSheltersAction = async (queryParams = {}) => {
  try {
    const response = await getShelters(queryParams);

    if (response.error) {
      return { error: response.error };
    }

    return {
      data: response,
    };
  } catch (error) {
    console.error(error);
    return { error: error.message || "An unexpected Error occured" };
  }
};

export const getShelterAction = async (id) => {
  try {
    const response = await getShelter(id);

    if (response.error) {
      return { error: response.error };
    }

    return { data: response };
  } catch (error) {
    console.error(error);
    return { error: error.message || "An unexpected Error occured" };
  }
};

export const createShelterAction = async (formData) => {
  const pet_id = formData.get("pet");
  const request_date = formData.get("request_date");

  const data = {
    pet_id,
    request_date,
  };

  try {
    const response = await createShelter(data);

    if (response.error) {
      return actionError(response);
    }

    return { success: response.success };
  } catch (error) {
    console.error(error);
    return { error: error.message || "An unexpected Error occured" };
  }
};


export const deleteShelterAction = async (id) => {
  try {
    const response = await deleteShelter(id);

    if (response.error) {
      return { error: response.error };
    }
    
    return { success: response.success };
  } catch (error) {
    console.error(error);
    return { error: error.message || "An unexpected Error occured" };
  }
};

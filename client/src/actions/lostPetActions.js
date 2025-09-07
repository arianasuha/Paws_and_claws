"use server";
import {
  getLostPets,
  getLostPet,
  createLostPet,
  updateLostPet,
  deleteLostPet,
} from "@/libs/api";

export const actionError = async (response) => {
  if (typeof response.error === "object") {
    const errorMessages = {};

    if (response.error.pet_id) {
      errorMessages["pet"] = response.error.pet_id;
    }

    if (response.error.location) {
      errorMessages["location"] = response.error.location;
    }

    if (response.error.date_lost) {
      errorMessages["date_lost"] = response.error.date_lost;
    }

    if (response.error.status) {
      errorMessages["status"] = response.error.status;
    }

    // Combine messages into a single string with \n between each
    return { error: errorMessages };
  }

  // If it's not an object, return the error as is (string or other type)
  return { error: { error: response.error } };
};

export const getLostPetsAction = async (queryParams = {}) => {
  try {
    const response = await getLostPets(queryParams);

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

export const getLostPetAction = async (id) => {
  try {
    const response = await getLostPet(id);

    if (response.error) {
      return { error: response.error };
    }

    return { data: response };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to fetch user." };
  }
};

export const createLostPetAction = async (formData) => {
  const pet_id = formData["pet"];
  const location = formData["location"];
  const date_lost = formData["date_lost"];
  const status = formData["status"];

  const data = {
    pet_id,
    location,
    date_lost,
    status,
  };

  try {
    const response = await createLostPet(data);

    if (response.error) {
      return actionError(response);
    }

    return { success: response.success };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to create user." };
  }
};

export const updateLostPetAction = async (id, formData) => {
  const location = formData.get("location");
  const date_lost = formData.get("date_lost");
  const status = formData.get("status");

  const data = {
    ...(location && { location }),
    ...(date_lost && { date_lost }),
    ...(status && { status }),
  };

  try {
    const response = await updateLostPet(id, data);

    if (response.error) {
      return actionError(response);
    }

    return { success: response.success };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to update user." };
  }
};

export const deleteLostPetAction = async (id) => {
  try {
    const response = await deleteLostPet(id);

    if (response.error) {
      return { error: response.error };
    }
    
    return { success: response.success };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to delete user." };
  }
};

"use server";
import {
  getPetMarkets,
  getPetMarket,
  createPetMarket,
  updatePetMarket,
  deletePetMarket,
} from "@/libs/api";

export const actionError = async (response) => {
  if (typeof response.error === "object") {
    const errorMessages = {};

    if (response.error.pet_id) {
      errorMessages["pet"] = response.error.pet_id;
    }

    if (response.error.type) {
      errorMessages["type"] = response.error.type;
    }

    if (response.error.description) {
      errorMessages["description"] = response.error.description;
    }

    if (response.error.date) {
      errorMessages["date"] = response.error.date;
    }

    if (response.error.status) {
      errorMessages["status"] = response.error.status;
    }

    if (response.error.fee) {
      errorMessages["fee"] = response.error.fee;
    }

    // Combine messages into a single string with \n between each
    return { error: errorMessages };
  }

  // If it's not an object, return the error as is (string or other type)
  return { error: { error: response.error } };
};

export const getPetMarketsAction = async (queryParams = {}) => {
  try {
    const response = await getPetMarkets(queryParams);

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

export const getPetMarketAction = async (id) => {
  try {
    const response = await getPetMarket(id);

    if (response.error) {
      return { error: response.error };
    }

    return { data: response };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to fetch user." };
  }
};

export const createPetMarketAction = async (formData) => {
  const pet_id = formData["pet"];
  const type = formData["type"];
  const description = formData["description"];
  const fee = formData["fee"];
  const date = formData["date"];

  const data = {
    pet_id,
    type,
    description,
    fee,
    date,
  };

  try {
    const response = await createPetMarket(data);

    if (response.error) {
      return actionError(response);
    }

    return { success: response.success };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to create user." };
  }
};

export const updatePetMarketAction = async (id, formData) => {
  const type = formData.get("type");
  const description = formData.get("description");
  const fee = formData.get("fee");
  const status = formData.get("status");

  const data = {
    ...(type && { type }),
    ...(description && { description }),
    ...(fee && { fee }),
    ...(status && { status }),
  };

  try {
    const response = await updatePetMarket(id, data);

    if (response.error) {
      return actionError(response);
    }

    return { success: response.success };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to update user." };
  }
};

export const deletePetMarketAction = async (id) => {
  try {
    const response = await deletePetMarket(id);

    if (response.error) {
      return { error: response.error };
    }
    
    return { success: response.success };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to delete user." };
  }
};

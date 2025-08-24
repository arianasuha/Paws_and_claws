"use server";
import {
  getPetMarkets,
  getPetMarket,
  createPetMarket,
  updatePetMarket,
  deletePetMarket,
} from "@/libs/api";
import { logoutAction } from "./authActions";

// needs work
export const petActionError = async (response, errorMessages = {}) => {
  if (typeof response.pet === "object") {
    if (response.pet.name) {
      errorMessages["name"] = response.pet.name;
    }

    if (response.pet.species) {
      errorMessages["species"] = response.pet.species;
    }

    if (response.pet.breed) {
      errorMessages["breed"] = response.pet.breed;
    }

    if (response.pet.dob) {
      errorMessages["dob"] = response.pet.dob;
    }

    if (response.pet.height) {
      errorMessages["height"] = response.pet.height;
    }

    if (response.pet.weight) {
      errorMessages["weight"] = response.pet.weight;
    }

    if (response.pet.image_url) {
      errorMessages["image_url"] = response.pet.image_url;
    }

    return errorMessages
  }
};

export const marketActionError = async (response, errorMessages = {}) => {
  if (typeof response.market === "object") {
    if (response.market.type) {
      errorMessages["type"] = response.market.type;
    }

    if (response.market.description) {
      errorMessages["description"] = response.market.description;
    }

    if (response.market.fee) {
      errorMessages["fee"] = response.market.fee;
    }

    return errorMessages
  }
};

export const actionError = async (response) => {
  if (typeof response.error === "object") {
    const errorMessages = {};

    errorMessages = await petActionError(response, errorMessages);
    errorMessages = await marketActionError(response, errorMessages);

    return { error: errorMessages };
  }

  return { error: { error: response.error } };
}

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
  const name = formData["name"];
  const species = formData["species"];
  const breed = formData["breed"];
  const dob = formData["dob"];
  const gender = formData["gender"];
  const weight = formData["weight"];
  const height = formData["height"];
  const image_url = formData["image_url"];

  const type = formData["type"];
  const description = formData["description"];
  const fee = formData["fee"];

  const data = {
    pet: {
      name,
      ...(species && { species }),
      ...(breed && { breed }),
      ...(dob && { dob }),
      ...(gender && { gender }),
      ...(weight && { weight }),
      ...(height && { height }),
      ...(image_url && { image_url }),
    },
    market: {
      type,
      description,
      fee
    }
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
  const type = formData["type"];
  const description = formData["description"];
  const fee = formData["fee"];

  const data = {
    ...(type && { type }),
    ...(description && { description }),
    ...(fee && { fee }),
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
    
    await logoutAction()
    return { success: response.success };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to delete user." };
  }
};

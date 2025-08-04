"use server";
import {
  getPets,
  getPet,
  createPet,
  updatePet,
  deletePet,
} from "@/libs/api";

export const actionError = async (response) => {
  if (typeof response.error === "object") {
    const errorMessages = {};

    if (response.error.name) {
      errorMessages["name"] = response.error.name;
    }

    if (response.error.species) {
      errorMessages["species"] = response.error.species;
    }

    if (response.error.breed) {
      errorMessages["breed"] = response.error.breed;
    }

    if (response.error.dob) {
      errorMessages["dob"] = response.error.dob;
    }

    if (response.error.gender) {
      errorMessages["gender"] = response.error.gender;
    }

    if (response.error.weight) {
      errorMessages["weight"] = response.error.weight;
    }

    if (response.error.height) {
      errorMessages["height"] = response.error.height;
    }

    if (response.error.image_url) {
      errorMessages["image_url"] = response.error.image_url;
    }

    // Combine messages into a single string with \n between each
    return { error: errorMessages };
  }

  // If it's not an object, return the error as is (string or other type)
  return { error: { error: response.error } };
};

export const getPetsAction = async () => {
  try {
    const response = await getPets();

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

export const getPetAction = async (id) => {
  try {
    const response = await getPet(id);

    if (response.error) {
      return { error: response.error };
    }

    return { data: response };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to fetch user." };
  }
};

export const createPetAction = async (formData) => {
  const name = formData.get("name");
  const species = formData.get("species");
  const breed = formData.get("breed");
  const dob = formData.get("dob");
  const gender = formData.get("gender");
  const weight = formData.get("weight");
  const height = formData.get("height");
  const image_url = formData.get("image_url");

  const data = {
    name,
    ...(species && { species }),
    ...(breed && { breed }),
    ...(dob && { dob }),
    ...(gender && { gender }),
    ...(weight && { weight }),
    ...(height && { height }),
    ...(image_url && { image_url }),
  };

  try {
    const response = await createPet(data);

    if (response.error) {
      return actionError(response);
    }

    return { success: response.success };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to create user." };
  }
};

export const updatePetAction = async (id, formData) => {
  const name = formData.get("name");
  const species = formData.get("species");
  const breed = formData.get("breed");
  const dob = formData.get("dob");
  const gender = formData.get("gender");
  const weight = formData.get("weight");
  const height = formData.get("height");
  const image_url = formData.get("image_url");

  const data = {
    name,
    ...(species && { species }),
    ...(breed && { breed }),
    ...(dob && { dob }),
    ...(gender && { gender }),
    ...(weight && { weight }),
    ...(height && { height }),
    ...(image_url && { image_url }),
  };

  try {
    const response = await updatePet(id, data);

    if (response.error) {
      return actionError(response);
    }

    return { success: response.success };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to update user." };
  }
};

export const deletePetAction = async (id) => {
  try {
    const response = await deletePet(id);

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

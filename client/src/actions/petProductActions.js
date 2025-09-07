"use server";
import {
  getPetProducts,
  getPetProduct,
  createPetProduct,
  updatePetProduct,
  deletePetProduct
} from "@/libs/api";

export const actionError = async (response) => {
  if (typeof response.error === "object") {
    const errorMessages = {};

    if (response.error.name) {
      errorMessages["name"] = response.error.name;
    }

    if (response.error.price) {
      errorMessages["price"] = response.error.price;
    }

    if (response.error.description) {
      errorMessages["description"] = response.error.description;
    }

    if (response.error.stock) {
      errorMessages["stock"] = response.error.stock;
    }

    if (response.error.category_id) {
      errorMessages["category"] = response.error.category_id;
    }

    if (response.error.image_url) {
      errorMessages["image"] = response.error.image_url;
    }

    // Combine messages into a single string with \n between each
    return { error: errorMessages };
  }

  // If it's not an object, return the error as is (string or other type)
  return { error: { error: response.error } };
};

export const getPetProductsAction = async (queryParams = {}) => {
  try {
    const response = await getPetProducts(queryParams);

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

export const getPetProductAction = async (id) => {
  try {
    const response = await getPetProduct(id);

    if (response.error) {
      return { error: response.error };
    }

    return { data: response };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to fetch user." };
  }
};

export const createPetProductAction = async (formData) => {
  try {
    const response = await createPetProduct(formData);

    if (response.error) {
      return actionError(response);
    }

    return { success: response.success };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to create user." };
  }
};

export const updatePetProductAction = async (id, formData) => {
  try {
    let data, response;
    let image_url = formData.get("image_url")
    
    if (image_url.size > 0) {
      response = await updatePetProduct(id, formData, true);
    } else {
      const name = formData.get("name");
      const price = formData.get("price");
      const description = formData.get("description");
      const stock = formData.get("stock");
      const category_id = formData.get("category_id");

      data = {
        ...(name && { name }),
        ...(price && { price }),
        ...(description && { description }),
        ...(stock && { stock }),
        ...(category_id && { category_id }),
      };

      response = await updatePetProduct(id, data);
    }

    if (response.error) {
      return actionError(response);
    }

    return { success: response.success };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to update user." };
  }
};

export const deletePetProductAction = async (id) => {
  try {
    const response = await deletePetProduct(id);

    if (response.error) {
      return { error: response.error };
    }
    
    return { success: response.success };
  } catch (error) {
    console.error(error);
    return { error: error.message || "Failed to delete user." };
  }
};

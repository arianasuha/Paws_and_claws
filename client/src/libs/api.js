import { ApiClient } from "./apiClient";

const API_URL = process.env.API_BASE_URL;
const apiClient = new ApiClient(API_URL);

// API functions
export const login = async (data) => {
  return apiClient.post("/login/", data);
};

export const logout = async () => {
  return await apiClient.post("/logout/");
};

export const getUsers = async (queryParams = {}) => {
  const params = new URLSearchParams(queryParams);
  return apiClient.get(`/users/?${params.toString()}`);
};

export const getUser = async (id) => {
  return apiClient.get(`/users/${id}/`);
};

export const createUser = async (data) => {
  return apiClient.post("/users/", data);
};

export const updateUser = async (id, data) => {
  return apiClient.patch(`/users/${id}/`, data);
};

export const deleteUser = async (id) => {
  return apiClient.delete(`/users/${id}/`);
};

export const getVets = async (queryParams = {}) => {
  const params = new URLSearchParams(queryParams);
  return apiClient.get(`/vets/?${params.toString()}`);
};

export const getVet = async (id) => {
  return apiClient.get(`/vets/${id}/`);
};

export const createVet = async (data) => {
  return apiClient.post("/vets/", data);
};

export const updateVet = async (id, data) => {
  return apiClient.patch(`/vets/${id}/`, data);
};

export const deleteVet = async (id) => {
  return apiClient.delete(`/vets/${id}/`);
};

export const getServiceProviders = async (queryparams = {}) => {
  const params = new URLSearchParams(queryparams);
  return apiClient.get(`/service-providers/?${params.toString()}`);
};

export const getServiceProvider = async (id) => {
  return apiClient.get(`/service-providers/${id}/`);
};

export const createServiceProvider = async (data) => {
  return apiClient.post("/service-providers/", data);
};

export const updateServiceProvider = async (id, data) => {
  return apiClient.patch(`/service-providers/${id}/`, data);
};

export const deleteServiceProvider = async (id) => {
  return apiClient.delete(`/service-providers/${id}/`);
};

export const getCategories = async () => {
  return apiClient.get(`/categories/`);
};

export const getCategory = async (id) => {
  return apiClient.get(`/categories/${id}/`);
};

export const createCategory = async (data) => {
  return apiClient.post("/categories/", data);
};

export const deleteCategory = async (id) => {
  return apiClient.delete(`/categories/${id}/`);
};

export const getPets = async (queryParams = {}) => {
  const params = new URLSearchParams(queryParams);
  return apiClient.get(`/pets/?${params.toString()}`);
};

export const getPet = async (id) => {
  return apiClient.get(`/pets/${id}/`);
};

export const createPet = async (data) => {
  return apiClient.post("/pets/", data, {}, true);
};

export const updatePet = async (id, data, isImage) => {
  if (isImage) {
    data.append('_method', 'PATCH');
    return apiClient.post(`/pets/${id}/`, data, {}, true);
  }
  return apiClient.patch(`/pets/${id}/`, data);
};

export const deletePet = async (id) => {
  return apiClient.delete(`/pets/${id}/`);
};

export const getMedicalLogs = async (pet_id, queryParams = {}) => {
  const params = new URLSearchParams(queryParams);
  return apiClient.get(`/medicalpet-logs/${pet_id}/?${params.toString()}`);
};

export const getMedicalLog = async (id) => {
  return apiClient.get(`/medical-logs/${id}/`);
};

export const createMedicalLog = async (data) => {
  return apiClient.post("/medical-logs/", data);
};

export const updateMedicalLog = async (id, data) => {
  return apiClient.patch(`/medical-logs/${id}/`, data);
};

export const deleteMedicalLog = async (id) => {
  return apiClient.delete(`/medical-logs/${id}/`);
};

export const getPetMarkets = async (queryParams = {}) => {
  const params = new URLSearchParams(queryParams);
  return apiClient.get(`/pet-markets/?${params.toString()}`);
};

export const getPetMarket = async (pet_id) => {
  return apiClient.get(`/pet-markets/${pet_id}/`);
};

export const createPetMarket = async (data) => {
  return apiClient.post("/pet-markets/", data);
};

export const updatePetMarket = async (id, data) => {
  return apiClient.patch(`/pet-markets/${id}/`, data);
};

export const deletePetMarket = async (id) => {
  return apiClient.delete(`/pet-markets/${id}/`);
};

export const getAppointments = async (queryParams = {}) => {
  const params = new URLSearchParams(queryParams);
  return apiClient.get(`/appointments/?${params.toString()}`);
};

export const getAppointment = async (id) => {
  return apiClient.get(`/appointments/${id}/`);
};

export const createAppointment = async (data) => {
  return apiClient.post("/appointments/", data);
};

export const updateAppointment = async (id, data) => {
  return apiClient.patch(`/appointments/${id}/`, data);
};

export const deleteAppointment = async (id) => {
  return apiClient.delete(`/appointments/${id}/`);
};

export const getPetProducts = async (queryParams = {}) => {
  const params = new URLSearchParams(queryParams);
  return apiClient.get(`/pet-products/?${params.toString()}`);
};

export const getPetProduct = async (id) => {
  return apiClient.get(`/pet-products/${id}/`);
};

export const createPetProduct = async (data) => {
  return apiClient.post("/pet-products/", data, {}, true);
};

export const updatePetProduct = async (id, data, isImage) => {
  if (isImage) {
    data.append('_method', 'PATCH');
    return apiClient.post(`/pet-products/${id}/`, data, {}, true);
  }
  return apiClient.patch(`/pet-products/${id}/`, data, {}, isImage);
};

export const deletePetProduct = async (id) => {
  return apiClient.delete(`/pet-products/${id}/`);
};

export const getCartItems = async (user_id) => {
  return apiClient.get(`/cart-items/${user_id}/`);
};

export const updateCartItems = async (cart_id, data) => {
  return apiClient.put(`/cart-items/${cart_id}/`, data);
};

export const deleteCartItem = async (cart_id) => {
  return apiClient.delete(`/cart-items/${cart_id}/`);
};

export const getOrders = async (queryParams = {}) => {
  const params = new URLSearchParams(queryParams);
  return apiClient.get(`/orders/?${params.toString()}`);
};

export const getOrder = async (id) => {
  return apiClient.get(`/orders/${id}/`);
};

export const createOrder = async (data) => {
  return apiClient.post("/orders/", data);
};

export const updateOrder = async (id, data) => {
  return apiClient.patch(`/orders/${id}/`, data);
};

export const deleteOrder = async (id) => {
  return apiClient.delete(`/orders/${id}/`);
};

export const getPayments = async (queryParams = {}) => {
  const params = new URLSearchParams(queryParams);
  return apiClient.get(`/payments/?${params.toString()}`);
};

export const getPayment = async (id) => {
  return apiClient.get(`/payments/${id}/`);
};

export const createPayment = async (data) => {
  return apiClient.post("/payments/", data);
};

export const deletePayment = async (id) => {
  return apiClient.delete(`/payments/${id}/`);
};

export const getLostPets = async (queryParams = {}) => {
  const params = new URLSearchParams(queryParams);
  return apiClient.get(`/reports/lost-pets/?${params.toString()}`);
};

export const getLostPet = async (id) => {
  return apiClient.get(`/reports/lost-pets/${id}/`);
};

export const createLostPet = async (data) => {
  return apiClient.post("/reports/lost-pets/", data);
};

export const updateLostPet = async (id, data) => {
  return apiClient.patch(`/reports/lost-pets/${id}/`, data);
};

export const deleteLostPet = async (id) => {
  return apiClient.delete(`/reports/lost-pets/${id}/`);
};

export const getShelters = async (queryParams = {}) => {
  const params = new URLSearchParams(queryParams);
  return apiClient.get(`/shelters/?${params.toString()}`);
};

export const getShelter = async (id) => {
  return apiClient.get(`/shelters/${id}/`);
};

export const createShelter = async (data) => {
  return apiClient.post("/shelters/", data);
};

export const deleteShelter = async (id) => {
  return apiClient.delete(`/shelters/${id}/`);
};

export const getNotifications = async (queryParams = {}) => {
  const params = new URLSearchParams(queryParams);
  return apiClient.get(`/notifications/?${params.toString()}`);
};

export const getAvailableNotification = async() => {
  return apiClient.get(`/notifications/available/`);
}

export const getNotification = async (id) => {
  return apiClient.get(`/notifications/${id}/`);
};

export const updateNotification = async (id) => {
  return apiClient.put(`/notifications/${id}/`, {});
};

export const deleteNotification = async (id) => {
  return apiClient.delete(`/notifications/${id}/`);
};

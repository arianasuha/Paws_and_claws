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

export const getUsers = async () => {
  return apiClient.get(`/users/`);
};

export const getUser = async (id) => {
  return apiClient.get(`/users/${id}/`);
};

export const createUser = async (data) => {
  return apiClient.post("/users/", data);
};

export const createAdminUser = async (data) => {
  return apiClient.post("/admin/users/", data);
};

export const updateUser = async (id, data) => {
  return apiClient.put(`/users/${id}/`, data);
};

export const deleteUser = async (id) => {
  return apiClient.delete(`/users/${id}/`);
};

export const getPets = async () => {
  return apiClient.get(`/pets/`);
};

export const getPet = async (id) => {
  return apiClient.get(`/pets/${id}/`);
};

export const createPet = async (data) => {
  return apiClient.post("/pets/", data);
};

export const updatePet = async (id, data) => {
  return apiClient.put(`/pets/${id}/`, data);
};

export const deletePet = async (id) => {
  return apiClient.delete(`/pets/${id}/`);
};

export const getVets = async () => {
  return apiClient.get(`/vets/`);
};

export const getVet = async (id) => {
  return apiClient.get(`/vets/${id}/`);
};

export const createVet = async (data) => {
  return apiClient.post("/vets/", data);
};

export const updateVet = async (id, data) => {
  return apiClient.put(`/vets/${id}/`, data);
};

export const deleteVet = async (id) => {
  return apiClient.delete(`/vets/${id}/`);
};
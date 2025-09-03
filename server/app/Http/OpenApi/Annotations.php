<?php

namespace App\Http\OpenApi;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 * version="1.0.0",
 * title="Paws and Claws API",
 * description="API documentation for the Paws and Claws project",
 * @OA\Contact(
 * email="support@example.com"
 * )
 * )
 *
 * @OA\Server(
 * url="http://127.0.0.1:8000",
 * description="Local API Server"
 * )
 *
 * @OA\SecurityScheme(
 * securityScheme="sanctum",
 * type="http",
 * scheme="bearer"
 * )
 *
 * @OA\Tag(
 * name="Users",
 * description="API Endpoints of Users"
 * )
 *
 * @OA\Tag(
 * name="Pets",
 * description="API Endpoints of Pets"
 * )
 *
 * @OA\Tag(
 * name="Vets",
 * description="API Endpoints of Vets"
 * )
 *
 * @OA\Tag(
 * name="PetMarket",
 * description="API Endpoints of Pet Market"
 * )
 *
 * @OA\Tag(
 * name="Appointments",
 * description="API Endpoints of Appointments"
 * )
 *
 * @OA\Tag(
 * name="Service Providers",
 * description="API Endpoints for Service Providers"
 * )
 *
 * @OA\Tag(
 * name="Notifications",
 * description="API Endpoints for Notifications"
 * )
 *
 * @OA\Tag(
 * name="Lost Pets",
 * description="API Endpoints for reporting and managing lost pets"
 * )
 *
 * @OA\Tag(
 * name="Emergency Shelters",
 * description="API Endpoints for managing emergency pet shelter requests"
 * )
 *
 * @OA\Tag(
 * name="Cart",
 * description="API Endpoints for Cart Management"
 * )
 *
 * @OA\Tag(
 * name="Orders",
 * description="API Endpoints for Orders Management"
 * )
 * 
 * @OA\Tag(
 * name="Payments",
 * description="API Endpoints for Payment Management"
 * )
 *
 * @OA\Tag(
 * name="PetProducts",
 * description="API Endpoints for managing pet products"
 * )
 *
 * @OA\Tag(
 * name="Categories",
 * description="API Endpoints for managing product categories"
 * )
 *
 * @OA\Schema(
 * schema="ErrorResponse",
 * title="Error Response",
 * description="Standard error response format for generic errors (e.g., 401, 403, 404, 500)",
 * @OA\Property(property="errors", type="string", description="Error message"),
 * example={"errors": "Something went wrong."}
 * )
 *
 * @OA\Schema(
 * schema="SuccessResponse",
 * title="Success Response",
 * description="Standard success response format for updates and deletes",
 * @OA\Property(property="success", type="string", description="Success message"),
 * example={"success": "Operation successful."}
 * )
 *
 * @OA\Schema(
 * schema="User",
 * title="User",
 * description="User model",
 * @OA\Property(property="id", type="integer", format="int64", description="User ID"),
 * @OA\Property(property="first_name", type="string", nullable=true, description="First name of the user"),
 * @OA\Property(property="last_name", type="string", nullable=true, description="Last name of the user"),
 * @OA\Property(property="email", type="string", format="email", description="Email address of the user"),
 * @OA\Property(property="username", type="string", description="Username of the user"),
 * @OA\Property(property="address", type="string", nullable=true, description="Address of the user"),
 * @OA\Property(property="is_admin", type="boolean", description="Is the user an admin?"),
 * @OA\Property(property="is_active", type="boolean", description="Is the user active?"),
 * @OA\Property(property="is_vet", type="boolean", description="Is the user a veterinarian?"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp")
 * )
 *
 * @OA\Schema(
 * schema="UserPaginatedResponse",
 * title="UserPaginatedResponse",
 * description="Paginated list of users",
 * @OA\Property(property="current_page", type="integer"),
 * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/User")),
 * @OA\Property(property="first_page_url", type="string"),
 * @OA\Property(property="from", type="integer"),
 * @OA\Property(property="last_page", type="integer"),
 * @OA\Property(property="last_page_url", type="string"),
 * @OA\Property(property="links", type="array", @OA\Items(@OA\Property(property="url", type="string", nullable=true),@OA\Property(property="label", type="string"),@OA\Property(property="active", type="boolean"))),
 * @OA\Property(property="next_page_url", type="string", nullable=true),
 * @OA\Property(property="path", type="string"),
 * @OA\Property(property="per_page", type="integer"),
 * @OA\Property(property="prev_page_url", type="string", nullable=true),
 * @OA\Property(property="to", type="integer"),
 * @OA\Property(property="total", type="integer")
 * )
 *
 * @OA\Schema(
 * schema="RegisterUserRequest",
 * title="RegisterUserRequest",
 * description="User registration request payload",
 * required={"email", "username", "password"},
 * @OA\Property(property="first_name", type="string", description="First name of the user", nullable=true),
 * @OA\Property(property="last_name", type="string", description="Last name of the user", nullable=true),
 * @OA\Property(property="email", type="string", format="email", description="Email address of the user"),
 * @OA\Property(property="username", type="string", description="Username of the user"),
 * @OA\Property(property="password", type="string", format="password", description="Password (min: 8 characters, confirmed)"),
 * @OA\Property(property="password_confirmation", type="string", format="password", description="Password confirmation"),
 * @OA\Property(property="address", type="string", description="Address of the user", nullable=true)
 * )
 *
 * @OA\Schema(
 * schema="UpdateUserRequest",
 * title="UpdateUserRequest",
 * description="User update request payload",
 * @OA\Property(property="first_name", type="string", description="First name of the user", nullable=true),
 * @OA\Property(property="last_name", type="string", description="Last name of the user", nullable=true),
 * @OA\Property(property="email", type="string", format="email", description="Email address of the user", nullable=true),
 * @OA\Property(property="username", type="string", description="Username of the user", nullable=true),
 * @OA\Property(property="password", type="string", format="password", description="Password (min: 8 characters, confirmed)", nullable=true),
 * @OA\Property(property="password_confirmation", type="string", format="password", nullable=true, description="Password confirmation"),
 * @OA\Property(property="address", type="string", nullable=true, description="Address of the user", nullable=true)
 * )
 *
 * @OA\Schema(
 * schema="Pet",
 * title="Pet",
 * description="Pet model",
 * @OA\Property(property="id", type="integer", format="int64", description="Pet ID"),
 * @OA\Property(property="user_id", type="integer", format="int64", description="ID of the pet's owner"),
 * @OA\Property(property="name", type="string", description="Name of the pet"),
 * @OA\Property(property="gender", type="string", enum={"male", "female"}, description="Gender of the pet"),
 * @OA\Property(property="species", type="string", description="Species of the pet"),
 * @OA\Property(property="breed", type="string", nullable=true, description="Breed of the pet"),
 * @OA\Property(property="dob", type="string", format="date", description="Date of birth of the pet"),
 * @OA\Property(property="image_url", type="string", nullable=true, description="URL to the pet's profile image"),
 * @OA\Property(property="height", type="number", format="float", nullable=true, description="Height of the pet in cm"),
 * @OA\Property(property="weight", type="number", format="float", nullable=true, description="Weight of the pet in kg"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp"),
 * )
 *
 * @OA\Schema(
 * schema="PetPaginatedResponse",
 * title="PetPaginatedResponse",
 * description="Paginated list of pets",
 * @OA\Property(property="current_page", type="integer"),
 * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Pet")),
 * @OA\Property(property="first_page_url", type="string"),
 * @OA\Property(property="from", type="integer"),
 * @OA\Property(property="last_page", type="integer"),
 * @OA\Property(property="last_page_url", type="string"),
 * @OA\Property(
 * property="links",
 * type="array",
 * @OA\Items(
 * @OA\Property(property="url", type="string", nullable=true),
 * @OA\Property(property="label", type="string"),
 * @OA\Property(property="active", type="boolean")
 * )
 * ),
 * @OA\Property(property="next_page_url", type="string", nullable=true),
 * @OA\Property(property="path", type="string"),
 * @OA\Property(property="per_page", type="integer"),
 * @OA\Property(property="prev_page_url", type="string", nullable=true),
 * @OA\Property(property="to", type="integer"),
 * @OA\Property(property="total", type="integer"),
 * )
 *
 * @OA\Schema(
 * schema="PetRegisterRequest",
 * title="PetRegisterRequest",
 * description="Pet registration payload",
 * required={"name", "gender", "species", "dob"},
 * @OA\Property(property="name", type="string", description="Name of the pet"),
 * @OA\Property(property="gender", type="string", enum={"male", "female"}, description="Gender of the pet"),
 * @OA\Property(property="species", type="string", description="Species of the pet"),
 * @OA\Property(property="breed", type="string", nullable=true, description="Breed of the pet"),
 * @OA\Property(property="dob", type="string", format="date", description="Date of birth of the pet"),
 * @OA\Property(property="image_url", type="string", format="binary", nullable=true, description="Pet's profile image file"),
 * @OA\Property(property="height", type="number", format="float", nullable=true, description="Height of the pet in cm"),
 * @OA\Property(property="weight", type="number", format="float", nullable=true, description="Weight of the pet in kg"),
 * )
 *
 * @OA\Schema(
 * schema="PetUpdateRequest",
 * title="PetUpdateRequest",
 * description="Pet update payload",
 * @OA\Property(property="name", type="string", nullable=true, description="Name of the pet"),
 * @OA\Property(property="gender", type="string", nullable=true, enum={"male", "female"}, description="Gender of the pet"),
 * @OA\Property(property="species", type="string", nullable=true, description="Species of the pet"),
 * @OA\Property(property="breed", type="string", nullable=true, description="Breed of the pet"),
 * @OA\Property(property="dob", type="string", format="date", nullable=true, description="Date of birth of the pet"),
 * @OA\Property(property="image_url", type="string", format="binary", nullable=true, description="Pet's profile image file"),
 * @OA\Property(property="height", type="number", format="float", nullable=true, description="Height of the pet in cm"),
 * @OA\Property(property="weight", type="number", format="float", nullable=true, description="Weight of the pet in kg"),
 * )
 *
 * @OA\Schema(
 * schema="Vet",
 * title="Vet",
 * description="Vet model",
 * @OA\Property(property="id", type="integer", format="int64", description="Vet ID"),
 * @OA\Property(property="user_id", type="integer", format="int64", description="ID of the associated user"),
 * @OA\Property(property="clinic_name", type="string", description="Name of the vet's clinic"),
 * @OA\Property(property="specialization", type="string", description="Specialization of the vet"),
 * @OA\Property(property="services_offered", type="string", nullable=true, description="Description of services offered"),
 * @OA\Property(property="working_hour", type="string", nullable=true, description="Working hours of the clinic"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp"),
 * @OA\Property(property="user", ref="#/components/schemas/User", description="Associated user information")
 * )
 *
 * @OA\Schema(
 * schema="VetPaginatedResponse",
 * title="VetPaginatedResponse",
 * description="Paginated list of vets",
 * @OA\Property(property="current_page", type="integer"),
 * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Vet")),
 * @OA\Property(property="first_page_url", type="string"),
 * @OA\Property(property="from", type="integer"),
 * @OA\Property(property="last_page", type="integer"),
 * @OA\Property(property="last_page_url", type="string"),
 * @OA\Property(
 * property="links",
 * type="array",
 * @OA\Items(
 * @OA\Property(property="url", type="string", nullable=true),
 * @OA\Property(property="label", type="string"),
 * @OA\Property(property="active", type="boolean")
 * )
 * ),
 * @OA\Property(property="next_page_url", type="string", nullable=true),
 * @OA\Property(property="path", type="string"),
 * @OA\Property(property="per_page", type="integer"),
 * @OA\Property(property="prev_page_url", type="string", nullable=true),
 * @OA\Property(property="to", type="integer"),
 * @OA\Property(property="total", type="integer")
 * )
 *
 * @OA\Schema(
 * schema="VetRegisterRequest",
 * title="VetRegisterRequest",
 * description="Vet registration payload",
 * required={"first_name", "last_name", "email", "username", "password", "clinic_name", "specialization"},
 * @OA\Property(property="first_name", type="string", description="First name of the vet"),
 * @OA\Property(property="last_name", type="string", description="Last name of the vet"),
 * @OA\Property(property="email", type="string", format="email", description="Email address of the vet"),
 * @OA\Property(property="username", type="string", description="Username of the vet"),
 * @OA\Property(property="password", type="string", format="password", description="Password (min: 8 characters, confirmed)"),
 * @OA\Property(property="password_confirmation", type="string", format="password", description="Password confirmation"),
 * @OA\Property(property="address", type="string", nullable=true, description="Address of the vet"),
 * @OA\Property(property="clinic_name", type="string", description="Name of the clinic"),
 * @OA\Property(property="specialization", type="string", description="Specialization of the vet"),
 * @OA\Property(property="services_offered", type="string", nullable=true, description="Description of services offered"),
 * @OA\Property(property="working_hour", type="string", nullable=true, description="Working hours of the clinic")
 * )
 *
 * @OA\Schema(
 * schema="VetUpdateRequest",
 * title="VetUpdateRequest",
 * description="Vet update payload",
 * @OA\Property(property="first_name", type="string", nullable=true, description="First name of the vet"),
 * @OA\Property(property="last_name", type="string", nullable=true, description="Last name of the vet"),
 * @OA\Property(property="email", type="string", format="email", nullable=true, description="Email address of the vet"),
 * @OA\Property(property="username", type="string", nullable=true, description="Username of the vet"),
 * @OA\Property(property="password", type="string", format="password", nullable=true, description="Password (min: 8 characters, confirmed)"),
 * @OA\Property(property="password_confirmation", type="string", format="password", nullable=true, description="Password confirmation"),
 * @OA\Property(property="address", type="string", nullable=true, description="Address of the vet"),
 * @OA\Property(property="clinic_name", type="string", nullable=true, description="Name of the clinic"),
 * @OA\Property(property="specialization", type="string", nullable=true, description="Specialization of the vet"),
 * @OA\Property(property="services_offered", type="string", nullable=true, description="Description of services offered"),
 * @OA\Property(property="working_hour", type="string", nullable=true, description="Working hours of the clinic")
 * )
 *
 * @OA\Schema(
 * schema="PetMarket",
 * title="PetMarket",
 * description="Pet Market model",
 * @OA\Property(property="id", type="integer", format="int64", description="Pet Market ID"),
 * @OA\Property(property="user_id", type="integer", format="int64", description="ID of the user selling the pet"),
 * @OA\Property(property="pet_id", type="integer", format="int64", description="ID of the pet being sold"),
 * @OA\Property(property="date", type="string", format="date", description="Date of the listing"),
 * @OA\Property(property="type", type="string", description="Type of listing (e.g., sale, adoption)"),
 * @OA\Property(property="status", type="string", description="Status of the pet listing"),
 * @OA\Property(property="description", type="string", nullable=true, description="Description of the pet for sale"),
 * @OA\Property(property="fee", type="number", format="float", nullable=true, description="Fee associated with the listing"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp"),
 * @OA\Property(property="pet", ref="#/components/schemas/Pet", description="Associated pet information"),
 * @OA\Property(property="user", ref="#/components/schemas/User", description="Associated user information")
 * )
 *
 * @OA\Schema(
 * schema="PetMarketPaginatedResponse",
 * title="PetMarketPaginatedResponse",
 * description="Paginated list of pet market entries",
 * @OA\Property(property="current_page", type="integer"),
 * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/PetMarket")),
 * @OA\Property(property="first_page_url", type="string"),
 * @OA\Property(property="from", type="integer"),
 * @OA\Property(property="last_page", type="integer"),
 * @OA\Property(property="last_page_url", type="string"),
 * @OA\Property(
 * property="links",
 * type="array",
 * @OA\Items(
 * @OA\Property(property="url", type="string", nullable=true),
 * @OA\Property(property="label", type="string"),
 * @OA\Property(property="active", type="boolean")
 * )
 * ),
 * @OA\Property(property="next_page_url", type="string", nullable=true),
 * @OA\Property(property="path", type="string"),
 * @OA\Property(property="per_page", type="integer"),
 * @OA\Property(property="prev_page_url", type="string", nullable=true),
 * @OA\Property(property="to", type="integer"),
 * @OA\Property(property="total", type="integer")
 * )
 *
 * @OA\Schema(
 * schema="PetMarketRegisterRequest",
 * title="PetMarketRegisterRequest",
 * description="Pet market registration payload for an existing pet",
 * required={"pet_id", "date", "type", "status"},
 * @OA\Property(
 * property="pet_id",
 * type="integer",
 * description="ID of the pet for this listing"
 * ),
 * @OA\Property(
 * property="date",
 * type="string",
 * format="date",
 * description="Date of the listing"
 * ),
 * @OA\Property(
 * property="type",
 * type="string",
 * enum={"sale", "adoption"},
 * description="Type of listing"
 * ),
 * @OA\Property(
 * property="description",
 * type="string",
 * nullable=true,
 * description="Description of the listing"
 * ),
 * @OA\Property(
 * property="fee",
 * type="number",
 * format="float",
 * nullable=true,
 * description="Fee associated with the listing"
 * )
 * )
 *
 * @OA\Schema(
 * schema="PetMarketUpdateRequest",
 * title="PetMarketUpdateRequest",
 * description="Pet market update payload",
 * @OA\Property(
 * property="_method",
 * type="string",
 * example="PUT",
 * description="Method spoofing for PUT/PATCH requests with multipart/form-data"
 * ),
 * @OA\Property(property="type", type="string", nullable=true, enum={"sale", "adoption"}, description="Type of listing"),
 * @OA\Property(property="description", type="string", nullable=true, description="Description of the listing"),
 * @OA\Property(property="fee", type="number", format="float", nullable=true, description="Fee associated with the listing"),
 * @OA\Property(property="image_url", type="string", format="binary", nullable=true, description="Image file of the pet")
 * )
 *
 * @OA\Schema(
 * schema="Appointment",
 * title="Appointment",
 * description="Appointment model",
 * @OA\Property(property="id", type="integer", format="int64", description="Appointment ID"),
 * @OA\Property(property="user_id", type="integer", format="int64", description="ID of the user who made the appointment"),
 * @OA\Property(property="provider_id", type="integer", format="int64", description="ID of the service provider or vet for the appointment"),
 * @OA\Property(property="pet_id", type="integer", format="int64", description="ID of the pet for the appointment"),
 * @OA\Property(property="app_date", type="string", format="date", description="Date of the appointment"),
 * @OA\Property(property="app_time", type="string", format="time", description="Time of the appointment"),
 * @OA\Property(property="visit_reason", type="string", description="Reason for the visit"),
 * @OA\Property(property="status", type="string", enum={"pending", "accepted", "completed", "canceled"}, description="Status of the appointment"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp"),
 * )
 *
 * @OA\Schema(
 * schema="AppointmentRegisterRequest",
 * title="AppointmentRegisterRequest",
 * description="Appointment registration payload",
 * required={"pet_id", "provider_id", "app_date", "app_time", "visit_reason"},
 * @OA\Property(property="pet_id", type="integer", description="ID of the pet for the appointment"),
 * @OA\Property(property="provider_id", type="integer", description="ID of the service provider or vet for the appointment"),
 * @OA\Property(property="app_date", type="string", format="date", description="Date of the appointment (YYYY-MM-DD)"),
 * @OA\Property(property="app_time", type="string", format="time", description="Time of the appointment (HH:MM:SS)"),
 * @OA\Property(property="visit_reason", type="string", description="Reason for the visit"),
 * )
 *
 * @OA\Schema(
 * schema="AppointmentUpdateRequest",
 * title="AppointmentUpdateRequest",
 * description="Appointment update payload",
 * @OA\Property(property="status", type="string", nullable=true, enum={"pending", "accepted", "completed", "canceled"}, description="Status of the appointment"),
 * )
 *
 * @OA\Schema(
 * schema="ServiceProvider",
 * title="ServiceProvider",
 * description="Service Provider model",
 * @OA\Property(property="id", type="integer", format="int64", description="Service Provider ID"),
 * @OA\Property(property="user_id", type="integer", format="int64", description="ID of the associated user"),
 * @OA\Property(property="service_type", type="string", enum={"walker", "groomer", "trainer"}, description="Type of service"),
 * @OA\Property(property="service_desc", type="string", nullable=true, description="Description of the service provided"),
 * @OA\Property(property="rate_per_hour", type="number", format="float", description="Rate charged per hour"),
 * @OA\Property(property="rating", type="number", format="float", nullable=true, description="Rating of the service provider (0.0 to 5.0)"),
 * @OA\Property(property="user", ref="#/components/schemas/User", description="Associated user information")
 * )
 *
 * @OA\Schema(
 * schema="ServiceProviderRegisterRequest",
 * title="ServiceProviderRegisterRequest",
 * description="Service Provider registration payload",
 * required={"first_name", "last_name", "email", "username", "password", "service_type", "rate_per_hour"},
 * @OA\Property(property="first_name", type="string", description="First name of the user"),
 * @OA\Property(property="last_name", type="string", description="Last name of the user"),
 * @OA\Property(property="email", type="string", format="email", description="Email address of the user"),
 * @OA\Property(property="username", type="string", description="Username of the user"),
 * @OA\Property(property="password", type="string", format="password", description="Password (min: 8 characters, confirmed)"),
 * @OA\Property(property="password_confirmation", type="string", format="password", description="Password confirmation"),
 * @OA\Property(property="address", type="string", nullable=true, description="Address of the user"),
 * @OA\Property(property="service_type", type="string", enum={"walker", "groomer", "trainer"}, description="Type of service"),
 * @OA\Property(property="service_desc", type="string", nullable=true, description="Description of the service provided"),
 * @OA\Property(property="rate_per_hour", type="number", format="float", description="Rate charged per hour"),
 * )
 *
 * @OA\Schema(
 * schema="ServiceProviderUpdateRequest",
 * title="ServiceProviderUpdateRequest",
 * description="Service Provider update payload",
 * @OA\Property(property="first_name", type="string", nullable=true, description="First name of the user"),
 * @OA\Property(property="last_name", type="string", nullable=true, description="Last name of the user"),
 * @OA\Property(property="email", type="string", format="email", nullable=true, description="Email address of the user"),
 * @OA\Property(property="username", type="string", nullable=true, description="Username of the user"),
 * @OA\Property(property="password", type="string", format="password", nullable=true, description="Password (min: 8 characters, confirmed)"),
 * @OA\Property(property="password_confirmation", type="string", format="password", nullable=true, description="Password confirmation"),
 * @OA\Property(property="address", type="string", nullable=true, description="Address of the user"),
 * @OA\Property(property="service_type", type="string", nullable=true, enum={"walker", "groomer", "trainer"}, description="Type of service"),
 * @OA\Property(property="service_desc", type="string", nullable=true, description="Description of the service provided"),
 * @OA\Property(property="rate_per_hour", type="number", format="float", nullable=true, description="Rate charged per hour"),
 * @OA\Property(property="rating", type="number", format="float", nullable=true, description="Rating of the service provider (0.0 to 5.0)"),
 * )
 *
 * @OA\Schema(
 * schema="Notification",
 * title="Notification",
 * description="Notification model",
 * @OA\Property(property="id", type="integer", format="int64", description="Notification ID"),
 * @OA\Property(property="user_id", type="integer", format="int64", description="ID of the user the notification belongs to"),
 * @OA\Property(property="message", type="string", description="The notification message"),
 * @OA\Property(property="type", type="string", enum={"info", "problem", "proposal", "project", "transaction", "review"}, description="The type of notification"),
 * @OA\Property(property="link", type="string", nullable=true, description="URL or link related to the notification"),
 * @OA\Property(property="is_read", type="boolean", description="Indicates if the notification has been read"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the notification was created"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp of last update"),
 * example={
 * "id": 1, "user_id": 1, "message": "Your proposal has been accepted.",
 * "type": "proposal", "link": "/proposals/123", "is_read": false,
 * "created_at": "2025-08-16T12:00:00.000000Z", "updated_at": "2025-08-16T12:00:00.000000Z"
 * }
 * )
 *
 * @OA\Schema(
 * schema="NotificationPagination",
 * title="Notification Pagination",
 * description="Paginated list of notifications",
 * @OA\Property(property="current_page", type="integer", example=1),
 * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Notification")),
 * @OA\Property(property="first_page_url", type="string", example="http://localhost:8000/api/notifications?page=1"),
 * @OA\Property(property="from", type="integer", example=1),
 * @OA\Property(property="last_page", type="integer", example=2),
 * @OA\Property(property="last_page_url", type="string", example="http://localhost:8000/api/notifications?page=2"),
 * @OA\Property(
 * property="links",
 * type="array",
 * @OA\Items(
 * @OA\Property(property="url", type="string", nullable=true, example="http://localhost:8000/api/notifications?page=1"),
 * @OA\Property(property="label", type="string", example="&laquo; Previous"),
 * @OA\Property(property="active", type="boolean", example=true)
 * )
 * ),
 * @OA\Property(property="next_page_url", type="string", nullable=true, example="http://localhost:8000/api/notifications?page=2"),
 * @OA\Property(property="path", type="string", example="http://localhost:8000/api/notifications"),
 * @OA\Property(property="per_page", type="integer", example=10),
 * @OA\Property(property="prev_page_url", type="string", nullable=true, example=null),
 * @OA\Property(property="to", type="integer", example=10),
 * @OA\Property(property="total", type="integer", example=14)
 * )
 *
 * @OA\Schema(
 * schema="ReportLostPet",
 * title="ReportLostPet",
 * description="Lost pet report model",
 * @OA\Property(property="id", type="integer", format="int64", description="Report ID"),
 * @OA\Property(property="user_id", type="integer", format="int64", description="ID of the user who reported the pet"),
 * @OA\Property(property="pet_id", type="integer", format="int64", description="ID of the lost pet"),
 * @OA\Property(property="location", type="string", description="Last known location of the pet"),
 * @OA\Property(property="date_lost", type="string", format="date", description="Date the pet was lost"),
 * @OA\Property(property="status", type="string", enum={"lost", "found"}, description="Status of the report"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp"),
 * @OA\Property(property="user", ref="#/components/schemas/User", description="Associated user information"),
 * @OA\Property(property="pet", ref="#/components/schemas/Pet", description="Associated pet information")
 * )
 *
 * @OA\Schema(
 * schema="ReportLostPetRegisterRequest",
 * title="ReportLostPetRegisterRequest",
 * description="Lost pet report registration payload",
 * required={"pet_id", "location", "date_lost", "status"},
 * @OA\Property(property="pet_id", type="integer", description="ID of the lost pet"),
 * @OA\Property(property="location", type="string", description="Last known location of the pet"),
 * @OA\Property(property="date_lost", type="string", format="date", description="Date the pet was lost (YYYY-MM-DD)"),
 * @OA\Property(property="status", type="string", enum={"missing", "found"}, description="Status of the report"),
 * )
 *
 * @OA\Schema(
 * schema="ReportLostPetUpdateRequest",
 * title="ReportLostPetUpdateRequest",
 * description="Lost pet report update payload",
 * @OA\Property(property="location", type="string", nullable=true, description="Last known location of the pet"),
 * @OA\Property(property="date_lost", type="string", format="date", nullable=true, description="Date the pet was lost (YYYY-MM-DD)"),
 * @OA\Property(property="status", type="string", nullable=true, enum={"missing", "found"}, description="Status of the report"),
 * )
 *
 * @OA\Schema(
 * schema="Category",
 * title="Category",
 * description="Category model",
 * @OA\Property(
 * property="id",
 * type="integer",
 * format="int64",
 * description="Category ID"
 * ),
 * @OA\Property(
 * property="name",
 * type="string",
 * description="Name of the category"
 * ),
 * @OA\Property(
 * property="created_at",
 * type="string",
 * format="date-time",
 * description="Creation timestamp"
 * ),
 * @OA\Property(
 * property="updated_at",
 * type="string",
 * format="date-time",
 * description="Last update timestamp"
 * )
 * )
 *
 * @OA\Schema(
 * schema="CategoryRegisterRequest",
 * title="CategoryRegisterRequest",
 * description="Category registration payload",
 * required={"name"},
 * @OA\Property(
 * property="name",
 * type="string",
 * description="Name of the category"
 * )
 * )
 *
 * @OA\Schema(
 * schema="CategoryUpdateRequest",
 * title="CategoryUpdateRequest",
 * description="Category update payload",
 * required={"name"},
 * @OA\Property(
 * property="name",
 * type="string",
 * description="Name of the category"
 * )
 * )
 *
 * @OA\Schema(
 * schema="MedicalLog",
 * title="MedicalLog",
 * description="Medical Log model",
 * @OA\Property(property="id", type="integer", format="int64", description="Medical Log ID"),
 * @OA\Property(property="pet_id", type="integer", format="int64", description="ID of the pet the log is for"),
 * @OA\Property(property="visit_date", type="string", format="date", description="Date of the medical visit"),
 * @OA\Property(property="diagnosis", type="string", description="Diagnosis from the vet"),
 * @OA\Property(property="notes", type="string", nullable=true, description="Additional notes for the log"),
 * @OA\Property(property="vet_name", type="string", nullable=true, description="Name of the attending vet"),
 * @OA\Property(property="clinic_name", type="string", nullable=true, description="Name of the clinic"),
 * @OA\Property(property="prescribed_medication", type="string", nullable=true, description="Medication name"),
 * @OA\Property(property="attachment_url", type="string", nullable=true, description="URL of the attachment"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp")
 * )
 *
 * @OA\Schema(
 * schema="MedicalLogRegisterRequest",
 * title="MedicalLogRegisterRequest",
 * description="Medical log creation request payload",
 * required={"pet_id", "visit_date", "diagnosis"},
 * @OA\Property(property="pet_id", type="integer", format="int64", description="ID of the pet the log is for"),
 * @OA\Property(property="visit_date", type="string", format="date", description="Date of the medical visit"),
 * @OA\Property(property="diagnosis", type="string", description="Diagnosis from the vet"),
 * @OA\Property(property="notes", type="string", nullable=true, description="Additional notes for the log"),
 * @OA\Property(property="vet_name", type="string", nullable=true, description="Name of the attending vet"),
 * @OA\Property(property="clinic_name", type="string", nullable=true, description="Name of the clinic"),
 * @OA\Property(property="prescribed_medication", type="string", nullable=true, description="Medication name"),
 * @OA\Property(property="attachment_url", type="string", nullable=true, description="URL of the attachment"),
 * )
 *
 * @OA\Schema(
 * schema="MedicalLogUpdateRequest",
 * title="MedicalLogUpdateRequest",
 * description="Medical log update request payload",
 * @OA\Property(property="visit_date", type="string", format="date", nullable=true, description="Date of the medical visit"),
 * @OA\Property(property="diagnosis", type="string", nullable=true, description="Diagnosis from the vet"),
 * @OA\Property(property="notes", type="string", nullable=true, description="Additional notes for the log"),
 * @OA\Property(property="vet_name", type="string", nullable=true, description="Name of the attending vet"),
 * @OA\Property(property="clinic_name", type="string", nullable=true, description="Name of the clinic"),
 * @OA\Property(property="prescribed_medication", type="string", nullable=true, description="Medication name"),
 * @OA\Property(property="attachment_url", type="string", nullable=true, description="URL of the attachment"),
 * )
 *
 * @OA\Tag(
 * name="Emergency Shelters",
 * description="API Endpoints for managing emergency pet shelter requests"
 * )
 *
 * @OA\Schema(
 * schema="EmergencyShelter",
 * title="EmergencyShelter",
 * description="Emergency shelter request model",
 * @OA\Property(property="id", type="integer", format="int64", description="Emergency Shelter Request ID"),
 * @OA\Property(property="user_id", type="integer", format="int64", description="ID of the user who made the request"),
 * @OA\Property(property="reason", type="string", description="Reason for the emergency shelter request"),
 * @OA\Property(property="status", type="string", enum={"pending", "accepted", "rejected", "completed"}, description="Status of the request"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp"),
 * example={
 * "id": 1, "user_id": 1, "reason": "My house was flooded due to heavy rain.",
 * "status": "pending",
 * "created_at": "2025-08-16T12:00:00.000000Z", "updated_at": "2025-08-16T12:00:00.000000Z"
 * }
 * )
 *
 * @OA\Schema(
 * schema="EmergencyShelterRegisterRequest",
 * title="EmergencyShelterRegisterRequest",
 * description="Emergency shelter request creation payload",
 * required={"pet_id", "request_date"},
 * @OA\Property(property="pet_id", type="integer", description="ID of the lost pet"),
 * @OA\Property(property="request_date", type="string", format="date", description="Date of the placing request for emergency shelter"),
 * )
 *
 * @OA\Schema(
 * schema="EmergencyShelterPaginatedResponse",
 * title="EmergencyShelterPaginatedResponse",
 * description="Paginated list of emergency shelter requests",
 * @OA\Property(property="current_page", type="integer"),
 * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/EmergencyShelter")),
 * @OA\Property(property="first_page_url", type="string"),
 * @OA\Property(property="from", type="integer"),
 * @OA\Property(property="last_page", type="integer"),
 * @OA\Property(property="last_page_url", type="string"),
 * @OA\Property(
 * property="links",
 * type="array",
 * @OA\Items(
 * @OA\Property(property="url", type="string", nullable=true),
 * @OA\Property(property="label", type="string"),
 * @OA\Property(property="active", type="boolean")
 * )
 * ),
 * @OA\Property(property="next_page_url", type="string", nullable=true),
 * @OA\Property(property="path", type="string"),
 * @OA\Property(property="per_page", type="integer"),
 * @OA\Property(property="prev_page_url", type="string", nullable=true),
 * @OA\Property(property="to", type="integer"),
 * @OA\Property(property="total", type="integer"),
 * )
 * )
 *
 * @OA\Tag(
 * name="PetProducts",
 * description="API Endpoints for managing pet products"
 * )
 *
 * @OA\Schema(
 * schema="PetProduct",
 * title="PetProduct",
 * description="Pet Product model",
 * @OA\Property(property="id", type="integer", format="int64", description="Pet Product ID"),
 * @OA\Property(property="name", type="string", description="Name of the pet product"),
 * @OA\Property(property="description", type="string", nullable=true, description="Description of the product"),
 * @OA\Property(property="price", type="number", format="float", description="Price of the product"),
 * @OA\Property(property="stock", type="integer", description="Available quantity in stock"),
 * @OA\Property(property="image_url", type="string", nullable=true, description="URL to the product image"),
 * @OA\Property(property="category_id", type="integer", format="int64", description="ID of the product's category"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp"),
 * @OA\Property(property="category", ref="#/components/schemas/Category", description="Associated category information")
 * )
 *
 * @OA\Schema(
 * schema="PetProductPaginatedResponse",
 * title="PetProductPaginatedResponse",
 * description="Paginated list of pet products",
 * @OA\Property(property="current_page", type="integer"),
 * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/PetProduct")),
 * @OA\Property(property="first_page_url", type="string"),
 * @OA\Property(property="from", type="integer"),
 * @OA\Property(property="last_page", type="integer"),
 * @OA\Property(property="last_page_url", type="string"),
 * @OA\Property(
 * property="links",
 * type="array",
 * @OA\Items(
 * @OA\Property(property="url", type="string", nullable=true),
 * @OA\Property(property="label", type="string"),
 * @OA\Property(property="active", type="boolean")
 * )
 * ),
 * @OA\Property(property="next_page_url", type="string", nullable=true),
 * @OA\Property(property="path", type="string"),
 * @OA\Property(property="per_page", type="integer"),
 * @OA\Property(property="prev_page_url", type="string", nullable=true),
 * @OA\Property(property="to", type="integer"),
 * @OA\Property(property="total", type="integer")
 * )
 *
 * @OA\Schema(
 * schema="PetProductRegisterRequest",
 * title="PetProductRegisterRequest",
 * description="Pet product registration payload",
 * required={"name", "price", "category_id", "image_url","stock"},
 * @OA\Property(property="name", type="string", description="Name of the product"),
 * @OA\Property(property="description", type="string", nullable=true, description="Description of the product"),
 * @OA\Property(property="price", type="number", format="float", description="Price of the product"),
 * @OA\Property(property="stock", type="integer", description="Available quantity in stock"),
 * @OA\Property(property="image_url", type="string", format="binary", description="Product image file"),
 * @OA\Property(property="category_id", type="integer", description="ID of the product's category")
 * )
 *
 * @OA\Schema(
 * schema="PetProductUpdateRequest",
 * title="PetProductUpdateRequest",
 * description="Pet product update payload",
 * @OA\Property(property="name", type="string", nullable=true, description="Name of the product"),
 * @OA\Property(property="description", type="string", nullable=true, description="Description of the product"),
 * @OA\Property(property="price", type="number", format="float", nullable=true, description="Price of the product"),
 * @OA\Property(property="stock", type="integer", nullable=true, description="Available quantity in stock"),
 * @OA\Property(property="image_url", type="string", format="binary", nullable=true, description="Product image file"),
 * @OA\Property(property="category_id", type="integer", nullable=true, description="ID of the product's category")
 * )
 * 
 * @OA\Schema(
 * schema="CartItem",
 * title="CartItem",
 * description="Cart item model",
 * @OA\Property(property="id", type="integer", format="int64", description="Cart item ID"),
 * @OA\Property(property="cart_id", type="integer", format="int64", description="ID of the associated cart"),
 * @OA\Property(property="product_id", type="integer", format="int64", description="ID of the associated product item"),
 * @OA\Property(property="quantity", type="integer", description="Quantity of the product item"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp of creation"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp of last update"),
 * example={
 * "id": 1,
 * "cart_id": 1,
 * "product_id": 101,
 * "quantity": 2,
 * "created_at": "2023-01-01T12:00:00.000000Z",
 * "updated_at": "2023-01-01T12:00:00.000000Z"
 * }
 * )
 *
 * @OA\Schema(
 * schema="CartItemList",
 * title="CartItemList",
 * description="List of cart items",
 * @OA\Property(
 * property="cartItems",
 * type="array",
 * @OA\Items(ref="#/components/schemas/CartItem")
 * )
 * )
 *
 * @OA\Schema(
 * schema="UpdateCartItemRequest",
 * title="UpdateCartItemRequest",
 * description="Request body for updating cart items",
 * required={"cart_id", "items"},
 * @OA\Property(
 * property="items",
 * type="array",
 * @OA\Items(
 * @OA\Property(property="product_id", type="integer", description="ID of the product item"),
 * @OA\Property(property="quantity", type="integer", description="Quantity of the product item")
 * ),
 * description="List of new cart items"
 * ),
 * example={
 * "items": {
 * {"product_id": 1, "quantity": 2},
 * {"product_id": 2, "quantity": 1}
 * }
 * }
 * )
 *
 * @OA\Schema(
 * schema="Order",
 * title="Order",
 * description="Order model",
 * @OA\Property(property="id", type="integer", format="int64", description="Order ID"),
 * @OA\Property(property="user_id", type="integer", format="int64", description="ID of the user who placed the order"),
 * @OA\Property(property="order_date", type="string", format="date-time", description="Date and time of the order"),
 * @OA\Property(property="total_amount", type="number", format="float", description="Total amount of the order"),
 * @OA\Property(
 * property="order_status",
 * type="string",
 * enum={"pending", "preparing", "ready", "picked", "delivered", "cancelled"},
 * default="pending",
 * description="Current status of the order"
 * ),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp of order creation"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp of last update"),
 * example={
 * "id": 1,
 * "user_id": 10,
 * "order_date": "2023-01-01T12:00:00.000000Z",
 * "total_amount": 25.75,
 * "order_status": "pending",
 * "created_at": "2023-01-01T12:00:00.000000Z",
 * "updated_at": "2023-01-01T12:00:00.000000Z"
 * }
 * )
 *
 * @OA\Schema(
 * schema="OrderItem",
 * title="OrderItem",
 * description="Order item model",
 * @OA\Property(property="id", type="integer", format="int64", description="Order item ID"),
 * @OA\Property(property="order_id", type="integer", format="int64", description="ID of the associated order"),
 * @OA\Property(property="product_id", type="integer", format="int64", description="ID of the associated product item"),
 * @OA\Property(property="quantity", type="integer", description="Quantity of the product item"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp of creation"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp of last update"),
 * example={
 * "id": 1,
 * "order_id": 1,
 * "product_id": 101,
 * "quantity": 2,
 * "created_at": "2023-01-01T12:00:00.000000Z",
 * "updated_at": "2023-01-01T12:00:00.000000Z"
 * }
 * )
 *
 * @OA\Schema(
 * schema="OrderWithItems",
 * title="OrderWithItems",
 * description="Order model with associated order items",
 * allOf={
 * @OA\Schema(ref="#/components/schemas/Order"),
 * @OA\Schema(
 * @OA\Property(
 * property="order_items",
 * type="array",
 * @OA\Items(ref="#/components/schemas/OrderItem")
 * )
 * )
 * },
 * example={
 * "id": 1,
 * "user_id": 10,
 * "order_date": "2023-01-01T12:00:00.000000Z",
 * "total_amount": 25.75,
 * "order_status": "pending",
 * "created_at": "2023-01-01T12:00:00.000000Z",
 * "updated_at": "2023-01-01T12:00:00.000000Z",
 * "order_items": {
 * {
 * "id": 1,
 * "order_id": 1,
 * "product_id": 101,
 * "quantity": 2,
 * "created_at": "2023-01-01T12:00:00.000000Z",
 * "updated_at": "2023-01-01T12:00:00.000000Z"
 * }
 * }
 * }
 * )
 *
 * @OA\Schema(
 * schema="NewOrder",
 * title="NewOrder",
 * description="New order model",
 * @OA\Property(property="id", type="integer", format="int64", description="New order ID"),
 * @OA\Property(property="order_id", type="integer", format="int64", description="ID of the associated order"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp of creation"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp of last update"),
 * @OA\Property(
 * property="order",
 * ref="#/components/schemas/Order"
 * ),
 * example={
 * "id": 1,
 * "order_id": 1,
 * "created_at": "2023-01-01T12:00:00.000000Z",
 * "updated_at": "2023-01-01T12:00:00.000000Z",
 * "order": {
 * "id": 1,
 * "user_id": 10,
 * "order_date": "2023-01-01T12:00:00.000000Z",
 * "total_amount": 25.75,
 * "order_status": "pending"
 * }
 * }
 * )
 *
 * @OA\Schema(
 * schema="OrderPagination",
 * title="Order Pagination",
 * description="Paginated list of orders",
 * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Order")),
 * @OA\Property(property="links", type="object", description="Pagination links"),
 * @OA\Property(property="meta", type="object", description="Pagination meta information")
 * )
 *
 * @OA\Schema(
 * schema="NewOrderPagination",
 * title="New Order Pagination",
 * description="Paginated list of new orders",
 * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/NewOrder")),
 * @OA\Property(property="links", type="object", description="Pagination links"),
 * @OA\Property(property="meta", type="object", description="Pagination meta information")
 * )
 *
 * @OA\Schema(
 * schema="Payment",
 * title="Payment",
 * description="Payment model",
 * @OA\Property(property="id", type="integer", format="int64", description="Payment ID"),
 * @OA\Property(property="user_id", type="integer", format="int64", description="ID of the user who made the payment"),
 * @OA\Property(property="order_id", type="integer", format="int64", description="ID of the associated order"),
 * @OA\Property(property="payment_type", type="string", enum={"cash", "card"}, description="Type of payment"),
 * @OA\Property(property="payment_date", type="string", format="date-time", description="Date and time of the payment"),
 * example={
 * "id": 1,
 * "user_id": 1,
 * "order_id": 10,
 * "payment_type": "cash",
 * "payment_date": "2023-01-01T13:00:00.000000Z"
 * }
 * )
 *
 * @OA\Schema(
 * schema="PaymentPagination",
 * title="Payment Pagination",
 * description="Paginated list of payments",
 * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Payment")),
 * @OA\Property(property="links", type="object", description="Pagination links"),
 * @OA\Property(property="meta", type="object", description="Pagination meta information")
 * )
 *
 * @OA\Schema(
 * schema="RegisterPaymentRequest",
 * title="RegisterPaymentRequest",
 * description="Request body for creating a new payment",
 * required={"order_id", "payment_type"},
 * @OA\Property(property="order_id", type="integer", description="ID of the order to be paid"),
 * @OA\Property(property="payment_type", type="string", enum={"cash", "card"}, description="Type of payment"),
 * example={
 * "order_id": 1,
 * "payment_type": "cash"
 * }
 * )
 * 
 */
class Annotations
{
    // This class is just a container for the annotations. No actual code needed here.
}

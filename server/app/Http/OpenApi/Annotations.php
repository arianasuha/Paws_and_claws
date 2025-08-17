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
 * name="PetProducts",
 * description="API Endpoints for Pet Products"
 * )
 *
 * @OA\Tag(
 * name="Orders",
 * description="API Endpoints for Orders"
 * )
 *
 * @OA\Tag(
 * name="Order Items",
 * description="API Endpoints for Order Items"
 * )
 *
 * @OA\Tag(
 * name="Carts",
 * description="API Endpoints for Carts"
 * )
 *
 * @OA\Schema(
 * schema="User",
 * title="User",
 * description="User model",
 * @OA\Property(
 * property="id",
 * type="integer",
 * format="int64",
 * description="User ID"
 * ),
 * @OA\Property(
 * property="first_name",
 * type="string",
 * nullable=true,
 * description="First name of the user"
 * ),
 * @OA\Property(
 * property="last_name",
 * type="string",
 * nullable=true,
 * description="Last name of the user"
 * ),
 * @OA\Property(
 * property="email",
 * type="string",
 * format="email",
 * description="Email address of the user"
 * ),
 * @OA\Property(
 * property="username",
 * type="string",
 * description="Username of the user"
 * ),
 * @OA\Property(
 * property="address",
 * type="string",
 * nullable=true,
 * description="Address of the user"
 * ),
 * @OA\Property(
 * property="is_admin",
 * type="boolean",
 * description="Is the user an admin?"
 * ),
 * @OA\Property(
 * property="is_active",
 * type="boolean",
 * description="Is the user active?"
 * ),
 * @OA\Property(
 * property="is_vet",
 * type="boolean",
 * description="Is the user a veterinarian?"
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
 * schema="UserPaginatedResponse",
 * title="UserPaginatedResponse",
 * description="Paginated list of users",
 * @OA\Property(
 * property="current_page",
 * type="integer"
 * ),
 * @OA\Property(
 * property="data",
 * type="array",
 * @OA\Items(ref="#/components/schemas/User")
 * ),
 * @OA\Property(
 * property="first_page_url",
 * type="string"
 * ),
 * @OA\Property(
 * property="from",
 * type="integer"
 * ),
 * @OA\Property(
 * property="last_page",
 * type="integer"
 * ),
 * @OA\Property(
 * property="last_page_url",
 * type="string"
 * ),
 * @OA\Property(
 * property="links",
 * type="array",
 * @OA\Items(
 * @OA\Property(property="url", type="string", nullable=true),
 * @OA\Property(property="label", type="string"),
 * @OA\Property(property="active", type="boolean")
 * )
 * ),
 * @OA\Property(
 * property="next_page_url",
 * type="string",
 * nullable=true
 * ),
 * @OA\Property(
 * property="path",
 * type="string"
 * ),
 * @OA\Property(
 * property="per_page",
 * type="integer"
 * ),
 * @OA\Property(
 * property="prev_page_url",
 * type="string",
 * nullable=true
 * ),
 * @OA\Property(
 * property="to",
 * type="integer"
 * ),
 * @OA\Property(
 * property="total",
 * type="integer"
 * )
 * )
 *
 * @OA\Schema(
 * schema="RegisterUserRequest",
 * title="RegisterUserRequest",
 * description="User registration request payload",
 * required={"email", "username", "password"},
 * @OA\Property(
 * property="first_name",
 * type="string",
 * description="First name of the user",
 * nullable=true
 * ),
 * @OA\Property(
 * property="last_name",
 * type="string",
 * description="Last name of the user",
 * nullable=true
 * ),
 * @OA\Property(
 * property="email",
 * type="string",
 * format="email",
 * description="Email address of the user"
 * ),
 * @OA\Property(
 * property="username",
 * type="string",
 * description="Username of the user"
 * ),
 * @OA\Property(
 * property="password",
 * type="string",
 * format="password",
 * description="Password (min: 8 characters, confirmed)"
 * ),
 * @OA\Property(
 * property="password_confirmation",
 * type="string",
 * format="password",
 * description="Password confirmation"
 * ),
 * @OA\Property(
 * property="address",
 * type="string",
 * description="Address of the user",
 * nullable=true
 * )
 * )
 *
 * @OA\Schema(
 * schema="UpdateUserRequest",
 * title="UpdateUserRequest",
 * description="User update request payload",
 * @OA\Property(
 * property="first_name",
 * type="string",
 * description="First name of the user",
 * nullable=true
 * ),
 * @OA\Property(
 * property="last_name",
 * type="string",
 * description="Last name of the user",
 * nullable=true
 * ),
 * @OA\Property(
 * property="email",
 * type="string",
 * format="email",
 * description="Email address of the user",
 * nullable=true
 * ),
 * @OA\Property(
 * property="username",
 * type="string",
 * description="Username of the user",
 * nullable=true
 * ),
 * @OA\Property(
 * property="password",
 * type="string",
 * format="password",
 * description="Password (min: 8 characters, confirmed)",
 * nullable=true
 * ),
 * @OA\Property(
 * property="password_confirmation",
 * type="string",
 * format="password",
 * description="Password confirmation",
 * nullable=true
 * ),
 * @OA\Property(
 * property="address",
 * type="string",
 * description="Address of the user",
 * nullable=true
 * )
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
 * schema="PetProduct",
 * title="PetProduct",
 * description="Pet product model",
 * @OA\Property(
 * property="id",
 * type="integer",
 * format="int64",
 * description="Product ID"
 * ),
 * @OA\Property(
 * property="name",
 * type="string",
 * description="Name of the product"
 * ),
 * @OA\Property(
 * property="description",
 * type="string",
 * description="Description of the product"
 * ),
 * @OA\Property(
 * property="price",
 * type="number",
 * format="float",
 * description="Price of the product"
 * ),
 * @OA\Property(
 * property="stock",
 * type="integer",
 * description="Current stock of the product"
 * ),
 * @OA\Property(
 * property="image_url",
 * type="string",
 * nullable=true,
 * description="URL to the product image"
 * )
 * )
 *
 * @OA\Schema(
 * schema="PetProductPaginatedResponse",
 * title="PetProductPaginatedResponse",
 * description="Paginated list of pet products",
 * @OA\Property(property="current_page", type="integer"),
 * @OA\Property(
 * property="data",
 * type="array",
 * @OA\Items(ref="#/components/schemas/PetProduct")
 * ),
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
 * schema="PetProductStoreRequest",
 * title="PetProductStoreRequest",
 * description="Payload for creating a new pet product",
 * required={"name", "description", "price", "stock"},
 * @OA\Property(
 * property="name",
 * type="string",
 * description="Name of the product"
 * ),
 * @OA\Property(
 * property="description",
 * type="string",
 * description="Description of the product"
 * ),
 * @OA\Property(
 * property="price",
 * type="number",
 * format="float",
 * description="Price of the product"
 * ),
 * @OA\Property(
 * property="stock",
 * type="integer",
 * description="Current stock of the product"
 * ),
 * @OA\Property(
 * property="image_url",
 * type="string",
 * nullable=true,
 * description="URL to the product image"
 * )
 * )
 *
 * @OA\Schema(
 * schema="PetProductUpdateRequest",
 * title="PetProductUpdateRequest",
 * description="Payload for updating an existing pet product",
 * @OA\Property(
 * property="name",
 * type="string",
 * nullable=true,
 * description="Name of the product"
 * ),
 * @OA\Property(
 * property="description",
 * type="string",
 * nullable=true,
 * description="Description of the product"
 * ),
 * @OA\Property(
 * property="price",
 * type="number",
 * format="float",
 * nullable=true,
 * description="Price of the product"
 * ),
 * @OA\Property(
 * property="stock",
 * type="integer",
 * nullable=true,
 * description="Current stock of the product"
 * ),
 * @OA\Property(
 * property="image_url",
 * type="string",
 * nullable=true,
 * description="URL to the product image"
 * )
 * )
 *
 * @OA\Schema(
 * schema="Order",
 * title="Order",
 * description="Order model",
 * @OA\Property(
 * property="id",
 * type="integer",
 * format="int64",
 * description="Order ID"
 * ),
 * @OA\Property(
 * property="user_id",
 * type="integer",
 * format="int64",
 * description="ID of the user who owns the order"
 * ),
 * @OA\Property(
 * property="order_date",
 * type="string",
 * format="date",
 * description="Date the order was placed"
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
 * schema="OrderPaginatedResponse",
 * title="OrderPaginatedResponse",
 * description="Paginated list of orders",
 * @OA\Property(
 * property="current_page",
 * type="integer"
 * ),
 * @OA\Property(
 * property="data",
 * type="array",
 * @OA\Items(ref="#/components/schemas/Order")
 * ),
 * @OA\Property(
 * property="first_page_url",
 * type="string"
 * ),
 * @OA\Property(
 * property="from",
 * type="integer"
 * ),
 * @OA\Property(
 * property="last_page",
 * type="integer"
 * ),
 * @OA\Property(
 * property="last_page_url",
 * type="string"
 * ),
 * @OA\Property(
 * property="links",
 * type="array",
 * @OA\Items(
 * @OA\Property(property="url", type="string", nullable=true),
 * @OA\Property(property="label", type="string"),
 * @OA\Property(property="active", type="boolean")
 * )
 * ),
 * @OA\Property(
 * property="next_page_url",
 * type="string",
 * nullable=true
 * ),
 * @OA\Property(
 * property="path",
 * type="string"
 * ),
 * @OA\Property(
 * property="per_page",
 * type="integer"
 * ),
 * @OA\Property(
 * property="prev_page_url",
 * type="string",
 * nullable=true
 * ),
 * @OA\Property(
 * property="to",
 * type="integer"
 * ),
 * @OA\Property(
 * property="total",
 * type="integer"
 * )
 * )
 *
 * @OA\Schema(
 * schema="OrderRegisterRequest",
 * title="OrderRegisterRequest",
 * description="Payload for creating a new order",
 * required={"status", "total"},
 * @OA\Property(
 * property="status",
 * type="string",
 * description="The status of the order"
 * ),
 * @OA\Property(
 * property="total",
 * type="number",
 * format="float",
 * description="The total price of the order"
 * )
 * )
 *
 * @OA\Schema(
 * schema="OrderUpdateRequest",
 * title="OrderUpdateRequest",
 * description="Payload for updating an existing order",
 * @OA\Property(
 * property="status",
 * type="string",
 * description="The new status of the order",
 * nullable=true
 * ),
 * @OA\Property(
 * property="total",
 * type="number",
 * format="float",
 * description="The new total price of the order",
 * nullable=true
 * )
 * )
 *
 * @OA\Schema(
 * schema="OrderItem",
 * title="OrderItem",
 * description="Order Item model",
 * @OA\Property(
 * property="id",
 * type="integer",
 * format="int64",
 * description="Order Item ID"
 * ),
 * @OA\Property(
 * property="order_id",
 * type="integer",
 * format="int64",
 * description="ID of the associated order"
 * ),
 * @OA\Property(
 * property="product_id",
 * type="integer",
 * format="int64",
 * description="ID of the associated product"
 * ),
 * @OA\Property(
 * property="quantity",
 * type="integer",
 * description="Quantity of the product"
 * )
 * )
 *
 * @OA\Schema(
 * schema="OrderItemRegisterRequest",
 * title="OrderItemRegisterRequest",
 * description="Payload for creating a new order item",
 * required={"product_id", "quantity"},
 * @OA\Property(
 * property="product_id",
 * type="integer",
 * description="ID of the product"
 * ),
 * @OA\Property(
 * property="quantity",
 * type="integer",
 * description="Quantity of the product"
 * )
 * )
 *
 * @OA\Schema(
 * schema="OrderItemUpdateRequest",
 * title="OrderItemUpdateRequest",
 * description="Payload for updating an existing order item",
 * @OA\Property(
 * property="quantity",
 * type="integer",
 * description="Quantity of the product"
 * )
 * )
 *
 * @OA\Schema(
 * schema="Cart",
 * title="Cart",
 * description="Cart model",
 * @OA\Property(property="id", type="integer", format="int64", description="Cart ID"),
 * @OA\Property(property="user_id", type="integer", format="int64", description="ID of the user who owns the cart"),
 * @OA\Property(property="product_id", type="integer", format="int64", description="ID of the associated product"),
 * @OA\Property(property="quantity", type="integer", description="Quantity of the product"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp"),
 * @OA\Property(property="pet_product", ref="#/components/schemas/PetProduct", description="Associated product details")
 * )
 *
 * @OA\Schema(
 * schema="CartPaginatedResponse",
 * title="CartPaginatedResponse",
 * description="Paginated list of cart items",
 * @OA\Property(property="current_page", type="integer"),
 * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Cart")),
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
 * schema="CartStoreRequest",
 * title="CartStoreRequest",
 * description="Payload for adding an item to the cart",
 * required={"product_id", "quantity"},
 * @OA\Property(
 * property="product_id",
 * type="integer",
 * description="ID of the product to add"
 * ),
 * @OA\Property(
 * property="quantity",
 * type="integer",
 * description="Quantity of the product to add"
 * )
 * )
 *
 * @OA\Schema(
 * schema="CartUpdateRequest",
 * title="CartUpdateRequest",
 * description="Payload for updating a cart item",
 * required={"quantity"},
 * @OA\Property(
 * property="quantity",
 * type="integer",
 * description="New quantity for the cart item"
 * )
 * )
 */
class Annotations
{
    // This class is just a container for the annotations. No actual code needed here.
}

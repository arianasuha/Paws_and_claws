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
 */
class Annotations
{
    // This class is just a container for the annotations. No actual code needed here.
}

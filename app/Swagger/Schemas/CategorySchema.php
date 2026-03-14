<?php

namespace App\Swagger\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Category",
    title: "Category",
    description: "Schema for a category",
    type: "object",
    properties: [
        new OA\Property(
            property: "id",
            type: "integer",
            description: "Category ID"
        ),
        new OA\Property(
            property: "name",
            type: "string",
            description: "Category name"
        ),
        new OA\Property(
            property: "created_at",
            type: "string",
            format: "date-time"
        ),
        new OA\Property(
            property: "updated_at",
            type: "string",
            format: "date-time"
        )
    ]
)]
class CategorySchema {}
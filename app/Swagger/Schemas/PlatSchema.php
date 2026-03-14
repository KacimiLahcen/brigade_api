<?php

namespace App\Swagger\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Plat",
    title: "Plat",
    description: "Schema for a Plat (dish)",
    type: "object",
    properties: [
        new OA\Property(
            property: "id",
            type: "integer",
            description: "Plat ID"
        ),
        new OA\Property(
            property: "name",
            type: "string",
            description: "Plat name"
        ),
        new OA\Property(
            property: "price",
            type: "number",
            format: "float",
            description: "Plat price"
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
class PlatSchema {}
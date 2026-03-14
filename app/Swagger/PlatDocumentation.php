<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Tag(name: "Plats", description: "Operations related to Plats")]
class PlatDocumentation {

    #[OA\Post(
        path: "/api/plats",
        summary: "Create a new Plat (with image upload)",
        tags: ["Plats"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "title", type: "string"),
                        new OA\Property(property: "description", type: "string"),
                        new OA\Property(property: "price", type: "number", format: "float"),
                        new OA\Property(property: "stock", type: "integer"),
                        new OA\Property(property: "category_id", type: "integer"),
                        new OA\Property(property: "image", type: "string", format: "binary") // so u can upload and test by a picture
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Plat created"),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function store() {}
}
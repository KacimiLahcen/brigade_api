<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Tag(name: "Plats", description: "Operations related to Plats")]
class PlatDocumentation
{





    #[OA\Get(
        path: "/api/plats",
        summary: "Get all plats",
        tags: ["Plats"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of all plats",
                content: new OA\JsonContent(type: "array", items: new OA\Items(ref: "#/components/schemas/Plat"))
            )
        ]
    )]
    public function index() {}






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








    #[OA\Get(
        path: "/api/plats/{id}",
        summary: "Get a specific plat",
        tags: ["Plats"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Plat details", content: new OA\JsonContent(ref: "#/components/schemas/Plat")),
            new OA\Response(response: 404, description: "Plat not found")
        ]
    )]
    public function show() {}








    #[OA\Post(
        path: "/api/plats/{id}",
        summary: "Update an existing plat (with optional image)",
        tags: ["Plats"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "_method", type: "string", example: "PUT"),
                        new OA\Property(property: "title", type: "string"),
                        new OA\Property(property: "description", type: "string"),
                        new OA\Property(property: "price", type: "number", format: "float"),
                        new OA\Property(property: "stock", type: "integer"),
                        new OA\Property(property: "category_id", type: "integer"),
                        new OA\Property(property: "image", type: "string", format: "binary")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Plat updated successfully"),
            new OA\Response(response: 403, description: "Forbidden"),
            new OA\Response(response: 404, description: "Plat not found")
        ]
    )]
    public function update() {}








    #[OA\Delete(
        path: "/api/plats/{id}",
        summary: "Delete a plat",
        tags: ["Plats"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Plat deleted"),
            new OA\Response(response: 403, description: "Forbidden")
        ]
    )]
    public function destroy() {}
}

<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Tag(name: "Categories", description: "Operations related to Categories")]
class CategoryDocumentation {

    #[OA\Get(
        path: "/api/categories",
        summary: "Get all categories",
        tags: ["Categories"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of categories",
                content: new OA\JsonContent(type: "array", items: new OA\Items(ref: "#/components/schemas/Category"))
            )
        ]
    )]
    public function index() {}

    #[OA\Post(
        path: "/api/categories",
        summary: "Create a new category",
        tags: ["Categories"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Appetizers")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Category created"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function store() {}
}
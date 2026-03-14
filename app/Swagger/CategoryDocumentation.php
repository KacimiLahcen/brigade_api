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












    #[OA\Get(
        path: "/api/categories/{id}",
        summary: "Display a specific category",
        tags: ["Categories"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID of the category to return",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Category found",
                content: new OA\JsonContent(ref: "#/components/schemas/Category")
            ),
            new OA\Response(response: 404, description: "Category not found")
        ]
    )]
    public function show() {}












    #[OA\Put(
        path: "/api/categories/{id}",
        summary: "Update an existing category",
        tags: ["Categories"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID of the category to update",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Main Dishes")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Category updated"),
            new OA\Response(response: 403, description: "Forbidden - You don't own this category"),
            new OA\Response(response: 404, description: "Category not found")
        ]
    )]
    public function update() {}











    #[OA\Delete(
        path: "/api/categories/{id}",
        summary: "Delete a category",
        tags: ["Categories"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID of the category to delete",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Category deleted successfully"),
            new OA\Response(response: 403, description: "Forbidden - You don't own this category"),
            new OA\Response(response: 404, description: "Category not found")
        ]
    )]
    public function destroy() {}
}
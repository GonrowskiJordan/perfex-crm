<?php

/**
 * @OA\Tag(
 *     name="Check",
 *     description="Common API endpoints"
 * )
 */
class Check extends REST_Controller
{
    /**
     * @OA\Get(
     *     path="/common/data/{type}",
     *     tags={"Common"},
     *     summary="Get common data",
     *     description="Retrieve common system data",
     *     operationId="getCommonData",
     *     security={{"api_key":{}}},
     *     @OA\Parameter(
     *         name="type",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             enum={"expense_category", "payment_mode", "tax_data"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     )
     * )
     */
    public function data_get($type = "")
    {
        // Existing implementation
    }
}
<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubscriptionCollection;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


/**
 * @OA\Tag(
 *     name="Subscriptions",
 *     description="API Endpoints of Subscriptions"
 * )
 */
class SubscriptionController extends Controller
{
    public function __construct(private Request $request)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/subscriptions",
     *     tags={"Subscriptions"},
     *     summary="Get all subscriptions",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search term for subscriptions",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="The page number to retrieve",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A list of subscriptions with pagination metadata",
     *         @OA\JsonContent(ref="#/components/schemas/SubscriptionCollection")
     *     )
     * )
     */
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'string|nullable',
            'page' => 'integer|nullable|min:1',
            'limit' => 'integer|nullable|min:1|max:100',
        ]);

        $query = Subscription::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
        }

        $subscriptions = $query->paginate($request->input('limit', 10));

        return new SubscriptionCollection($subscriptions);
    }

    /**
     * @OA\Post(
     *     path="/api/subscriptions",
     *     tags={"Subscriptions"},
     *     summary="Create a new subscription",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Subscription")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="The created subscription",
     *         @OA\JsonContent(ref="#/components/schemas/Subscription")
     *     )
     * )
     */
    public function store(StoreSubscriptionRequest $request)
    {
        $subscription = Subscription::create($request->validated());

        return (new SubscriptionResource($subscription))
                    ->response()
                    ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/subscriptions/{subscription}",
     *     tags={"Subscriptions"},
     *     summary="Get a specific subscription",
     *     @OA\Parameter(
     *         name="subscription",
     *         in="path",
     *         description="Unique identifier for the subscription",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A specific subscription",
     *         @OA\JsonContent(ref="#/components/schemas/Subscription")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Subscription not found"
     *     )
     * )
     */
    public function show(Subscription $subscription)
    {
        return new SubscriptionResource($subscription);
    }

    /**
     * @OA\Put(
     *     path="/api/subscriptions/{subscription}",
     *     tags={"Subscriptions"},
     *     summary="Update a specific subscription",
     *     @OA\Parameter(
     *         name="subscription",
     *         in="path",
     *         description="Unique identifier for the subscription",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Subscription")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="The updated subscription",
     *         @OA\JsonContent(ref="#/components/schemas/Subscription")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Subscription not found"
     *     )
     * )
     */
    public function update(UpdateSubscriptionRequest $request, Subscription $subscription)
    {
        $subscription->update($request->validated());

        return new SubscriptionResource($subscription->fresh());
    }
    /**
     * @OA\Delete(
     *     path="/api/subscriptions/{subscription}",
     *     tags={"Subscriptions"},
     *     summary="Delete a specific subscription",
     *     @OA\Parameter(
     *         name="subscription",
     *         in="path",
     *         description="Unique identifier for the subscription",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Subscription deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Subscription not found"
     *     )
     * )
     */
    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubscriberCollection;
use App\Http\Resources\SubscriberResource;
use App\Models\Subscriber;
use App\Http\Requests\StoreSubscriberRequest;
use App\Http\Requests\UpdateSubscriberRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


/**
 * @OA\Tag(
 *     name="Subscribers",
 *     description="API Endpoints для Subscribers"
 * )
 * 
 * @OA\Schema(
 *     schema="Subscriber",
 *     type="object",
 *     required={"id", "email", "name"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="subscriptions", type="array", @OA\Items(ref="#/components/schemas/Subscription")),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 * 
 * @OA\Schema(
 *     schema="SubscriberCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Subscriber")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         @OA\Property(property="current_page", type="integer"),
 *         @OA\Property(property="from", type="integer"),
 *         @OA\Property(property="last_page", type="integer"),
 *         @OA\Property(property="per_page", type="integer"),
 *         @OA\Property(property="to", type="integer"),
 *         @OA\Property(property="total", type="integer")
 *     ),
 *     @OA\Property(
 *         property="links",
 *         type="object",
 *         @OA\Property(property="first", type="string"),
 *         @OA\Property(property="last", type="string"),
 *         @OA\Property(property="prev", type="string", nullable=true),
 *         @OA\Property(property="next", type="string", nullable=true)
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="Subscription",
 *     type="object",
 *     required={"id", "subscriber_id", "service", "topic"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="subscriber_id", type="integer", example=1),
 *     @OA\Property(property="service", type="string", example="Newsletter"),
 *     @OA\Property(property="topic", type="string", example="Weekly Updates"),
 *     @OA\Property(property="payload", type="object", example={"frequency": "weekly"}),
 *     @OA\Property(property="expired_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 * 
 * @OA\Schema(
 *     schema="SubscriptionCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Subscription")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         @OA\Property(property="current_page", type="integer"),
 *         @OA\Property(property="from", type="integer"),
 *         @OA\Property(property="last_page", type="integer"),
 *         @OA\Property(property="per_page", type="integer"),
 *         @OA\Property(property="to", type="integer"),
 *         @OA\Property(property="total", type="integer")
 *     ),
 *     @OA\Property(
 *         property="links",
 *         type="object",
 *         @OA\Property(property="first", type="string"),
 *         @OA\Property(property="last", type="string"),
 *         @OA\Property(property="prev", type="string", nullable=true),
 *         @OA\Property(property="next", type="string", nullable=true)
 *     )
 * )
 */
class SubscriberController extends Controller
{
    public function __construct(private Request $request)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/subscribers",
     *     tags={"Subscribers"},
     *     summary="Get all subscribers",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search term for subscribers",
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
     *         description="A list of subscribers with pagination metadata",
     *         @OA\JsonContent(ref="#/components/schemas/SubscriberCollection")
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

        $query = Subscriber::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        $subscribers = $query->paginate($request->input('limit', 10));

        return new SubscriberCollection($subscribers);
    }

    /**
     * @OA\Post(
     *     path="/api/subscribers",
     *     tags={"Subscribers"},
     *     summary="Create a new subscriber",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Subscriber")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="The created subscriber",
     *         @OA\JsonContent(ref="#/components/schemas/Subscriber")
     *     )
     * )
     */
    public function store(StoreSubscriberRequest $request)
    {
        $subscriber = Subscriber::create($request->validated());

        return (new SubscriberResource($subscriber))
                    ->response()
                    ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/subscribers/{subscriber}",
     *     tags={"Subscribers"},
     *     summary="Get a specific subscriber",
     *     @OA\Parameter(
     *         name="subscriber",
     *         in="path",
     *         description="Unique identifier for the subscriber",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A specific subscriber",
     *         @OA\JsonContent(ref="#/components/schemas/Subscriber")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Subscriber not found"
     *     )
     * )
     */
    public function show(Subscriber $subscriber)
    {
        return new SubscriberResource($subscriber);
    }

    /**
     * @OA\Put(
     *     path="/api/subscribers/{subscriber}",
     *     tags={"Subscribers"},
     *     summary="Update a specific subscriber",
     *     @OA\Parameter(
     *         name="subscriber",
     *         in="path",
     *         description="Unique identifier for the subscriber",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Subscriber")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="The updated subscriber",
     *         @OA\JsonContent(ref="#/components/schemas/Subscriber")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Subscriber not found"
     *     )
     * )
     */
    public function update(UpdateSubscriberRequest $request, Subscriber $subscriber)
    {
        $subscriber->update($request->validated());

        return new SubscriberResource($subscriber->fresh());
    }
    /**
     *  @OA\Delete(
     *     path="/api/subscribers/{subscriber}",
     *     tags={"Subscribers"},
     *     summary="Delete a specific subscriber",
     *     @OA\Parameter(
     *         name="subscriber",
     *         in="path",
     *         description="Unique identifier for the subscriber",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Subscriber deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Subscriber not found"
     *     )
     * )
     */
    public function destroy(Subscriber $subscriber)
    {
        $subscriber->delete();

        return response()->noContent();
    }
}

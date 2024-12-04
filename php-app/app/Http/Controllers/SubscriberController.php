<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubscriberCollection;
use App\Http\Resources\SubscriberResource;
use App\Models\Subscriber;
use App\Http\Requests\StoreSubscriberRequest;
use App\Http\Requests\UpdateSubscriberRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SubscriberController extends Controller
{
    public function __construct(private Request $request)
    {
    }

    /**
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
     */
    public function store(StoreSubscriberRequest $request)
    {
        $subscriber = Subscriber::create($request->validated());

        return (new SubscriberResource($subscriber))
                    ->response()
                    ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscriber $subscriber)
    {
        return new SubscriberResource($subscriber->load('subscriptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubscriberRequest $request, Subscriber $subscriber)
    {
        $subscriber->update($request->validated());

        return new SubscriberResource($subscriber->fresh('subscriptions'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscriber $subscriber)
    {
        $subscriber->delete();

        return response()->noContent();
    }
}

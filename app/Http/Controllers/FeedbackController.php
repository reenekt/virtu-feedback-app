<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeedbackRequest;
use App\Http\Requests\UpdateFeedbackRequest;
use App\Models\Feedback;
use App\Http\Resources\Feedback as FeedbackResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $feedbacks = Feedback::query()->paginate();

        return FeedbackResource::collection($feedbacks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreFeedbackRequest $request
     * @return JsonResponse
     */
    public function store(StoreFeedbackRequest $request)
    {
        $feedback = new Feedback($request->all());

        $attachment = $request->file('attachment');
        if ($attachment) {
            $feedback->setAttachmentUploadedFile($attachment);
        }

        $feedback->save();

        return response()->json([
            'success' => true,
            'model' => $feedback,
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Feedback $feedback
     * @return FeedbackResource
     */
    public function show(Feedback $feedback)
    {
        return new FeedbackResource($feedback);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateFeedbackRequest $request
     * @param Feedback $feedback
     * @return JsonResponse
     */
    public function update(UpdateFeedbackRequest $request, Feedback $feedback)
    {
        $feedback->fill($request->all());

        $attachment = $request->file('attachment');
        if ($attachment) {
            $feedback->setAttachmentUploadedFile($attachment);
        }

        $feedback->save();

        return response()->json([
            'success' => true,
            'model' => $feedback,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Feedback $feedback
     * @return JsonResponse
     */
    public function destroy(Feedback $feedback)
    {
        $feedback->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}

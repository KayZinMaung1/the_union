<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StateRequest;
use App\Http\Resources\StateResource;
use App\Models\State;
use Illuminate\Http\JsonResponse;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    const NAME = 'name';
    public function index()
    {

        $data = State::all();
        return response()->json(["status" => "success", "data" => StateResource::collection($data), "total" => count($data)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StateRequest $request)
    {
        try {
            $name = trim($request->get(self::NAME));

            $state = new State();
            $state->name = $name;

            $state->save();

            return jsend_success(new StateResource($state), JsonResponse::HTTP_CREATED);
        } catch (Exception $ex) {
            Log::error(__('api.saved-failed', ['state' => class_basename(State::class)]), [
                'code' => $ex->getCode(),
                'trace' => $ex->getTrace(),
            ]);

            return jsend_error(__('api.saved-failed', ['state' => class_basename(State::class)]), [
                $ex->getCode(),
                ErrorType::SAVE_ERROR,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function show(State $state)
    {
        return jsend_success(new StateResource($state));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function update(StateRequest $request, State $state)
    {

        try {
            $name = trim($request->get(self::NAME));

            $state->name = $name;

            $state->save();

            return jsend_success(new StateResource($state), JsonResponse::HTTP_CREATED);
        } catch (Exception $ex) {
            Log::error(__('api.updated-failed', ['state' => class_basename(State::class)]), [
                'code' => $ex->getCode(),
                'trace' => $ex->getTrace(),
            ]);

            return jsend_error(__('api.updated-failed', ['state' => class_basename(State::class)]), [
                $ex->getCode(),
                ErrorType::UPDATE_ERROR,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function destroy(State $state)
    {

        try {
            $state->delete();

            return jsend_success(null, JsonResponse::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $exception) {
            return jsend_error(["error" => 'Data Not Found.'], JsonResponse::HTTP_NOT_FOUND);
        }
    }
}

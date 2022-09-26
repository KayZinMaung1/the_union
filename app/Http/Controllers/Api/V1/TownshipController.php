<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TownshipRequest;
use App\Http\Resources\TownshipResource;
use App\Models\District;
use App\Models\Township;
use Illuminate\Http\JsonResponse;


class TownshipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    const NAME = 'name';
    const DISTRICT_ID = 'district_id';
    public function index()
    {

        $data = Township::all();
        return response()->json(["status" => "success", "data" => TownshipResource::collection($data), "total" => count($data)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TownshipRequest $request)
    {
        try {
            $name = trim($request->get(self::NAME));
            $district_id = $request->get(self::DISTRICT_ID);

            $township = new Township();
            $township->name = $name;
            $township->district_id = $district_id;

            $township->save();

            return jsend_success(new TownshipResource($township), JsonResponse::HTTP_CREATED);
        } catch (Exception $ex) {
            Log::error(__('api.saved-failed', ['township' => class_basename(Township::class)]), [
                'code' => $ex->getCode(),
                'trace' => $ex->getTrace(),
            ]);

            return jsend_error(__('api.saved-failed', ['township' => class_basename(Township::class)]), [
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
    public function show(Township $township)
    {
        return jsend_success(new TownshipResource($township));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function update(TownshipRequest $request, Township $township)
    {

        try {
            $name = trim($request->get(self::NAME));
            $district_id = $request->get(self::DISTRICT_ID);

            $township->name = $name;
            $township->district_id = $district_id;

            $township->save();

            return jsend_success(new TownshipResource($township), JsonResponse::HTTP_CREATED);
        } catch (Exception $ex) {
            Log::error(__('api.updated-failed', ['township' => class_basename(Township::class)]), [
                'code' => $ex->getCode(),
                'trace' => $ex->getTrace(),
            ]);

            return jsend_error(__('api.updated-failed', ['township' => class_basename(Township::class)]), [
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
    public function destroy(Township $township)
    {

        try {
            $township->delete();

            return jsend_success(null, JsonResponse::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $exception) {
            return jsend_error(["error" => 'Data Not Found.'], JsonResponse::HTTP_NOT_FOUND);
        }
    }
}

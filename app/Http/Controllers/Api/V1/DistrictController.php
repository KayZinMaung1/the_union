<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\DistrictRequest;
use App\Http\Resources\DistrictResource;
use App\Models\District;
use Illuminate\Http\JsonResponse;


class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    const NAME = 'name';
    const STATE_ID = 'state_id';
    public function index()
    {

        $data = District::all();
        return response()->json(["status" => "success", "data" => DistrictResource::collection($data), "total" => count($data)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DistrictRequest $request)
    {
        try {
            $name = trim($request->get(self::NAME));
            $state_id = $request->get(self::STATE_ID);

            $district = new District();
            $district->name = $name;
            $district->state_id = $state_id;

            $district->save();

            return jsend_success(new DistrictResource($district), JsonResponse::HTTP_CREATED);
        } catch (Exception $ex) {
            Log::error(__('api.saved-failed', ['distirct' => class_basename(District::class)]), [
                'code' => $ex->getCode(),
                'trace' => $ex->getTrace(),
            ]);

            return jsend_error(__('api.saved-failed', ['district' => class_basename(District::class)]), [
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
    public function show(District $district)
    {
        return jsend_success(new DistrictResource($district));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function update(DistrictRequest $request, District $district)
    {

        try {
            $name = trim($request->get(self::NAME));
            $state_id = $request->get(self::STATE_ID);

            $district->name = $name;
            $district->state_id = $state_id;

            $district->save();

            return jsend_success(new DistrictResource($district), JsonResponse::HTTP_CREATED);
        } catch (Exception $ex) {
            Log::error(__('api.updated-failed', ['district' => class_basename(District::class)]), [
                'code' => $ex->getCode(),
                'trace' => $ex->getTrace(),
            ]);

            return jsend_error(__('api.updated-failed', ['district' => class_basename(District::class)]), [
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
    public function destroy(District $district)
    {

        try {
            $district->delete();

            return jsend_success(null, JsonResponse::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $exception) {
            return jsend_error(["error" => 'Data Not Found.'], JsonResponse::HTTP_NOT_FOUND);
        }
    }
}

<?php

namespace App\Http\Controllers\Account\Activity;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Account\ActivityType;
use App\Traits\JsonRespondController;
use App\Models\Account\ActivityTypeCategory;
use App\Services\Account\Activity\ActivityType\CreateActivityType;
use App\Services\Account\Activity\ActivityType\UpdateActivityType;
use App\Services\Account\Activity\ActivityType\DestroyActivityType;
use App\Http\Resources\Activity\ActivityType as ActivityTypeResource;

class ActivityTypesController extends Controller
{
    use JsonRespondController;

    /**
     * Store an activity type category.
     *
     * @param  Contact $contact
     * @return ActivityType
     */
    public function store(Request $request)
    {
        $type = (new CreateActivityType)->execute([
            'account_id' => auth()->user()->account->id,
            'activity_type_category_id' => $request->get('activity_type_category_id'),
            'name' => $request->get('name'),
            'translation_key' => $request->get('translation_key'),
        ]);

        return new ActivityTypeResource($type);
    }

    /**
     * Update an activity type.
     *
     * @param Request $request
     * @param int $activityTypeId
     * @return ActivityTypeCategory
     */
    public function update(Request $request, $activityTypeId)
    {
        $data = [
            'account_id' => auth()->user()->account->id,
            'activity_type_id' => $activityTypeId,
            'activity_type_category_id' => $request->get('activity_type_category_id'),
            'name' => $request->get('name'),
            'translation_key' => $request->get('translation_key'),
        ];

        $type = (new UpdateActivityType)->execute($data);

        return new ActivityTypeResource($type);
    }

    /**
     * Delete the activity type.
     *
     * @param Request $request
     * @param int $activityType
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $activityTypeId)
    {
        $data = [
            'account_id' => auth()->user()->account->id,
            'activity_type_id' => $activityTypeId,
        ];

        try {
            (new DestroyActivityType)->execute($data);
        } catch (\Exception $e) {
            return $this->respondNotFound();
        }

        return $this->respondObjectDeleted($activityTypeId);
    }
}
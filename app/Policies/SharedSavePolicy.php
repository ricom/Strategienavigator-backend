<?php

namespace App\Policies;

use App\Helper\PermissionHelper;
use App\Models\Save;
use App\Models\SharedSave;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SharedSavePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return env("APP_DEBUG");
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param SharedSave $sharedSave
     * @return mixed
     */
    public function view(User $user, SharedSave $sharedSave)
    {
        return $sharedSave->safe->hasAtLeasPermission($user, PermissionHelper::$PERMISSION_READ);
    }


    /**
     * Determine whether the user can view the model of the given save
     * @param User $user
     * @param Save $save
     */
    public function viewOfSave(User $user, Save $save)
    {
        return $save->hasAtLeasPermission($user, PermissionHelper::$PERMISSION_READ);
    }

    /**
     * Determine whether the user can view the model of the given user
     * @param User $user
     * @param Save $model
     */
    public function viewOfUser(User $user, User $model)
    {
        return $model->id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user, Save $save)
    {
        return $save->hasAtLeasPermission($user, PermissionHelper::$PERMISSION_ADMIN);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param SharedSave $sharedSave
     * @return mixed
     */
    public function update(User $user, SharedSave $sharedSave)
    {
        return $sharedSave->safe->hasAtLeasPermission($user, PermissionHelper::$PERMISSION_ADMIN);
    }


    public function acceptDecline(User $user, SharedSave $sharedSave)
    {
        return $sharedSave->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param SharedSave $sharedSave
     * @return mixed
     */
    public function delete(User $user, SharedSave $sharedSave)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param SharedSave $sharedSave
     * @return mixed
     */
    public function restore(User $user, SharedSave $sharedSave)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param SharedSave $sharedSave
     * @return mixed
     */
    public function forceDelete(User $user, SharedSave $sharedSave)
    {
        return false;
    }
}

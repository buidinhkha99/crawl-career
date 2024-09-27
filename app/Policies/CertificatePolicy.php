<?php

namespace App\Policies;

use App\Models\Certificate;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CertificatePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Certificate $certificate
     * @return Response|bool
     */
    public function view(User $user, Certificate $certificate)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Certificate $certificate
     * @return Response|bool
     */
    public function update(User $user, Certificate $certificate)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Certificate $certificate
     * @return Response|bool
     */
    public function delete(User $user, Certificate $certificate)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Certificate $certificate
     * @return Response|bool
     */
    public function restore(User $user, Certificate $certificate)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Certificate $certificate
     * @return Response|bool
     */
    public function forceDelete(User $user, Certificate $certificate)
    {
        //
    }
}

<?php

namespace Modules\Auth\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Modules\Auth\Models\Permission;
use Modules\Auth\Models\Role;
use Modules\Auth\Models\User;


interface RolePermissionServiceInterface
{

    /**
     * @return Collection
     */
    public function getRolesWithPermissions(): Collection;


    /**
     * @return Collection
     */
    public function getPermissionsWithRoles(): Collection;


    /**
     * @param  int  $roleId
     * @return Role
     */
    public function getRoleById(int $roleId): Role;


    /**
     * @param  string  $slug
     * @return Role
     */
    public function getRoleBySlug(string $slug): Role;


    /**
     * @param  Role  $role
     * @param  User  $user
     * @return void
     */
    public function associateRoleWithUser(Role $role, User $user): void;


    /**
     * Get the role entity by the slug value
     * User should have only one role
     *
     * @param  User  $user
     * @return void
     */
    public function deleteUserRole(User $user): void;


    /**
     * @param  array  $data
     * @return Role
     */
    public function createRole(array $data): Role;


    /**
     * @param  array  $data
     * @return Permission
     */
    public function createPermission(array $data): Permission;


    /**
     * @param  Role  $role
     * @param  array  $permissions
     * @return bool
     */
    public function syncPermissionsToRole(Role $role, array $permissions): bool;

    /**
     * @param  Permission  $permission
     * @param  array  $roles
     * @return bool
     */
    public function syncRolesToPermission(Permission $permission, array $roles): bool;


    /**
     * @param  Role  $role
     * @param  array  $data
     * @return bool|null
     */
    public function updateRole(Role $role, array $data): bool|null;


    /**
     * @param  Permission  $permission
     * @param  array  $data
     * @return bool|null
     */
    public function updatePermission(Permission $permission, array $data): bool|null;


    /**
     * @param  Role  $role
     * @return bool|null
     */
    public function deleteRole(Role $role): bool|null;


    /**
     * @param  Permission  $permission
     * @return bool|null
     */
    public function deletePermission(Permission $permission): bool|null;


    /**
     * @param  Collection  $collection
     * @return array
     */
    public function getRelatedIds(Collection $collection): array;
}

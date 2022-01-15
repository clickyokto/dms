<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Pramix\XBranches\Models\BranchesModel;

class BranchScopes implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        $builder->where('branch_id', BranchesModel::getBranchID());

    }
}

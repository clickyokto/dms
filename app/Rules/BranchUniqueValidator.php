<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Pramix\XBranches\Models\BranchesModel;

class BranchUniqueValidator implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $model = NULL;
    public $column_name = NULL;
    public $id = NULL;

    public function __construct($model = NULL, $column_name = NULL, $id = NULL)
    {
        $this->model = $model;
        $this->column_name = $column_name;
        $this->id = $id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if($this->model == NULL || $this->column_name == NULL)
            return false;

        $model = $this->model;
        $column_name = $model::where($this->column_name, $value);
        if($this->id!=NULL)
            $column_name->where('id', '!=', $this->id);
         $column_name =   $column_name->where('branch_id', BranchesModel::getBranchID())->first();
        if ($column_name == NULL)
            return true;
        else
            return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute has already been taken';
    }
}

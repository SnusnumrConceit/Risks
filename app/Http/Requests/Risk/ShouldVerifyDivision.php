<?php


namespace App\Http\Requests\Risk;

use App\Division;
use Illuminate\Validation\Validator;

trait ShouldVerifyDivision
{
    protected $authUser;

    public function withValidator(Validator $validator)
    {
        if (empty($this->authUser)) {
            $this->authUser = auth()->user();
        }

        $validator->addExtension('accessable', function ($attribute, $value) {
            if ($this->authUser->hasPermission('full')) return true;

            if (! $this->authUser->is_responsible && $this->authUser->division_id !== $value) return false;

            return in_array($value, Division::getDescendantsIds($this->authUser->division_id, $this->authUser->division->level));
        });

        $validator->addReplacer('accessable', function ($message, $attribute, $value) {
            return __('validation.exists', ['attribute' => __('divisions.division')]);
        });
    }
}

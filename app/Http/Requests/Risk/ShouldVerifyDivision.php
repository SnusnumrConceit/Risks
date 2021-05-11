<?php


namespace App\Http\Requests\Risk;

use App\Division;
use Illuminate\Validation\Validator;

trait ShouldVerifyDivision
{
    public function withValidator(Validator $validator)
    {
        if (empty($this->user)) {
            $this->user = auth()->user();
        }

        $validator->addExtension('accessable', function ($attribute, $value) {
            if ($this->user->hasPermission('full')) return true;

            if (! $this->user->is_responsible && $this->user->division_id !== $value) return false;

            return in_array($value, Division::getDescendantsIds($this->user->division_id, $this->user->division->level));
        });

        $validator->addReplacer('accessable', function ($message, $attribute, $value) {
            return __('validation.exists', ['attribute' => __('divisions.division')]);
        });
    }
}

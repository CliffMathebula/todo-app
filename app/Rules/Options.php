<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Options implements Rule
{
  /**
   * Available options
   *
   * @var array
   */
  protected $options;

  /**
   * Create a new rule instance.
   *
   * @return void
   */
  public function __construct(array $options)
  {
    $this->options = $options;
  }

  /**
   * Determine if the validation rule passes.
   *
   * @param  string  $attribute
   * @param  mixed  $value
   * @return bool
   */
  public function passes($attribute, $value)
  {
    return in_array($value, $this->options);
  }

  /**
   * Get the validation error message.
   *
   * @return string
   */
  public function message()
  {
    return 'The :attribute option does not exist.';
  }
}

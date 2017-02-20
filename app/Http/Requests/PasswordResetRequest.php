<?php namespace Unified\Http\Requests;

use Unified\Http\Requests\Request;

class PasswordResetRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{


		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
            'user' => 'required',
            'email' =>'required|email'
		];
	}

}

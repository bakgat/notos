<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 18/08/15
 * Time: 07:57
 */

namespace Bakgat\Notos\Requests\Identity;


use Bakgat\Notos\Requests\Request;

class UserFormRequest extends Request
{
    public function rules()
    {
        return [
            'firstName' => 'max:255',
            'lastName' => 'required|max:255',
            'username' => 'required|max:255|email',
            'birthday' => 'date',
            'gender' => 'in:M,F'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
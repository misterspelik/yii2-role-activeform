<?php

namespace misterspelik\widgets;

use Yii;
use yii\widgets\ActiveForm;

use yii\helpers\ArayHelper;
use misterspelik\widgets\exceptions\RoleActiveFormException;

class RoleActiveForm extends ActiveForm
{

    public $role= false;

    private $all_fields= '*';

    private $allowed_for= [];
    private $denied_for= [];

    public $inputOptions = ['readonly' => false];

    /**
     * @var string the default field class name when calling [[field()]] to create a new field.
     * @see fieldConfig
     */
    public $fieldClass = 'misterspelik\widgets\RoleActiveField';

    /**
     * Field function which is inherited from ActiveForm
     * @param Model $model the data model.
     * @param string $attribute the attribute name or expression. See [[Html::getAttributeName()]] for the format
     * about attribute expression.
     * @param array $options the additional configurations for the field object. These are properties of [[ActiveField]]
     * or a subclass, depending on the value of [[fieldClass]].
     *
     * @return ActiveField the created ActiveField object.
     * @see fieldConfig
     */
    public function field($model, $attribute, $options = [])
    {
        $field = parent::field($model, $attribute, $options);
        if (!empty($this->role)){
            $validated = $this->validateRoles($model, $attribute, $this->role);
            $field->inputOptions['readonly'] = !$validated;

            $options= array_merge($options, $this->inputOptions);
        }

        //$object= parent::field($model, $attribute, $options);
        //var_dump($this->readonly, $object); die;
        return $field;
    }


    /**
     * Validating role rules from Model and check where is specified role
     * @param Model $model the data model.
     * @param string $attribute the attribute name or expression. See [[Html::getAttributeName()]] for the format
     * about attribute expression.
     * @param string $role role of current user to compare with allowed and denied roles and make a desicion
     *
     * return boolean
     */
    private function validateRoles($model, $attribute, $role)
    {
        //acts as default if no role rules specified
        if (!method_exists($model, 'roleRules')){
            return true;
        }

        $this->allowed_for = $this->denied_for = [];
        foreach ($model->roleRules() as $rule){
            if (!in_array($attribute, $rule['attributes']) && !in_array($this->all_fields, $rule['attributes'])){
                continue;
            }

            if ($rule['allow']){
                $this->allowed_for = $this->allowed_for + $rule['roles'];
            }else{
                $this->denied_for = $this->denied_for + $rule['roles'];
            }
        }
        $this->allowed_for= array_unique($this->allowed_for);
        $this->denied_for= array_unique($this->denied_for);

        if (in_array($role, $this->allowed_for) && in_array($role, $this->denied_for)){
            throw new RoleActiveFormException('Role can`t be in allowed and denied at the same time');
        }

        if (in_array($role, $this->allowed_for)){
            return true;
        }

        return false;
    }
}

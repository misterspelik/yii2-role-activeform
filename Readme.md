# Yii2 Role based ActiveForm
------------
This is a package to be used with Yii2 form widget. Usage is totally the same as Yii2 ActiveForm widget.

### Installation
------------
composer require misterspelik/yii2-role-activeform


### Configuration
------------
For example you have model `User` and you want to allow edit all fields to `admin` role but don't want to allow edit name field to role `manager`.
For that you need to define `roleRules` method with such content
```php
public function roleRules()
{
    return [
        [
            'allow' => true,
            'attributes' => ['*'],
            'roles' => ['admin']
        ],
        [
            'allow' => false,
            'attributes' => ['name'],
            'roles' => ['manager']
        ],
    ];
}
```

### Usage
------------
To include widget to your form just use this namespace and create $form instance

```php
use misterspelik\widgets\RoleActiveForm;

$form = RoleActiveForm::begin([
   'role' =>  'manager' //current user role
]);

echo $form->field($model, 'name')->textInput(['maxlength' => true]);
// some code here

RoleActiveForm::end();

```

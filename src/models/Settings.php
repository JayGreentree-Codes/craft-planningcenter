<?php
namespace jaygreentree-codes\planningcenter\models;

use craft\base\Model;

class Settings extends Model
{
    public string $appId = '';
    public string $secret = '';

    public function rules(): array
    {
        return [
            [['appId', 'secret'], 'string'],
            [['appId', 'secret'], 'default', 'value' => ''],
        ];
    }
}

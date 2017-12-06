<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "place".
 *
 * @property integer $id
 * @property string $Name
 * @property string $Description
 * @property boolean $Monday
 * @property boolean $Tuesday
 * @property boolean $Wednesday
 * @property boolean $Thursday
 * @property boolean $Friday
 * @property boolean $Saturday
 * @property boolean $Sunday
 * @property string $user
 * @property string $timestamp
 */
class Place extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'place';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name'], 'required'],
            [['Name', 'Description'], 'string'],
            [['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'], 'boolean'],
            [['timestamp'], 'safe'],
            [['user'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'Name' => Yii::t('app', 'Name'),
            'Description' => Yii::t('app', 'Description'),
            'Monday' => Yii::t('app', 'Monday'),
            'Tuesday' => Yii::t('app', 'Tuesday'),
            'Wednesday' => Yii::t('app', 'Wednesday'),
            'Thursday' => Yii::t('app', 'Thursday'),
            'Friday' => Yii::t('app', 'Friday'),
            'Saturday' => Yii::t('app', 'Saturday'),
            'Sunday' => Yii::t('app', 'Sunday'),
            'user' => Yii::t('app', 'User'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }
}

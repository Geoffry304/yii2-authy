<?php

namespace geoffry304\authy\models;

use Yii;
use \yii\db\ActiveRecord;
use borales\extensions\phoneInput\PhoneInputBehavior;
use libphonenumber\PhoneNumberFormat;

/**
 * This is the base model class for table "authy".
 *
 * @property integer $id
 * @property integer $userid
 * @property integer $authyid
 * @property string $cellphone
 * @property integer $countrycode
 *
 * @property AuthyLogin[] $authyLogins
 */
class Authy  extends ActiveRecord{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'authy';
    }

    public function behaviors()
    {
        return [
            [
                'class' => PhoneInputBehavior::className(),
                'phoneAttribute' => "cellphone",
                'countryCodeAttribute' => 'countrycode',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid', 'authyid', 'cellphone', 'countrycode'], 'required'],
            [['userid', 'authyid', 'countrycode'], 'integer'],
            [['cellphone'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'userid' => Yii::t('app', 'Userid'),
            'authyid' => Yii::t('app', 'Authyid'),
            'cellphone' => Yii::t('app', 'Cellphone'),
            'countrycode' => Yii::t('app', 'Countrycode'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthyLogins()
    {
        return $this->hasMany(AuthyLogin::className(), ['authyid' => 'id']);
    }

    public function saveCorrectPhone(){
      $prefix = "+". $this->countrycode;
      if (substr($this->cellphone, 0, strlen($prefix)) == $prefix) {
          $this->cellphone = substr($this->cellphone, strlen($prefix));
    }
      if($this->save()){
        return true;
      } else {
        return false;
      }
    }
}

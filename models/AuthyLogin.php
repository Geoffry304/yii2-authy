<?php


namespace geoffry304\authy\models;

use Yii; 
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "authy_login".
 *
 * @property int $id
 * @property int $authyid
 * @property string $ip
 * @property string $expire_at
 *
 * @property Authy $authy
 */
class AuthyLogin extends ActiveRecord{
     /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'authy_login';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['authyid', 'ip'], 'required'],
            [['authyid'], 'integer'],
            [['expire_at'], 'safe'],
            [['ip'], 'string', 'max' => 255],
            [['authyid'], 'exist', 'skipOnError' => true, 'targetClass' => Authy::className(), 'targetAttribute' => ['authyid' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'authyid' => Yii::t('app', 'Authyid'),
            'ip' => Yii::t('app', 'Ip'),
            'expire_at' => Yii::t('app', 'Expire At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthy()
    {
        return $this->hasOne(Authy::className(), ['id' => 'authyid']);
    }
}

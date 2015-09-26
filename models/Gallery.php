<?php

namespace sadovojav\gallery\models;

use Yii;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use yii\db\Expression;
use sadovojav\gallery\Module;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%gallery}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $status
 * @property string $created
 * @property string $updated
 *
 * @property GalleryFile[] $files
 */
class Gallery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gallery}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created',
                'updatedAtAttribute' => 'updated',
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            ['status', 'integer'],
            [['created', 'updated'], 'safe'],
            ['name', 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('default', 'ID'),
            'name' => Module::t('default', 'NAME'),
            'status' => Module::t('default', 'STATUS'),
            'created' => Module::t('default', 'CREATED'),
            'updated' => Module::t('default', 'UPDATED'),
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
           $this->updatePositions();

            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        $baseDir = Yii::getAlias(Module::getInstance()->basePath);

        if (!is_dir($baseDir)) {
            mkdir($baseDir);
        }

        $dir = $baseDir . DIRECTORY_SEPARATOR . $this->id;

        if (!is_dir($dir)) {
            mkdir($dir);
        }
    }

    public function afterDelete()
    {
       $this->removeModelDirectory();
    }

    /**
     * @inheritdoc
     * @return Query
     */
    public static function find()
    {
        return new Query(get_called_class());
    }

    /**
     * Remove model directory with all files
     */
    private function removeModelDirectory()
    {
        $dir = Yii::getAlias(Module::getInstance()->basePath . DIRECTORY_SEPARATOR . $this->id);
        $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);

        foreach($files as $file) {
            if ($file->isDir()){
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }

        rmdir($dir);
    }

    /**
     * Update image position
     * @return bool|int
     * @throws \yii\db\Exception
     */
    private function updatePositions()
    {
        if (!isset($_POST['positions']) || empty($_POST['positions'])) {
            return false;
        }

        $positions = explode('|', $_POST['positions']);

        if (!count($positions)) {
            return false;
        }

        $when = '';

        foreach ($positions as $key => $value) {
            $when .= ' WHEN ' . $value . ' THEN ' . $key;
            $where[] = $value;
        }

        $sql = 'UPDATE {{%gallery_file}} SET position = CASE id' . $when . ' END WHERE id IN (' . implode(', ', $where) . ')';

        $command = self::getDb()->createCommand($sql);

        return $command->execute();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(GalleryFile::className(), ['galleryId' => 'id'])
            ->orderBy([
                'position' => SORT_ASC
            ]);
    }
}

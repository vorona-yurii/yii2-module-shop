<?php

namespace backend\modules\shop\src\behavior;

use backend\modules\bot\models\Bot;
use src\api\ShortUrlApi;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Class ProductGroupBehavior
 * @package backend\modules\shop\src\behavior
 */
class ProductGroupBehavior extends Behavior
{

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'before',
            ActiveRecord::EVENT_AFTER_INSERT => 'after',
            ActiveRecord::EVENT_BEFORE_DELETE => 'delete',
        ];
    }


    public function before()
    {
        $this->owner->slug = \Yii::$app->security->generateRandomString(5);
    }

    public function after()
    {
        foreach (Bot::find()->all() as $bot) {
            $long_url = $bot->getDeeplinkProductGroup($this->owner->id);
            $custom_url = $this->owner->slug;
            switch ($bot->platform) {
                case Bot::VIBER:
                    $custom_url .= 'v';
                    break;
                case Bot::TELEGRAM:
                    $custom_url .= 't';
                    break;
            }
            ShortUrlApi::createShortUrl($long_url, $custom_url);
        }
    }

    public function delete()
    {
        foreach (Bot::find()->all() as $bot) {
            $custom_url = $this->owner->slug;
            switch ($bot->platform) {
                case Bot::VIBER:
                    $custom_url .= 'v';
                    break;
                case Bot::TELEGRAM:
                    $custom_url .= 't';
                    break;
            }
            ShortUrlApi::deleteShortUrl($custom_url);
        }
    }
}
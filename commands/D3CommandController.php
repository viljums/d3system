<?php

namespace d3system\commands;

use Yii;
use yii\console\Controller;
use yii\db\Connection;

/**
 *
 * @property Connection $connection
 */
class D3CommandController extends Controller
{

    public function beforeAction($action)
    {
        $this->out('Started: ' . date('Ymd His'));
        return parent::beforeAction($action);
    }

    public function afterAction($action, $result)
    {
        $this->out('Finished: ' . date('Ymd His'));
        return parent::afterAction($action, $result);

    }

    /**
     * @return Connection
     */
    public function getConnection(): Connection
    {
        return Yii::$app->getDb();
    }

    /**
     * output to terminal line
     * @param string $string output string
     * @param int $settings
     */
    public function out(string $string, int $settings = 0): void
    {
        $this->stdout($string . PHP_EOL, $settings);
    }

}

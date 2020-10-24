<?php defined('SYSPATH') or die('No direct script access.');

class Task_Counter extends Minion_Task
{
    protected $_options = array(
        'foo' => 'bar',
        'bar' => NULL,
    );

    /**
     * This is a counter task
     *
     * @param array $params
     */
    protected function _execute(array $params)
    {
        $worker= new GearmanWorker();
        $worker->addServer('gearman');

        $worker->addFunction("counter", [Model::factory('Domain'), 'run_counter']);

        while($worker->work()){};
        if ($worker->returnCode() != GEARMAN_SUCCESS) echo "GEARMAN RETURN CODE: False";
    }
}

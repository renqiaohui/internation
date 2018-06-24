<?php
/**
 * Created by PhpStorm.
 * User: mtt
 * Date: 2018/6/22
 * Time: 下午2:56
 */
namespace app\distribution\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use app\distribution\library\financeRate\FinanceRateNowApi;

class FinanceRate extends Command
{
    protected function configure()
    {
        $this->setName("financerate")->setDescription("汇率获取");
    }
    protected function execute(Input $input, Output $output)
    {
        $output->writeln("test");

        $api = new FinanceRateNowApi();
        $api->invoke();

    }

}
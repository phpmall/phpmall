<?php

declare (strict_types=1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

/**
 * Class Init
 * @package app\command
 */
class Init extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('init')
            ->setDescription('Initialize the application');
    }

    protected function execute(Input $input, Output $output)
    {
        // 指令输出
        $output->writeln('init');
    }
}

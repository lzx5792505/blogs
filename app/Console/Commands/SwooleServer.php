<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class SwooleServer extends Command
{
    private $server;

    //进程ID
    private $pidFile;

    //日志文件
    private $logFile;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swoole {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'start or stop the swoole http server';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        //指定主进程PID存储文件
        $this->pidFile = __DIR__ . '/../../../storage/swoole_server.pid';
        //日志文件存储
        $this->logFile = __DIR__ . '/../../../storage/logs/' . date('Ymd') . '_swoole_server.log';
        if (!is_file($this->logFile)) {
            $resource = fopen($this->logFile, "w");
            fclose($resource);
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //获取传递的操作
        $arg = $this->argument('action');

        switch ($arg) {
            case 'start':
                //检测进程是否已开启
                $pid = $this->getPid();
                if ($pid && \Swoole\Process::kill($pid, 0)) {
                    $this->error("\r\nswoole http server process already exist!\r\n");
                    exit;
                }

                $this->server = new \Swoole\Http\Server("0.0.0.0", 9501);
                $this->server->set([
                    'worker_num' => 8,
                    'daemonize' => 1,
                    'max_request' => 1000,
                    'dispatch_mode' => 2,
                    'pidFile' => $this->pidFile,
                    'logFile' => $this->logFile,
                    'log_level' => 3,
                ]);

                //绑定操作类回调函数
                $app = App::make('App\Handles\SwooleServerHandler');

                $this->server->on('workerstart', array($app, 'onWorkerStart'));
                $this->server->on('request', array($app, 'onRequest'));

                $this->info("\r\nswoole http server process created successful!\r\n");

                $this->server->start();
                break;

            case 'stop':
                if (!$pid = $this->getPid()) {
                    $this->error("\r\nswoole http server process not started!\r\n");
                    exit;
                }
                if (\Swoole\Process::kill((int) $pid)) {
                    $this->info("\r\nswoole http server process close successful!\r\n");
                    exit;
                }
                $this->info("\r\nswoole http server process close failed!\r\n");
                break;

            default:
                $this->error("\r\noperation method does not exist!\r\n");
        }
    }

    //获取pid
    private function getPid()
    {
        return file_exists($this->pidFile) ? file_get_contents($this->pidFile) : false;
    }
}

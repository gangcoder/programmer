<?php

define('BASEDIR', __DIR__);
define('DS', DIRECTORY_SEPARATOR);

include BASEDIR.DS.'IMooc'.DS.'Loader.php';
\IMooc\Loader::register();

// IMooc\Test::test();

/**
* 策略模式
*/
// class Page
// {
//     protected $strategy;

//     public function index()
//     {
//         echo "AD: ";
//         $this->strategy->showAd();
//         echo "\r\n";        
//         echo "Category: ";
//         $this->strategy->showCategory();
//     }

//     public function setStrategy(\IMooc\UserStrategy $strategy)
//     {
//         $this->strategy = $strategy;
//     }
// }

// $page = New Page;
// if (isset($_GET['female'])) {
//     $strategy = new \IMooc\FemaleUserStrategy();
// } else {
//     $strategy = new \IMooc\MaleUserStrategy();
// }

// $page->setStrategy($strategy);
// $page->index();

// ORM 数据对象映射模式
// $user = new User(1);

// $user->mobile = '123455';
// $user->name = 'test';
// $user->regtime = date('Y-m-d H:i:s');

// 复杂数据对象映射模式
/**
* ORM
*/
// class Page
// {
//     public function index()
//     {
//         $user = Factory::createUser(1);
//         $user->name = 'rango';

//         $this->test();
//     }

//     public function test()
//     {
//         $user = Factory::createUser(1);
//         $user->mobile = '180840532';
//     }
// }

// $page = new Page();
// ************************

/**
* 观察者模式：当一个对象状态发生改变时，依赖它的对象全部会收到通知
*/
class Event extends \IMooc\EventGenerator
{
    public function trigger()
    {
        echo "Event<br/>\n";

        // update
        $this->notify();
    }
}

/**
* Observer
*/
class Observer1 implements Observer
{
    public function update($event_info = null)
    {
        echo "逻辑1<br/>\n";
    }
}

class Observer2 implements Observer
{
    public function update($event_info = null)
    {
        echo "逻辑2<br/>\n";
    }
}

$event = new Event();
$event->addObserver(new Observer1);
$event->addObserver(new Observer2);

$event->trigger();
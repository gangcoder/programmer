# 设计模式

## 单例模式

工厂模式 是一种类，它具有为您创建对象的某些方法。您可以使用工厂类创建对象，而不直接使用 new。这样，如果您想要更改所创建的对象类型，只需更改该工厂即可。使用该工厂的所有代码会自动更改。

[Facotry](IMooc\Factory1.php)
[Facotry](IMooc\Factory2.php)
[Facotry](IMooc\Factory.php)

## 单例模式

某些应用程序资源是独占的，因为有且只有一个此类型的资源。例如，通过数据库句柄到数据库的连接是独占的。您希望在应用程序中共享数据库句柄，因为在保持连接打开或关闭时，它是一种开销，在获取单个页面的过程中更是如此

[Singleton](IMooc\Database.php)
[Singleton](IMooc\Singleton.php)

## 观察者模式

观察者模式为您提供了避免组件之间紧密耦合的另一种方法。该模式非常简单：一个对象通过添加一个方法（该方法允许另一个对象，即观察者 注册自己）使本身变得可观察。当可观察的对象更改时，它会将消息发送到已注册的观察者。这些观察者使用该信息执行的操作与可观察的对象无关。结果是对象可以相互对话，而不必了解原因

[Observer](IMooc\Observer1.php)
[Observer](IMooc\EventGenerator.php)
[Observer](IMooc\Observer.php)
[Observer](IMooc\index.php)

## 命令链模式

命令链 模式以松散耦合主题为基础，发送消息、命令和请求，或通过一组处理程序发送任意内容。每个处理程序都会自行判断自己能否处理请求。如果可以，该请求被处理，进程停止。您可以为系统添加或移除处理程序，而不影响其他处理程序

为处理请求而创建可扩展的架构时，命令链模式很有价值，使用它可以解决许多问题

[Chain](IMooc\Chain.php)


## 策略模式

在此模式中，算法是从复杂类提取的，因而可以方便地替换.

策略模式非常适合复杂数据管理系统或数据处理系统，二者在数据筛选、搜索或处理的方式方面需要较高的灵活性

[Strategy](IMooc\Strategy.php)
[UserStrategy](IMooc\UserStrategy.php)
[FemaleUserStrategy](IMooc\FemaleUserStrategy.php)
[MaleUserStrategy](IMooc\MaleUserStrategy.php)
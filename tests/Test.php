<?php

namespace Snr\AccessDescriptor\Tests;

use PHPUnit\Framework\TestCase;
use Snr\AccessDescriptor\Manager\HandlerPluginManager;
use Snr\AccessDescriptor\Manager\HandlerPluginManagerInterface;
use Snr\AccessDescriptor\Tests\AccessDescriptor\AccessDescriptor;
use Snr\AccessDescriptor\Tests\Container\ContainerFactory;
use Snr\AccessDescriptor\Tests\Entity\User;
use Snr\Plugin\Adapter\SymfonyContainerAdapter;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\DependencyInjection\Reference;

class Test extends TestCase {

  public function testMain() {

    $root = dirname(__FILE__, 2);
    require_once "$root/vendor/autoload.php";

    $container = new ContainerBuilder();

    $event_dispatcher = new EventDispatcher();
    $container->register('event_dispatcher', $event_dispatcher);

    $container->register(HandlerPluginManager::class, HandlerPluginManager::class)
      ->addArgument(["Snr\AccessDescriptor\Tests" => "$root/tests"])
      ->addArgument(new Reference('event_dispatcher'));

    $container->compile();

    $test_container = ContainerFactory::getContainer(new SymfonyContainerAdapter($container));

    // Получим менеджер
    /** @var HandlerPluginManagerInterface $handler_plugin_manager */
    $handler_plugin_manager = $test_container->get(HandlerPluginManager::class);

    // Все доступные обработчики доступа (экземпляры HandlerPluginInterface)
    // В нашем случае это 'roles', 'users' и 'users_or_roles'
    $definitions = $handler_plugin_manager->getDefinitions();

    // Например, мы хотим описать доступ по 'Ролям' и 'Пользователям' к какой либо записи
    // Мы может сразу воспользоваться классом AccessDescriptor и создать его экземпляр
    // Под капотом он сам определит набор обработчиков, которые будет использовать
    $access_descriptor = new AccessDescriptor(['users', 'roles']);

    // Дадим доступ пользователям с идентификаторами 1 и 5 и всем
    // пользователям с ролью "Работник" ('employee')
    $access_descriptor->addAccess(['1', '5'], 'users');
    $access_descriptor->addAccess(['employee'], 'roles');

    // Получим значение, которое можно куда то сохранить
    $access_value = $access_descriptor->__toString();

    // ...

    // Создадим экземпляр AccessDescriptor из сохранённых значений
    $access_descriptor = new AccessDescriptor(['users', 'roles'], $access_value);

    // Теперь для пользователя 'Dave' мы проверим его доступ к записи
    $access_result = $access_descriptor->access(User::dave());

    // Если мы попытаемся создать AccessDescriptor для недопустимого набора обработчков,
    // а потом провреим доступ, то произойдёт исключение (обработчика 'abdc' не существует)
    try {
      $access_descriptor = new AccessDescriptor(['users', 'abdc']);
      $access_descriptor->access(User::dave());
    } catch (\Exception $ex) {
      $message = $ex->getMessage();
    }

    $end = '';

  }

}
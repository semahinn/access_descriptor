# Описание
AccessDescriptor это объект, который описывает данные о доступе определённых СУБЪЕКТОВ доступа по разным операциям ('view', 'edit', 'delete' и т.д.) к определённым ОБЪЕКТАМ доступа.
Например, субъектами доступа в нашем случае являются пользователи, роли и ещё что либо, определяющее КАК именно будет вычисляться доступ.

# Использование
Все примеры можно найти в файле tests/Test.php

1. По-умолчанию все обработчики доступа описываются в папке Plugin/AccessDescriptorHandler. Это можно переопределить, создав свой "Менеджер обработчиков", который должен реализовывать HandlerPluginManagerInterface. Хорошим решением будет унаследоваться от класса HandlerPluginManager.
```php
class TestHandlerPluginManager extends HandlerPluginManager
{
   public function __construct(array $namespaces, EventDispatcherInterface $event_dispatcher)
   { 
     parent::__construct($namespaces, $event_dispatcher);
     $this->subdir = "Dir\\SubDir";
   }
}
```
2. Вы должны зарегистрировать "Менеджер обработчиков" у себя в контейнере. В тестовом примере Test.php показывается как это сделать. Так, я использую класс HandlerPluginManager, т.к. меня устраивает стандартное поведение.
```php
$container->register(HandlerPluginManager::class, HandlerPluginManager::class)
  // Вместо Snr\AccessDescriptor\Tests укажите пространство имён вашего проекта,
  // Вместо $root/tests - соответстующую директорию
  ->addArgument(["Snr\AccessDescriptor\Tests" => "$root/tests"])
  ->addArgument(new Reference('event_dispatcher'));
```

3. Обрботчики доступа хранятся в папке, указанной в HandlerPluginManager (в нашем случае это Plugin/AccessDescriptorHandler). Чтобы описать один из них, необходимо создать класс, наследующий базовый HandlerPlugin. В примере ниже мы описали обработчик с логикой вычисления доступа для субъекта 'users' (Пользователи).
```php
/**
 * @AccessDescriptorHandler(
 *   id = "users",
 *   label = "Пользователи",
 *   description = "Пользователи",
 *   subjects = {
 *     "users",
 *   },
 * )
 */
class Users extends HandlerPlugin {

  /**
   * {@inheritdoc}
   */
  public function access(AccessDescriptorInterface $access_descriptor, $account, string $operation = 'all') {
    // ...
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginManager() {
    // Должен возвращать экземпляр HandlerPluginManagerInterface из вашего контейнера
  }
}
```

4. Один обработчик может описывать логику доступа сразу для нескольких субъектов. 
```php
/**
 * @AccessDescriptorHandler(
 *   id = "users_or_roles",
 *   label = "Пользователи и роли",
 *   description = "Пользователи и роли",
 *   subjects = {
 *     "users",
 *     "roles",
 *   },
 * )
 */
class UsersOrRoles extends HandlerPlugin
{
  /**
   * {@inheritdoc}
   */
  public function access(AccessDescriptorInterface $access_descriptor, $account, string $operation = 'all') {
    // ...
    return new AccessResultNeutral();
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginManager() {
    // Должен возвращать экземпляр HandlerPluginManagerInterface из вашего контейнера
  }
}
```
4. Теперь мы может создавать экземпляры AccessDescriptor для вычисления значения доступа. Здесь написано, что он работает с субъектами 'users' и 'roles'. Т.е. для этих субъектов обязательно должны быть описаны обработчики (как в примерах выше).
```php
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

    // Если мы попытаемся создать AccessDescriptor для недопустимого набора обработчков,
    // а потом провреим доступ, то произойдёт исключение (обработчика 'abdc' не существует)
    try {
      $access_descriptor = new AccessDescriptor(['users', 'abdc']);
      $access_descriptor->access(User::dave());
    } catch (\Exception $ex) {
      $message = $ex->getMessage();
    }
```

5. Вызвав метод access у экземпляра AccessDescriptor мы запускаем выполение логики во всех обработчиках. Если в определении доступа участвует несколько обработчиков, то их результаты объединяются с помощью логического ИЛИ.
```php
    // Теперь для пользователя 'Dave' мы проверим его доступ к записи
    $access_result = $access_descriptor->access(User::dave());
```

6. Если мы попытаемся создать AccessDescriptor для недопустимого набора обработчков, а потом проверим доступ, то произойдёт исключение (обработчика для субъекта 'abdc' не существует).
```php

    // Если мы попытаемся создать AccessDescriptor для недопустимого набора обработчков,
    // а потом проверим доступ, то произойдёт исключение (обработчика для субъекта 'abdc' не существует)
    try {
      $access_descriptor = new AccessDescriptor(['users', 'abdc']);
      $access_descriptor->access(User::dave());
    } catch (\Exception $ex) {
      $message = $ex->getMessage();
    }
```
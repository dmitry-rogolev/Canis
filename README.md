# Canis

Функционал ролей и разрешений для фреймворка Laravel.

## Содержание

1. [Подключение](#подключение)

    - [Публикация ресурсов](#публикация-ресурсов)

2. [Перед использованием](#перед-использованием)

    - [Миграции](#миграции)
    - [Сидеры](#сидеры)

        + [Сидер ролей](#сидер-ролей)
        + [Сидер разрешений](#сидер-разрешений)

    - [Добавление функционала ролей и разрешений модели](#добавление-функционала-ролей-и-разрешений-модели)
    - [Иерархия ролей](#иерархия-ролей)

3. [Использование](#использование)

    - [Роли](#роли)

        + [Прикрепление роли](#прикрепление-роли)
        + [Отсоединение роли](#отсоединение-роли)
        + [Отсоединение всех ролей](#отсоединение-всех-ролей)
        + [Синхронизация ролей](#синхронизация-ролей)
        + [Проверка наличия роли](#проверка-наличия-роли)
        + [Проверка наличия всех ролей](#проверка-наличия-всех-ролей)
        + [Уровни ролей](#уровни-ролей)
        + [Расширения Blade для ролей](#расширения-blade-для-ролей)
        + [Посредники ролей](#посредники-ролей)

    - [Разрешения](#разрешения)

        + [Прикрепление разрешения](#прикрепление-разрешения)
        + [Отсоединение разрешения](#отсоединение-разрешения)
        + [Отсоединение всех разрешений](#отсоединение-всех-разрешений)
        + [Синхронизация разрешений](#синхронизация-разрешений)
        + [Проверка наличия разрешения](#проверка-наличия-разрешения)
        + [Проверка наличия всех разрешений](#проверка-наличия-всех-разрешений)
        + [Расширения Blade для разрешений](#расширения-blade-для-разрешений)
        + [Посредники разрешений](#посредники-разрешений)

4. [Титры](#титры)
5. [Лицензия](#лицензия)

## Подключение 

Добавьте ссылку на репозиторий в файл `composer.json`

    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:dmitry-rogolev/Canis.git"
        }
    ]

Подключите пакет с помощью команды: 

    composer require dmitryrogolev/canis

### Публикация ресурсов

Вы можете опубликовать все ресурсы разом

    php artisan canis:install 

или по отдельности.

    php artisan canis:install --config
    php artisan canis:install --migrations
    php artisan canis:install --seeders

## Перед использованием

### Миграции 

Пакет добавляет две основные таблицы: ролей и разрешений.

Таблица ролей:

| Столбец     | Назначение                                        |
|-------------|---------------------------------------------------|
| name        | Название роли.                                    |
| slug        | Человеко-понятный идентификатор. Например, admin. |
| description | Описание [опционально].                           |
| level       | Уровень в иерархии ролей.                         |

Таблица разрешений:

| Столбец     | Назначение                                                 |
|-------------|------------------------------------------------------------|
| name        | Название разрешения.                                       |
| slug        | Человеко-понятный идентификатор. Например, `create.users`. |
| description | Описание [опционально].                                    |

Создайте таблицы командой 

    php artisan migrate

### Сидеры

Сидеры заполняют таблицы данными по умолчанию. Вы можете изменить эти данные.

#### Сидер ролей

Поле публикации сидеров, откройте файл `database/seeders/RoleSeeder.php` и измените создаваемые роли. 

Затем заполните таблицу ролей командой 

    php artisan db:seed RoleSeeder

#### Сидер разрешений

Поле публикации сидеров, откройте файл `database/seeders/PermissionSeeder.php` и измените создаваемые разрешения. 

Затем заполните таблицу разрешений командой 

    php artisan db:seed PermissionSeeder

### Добавление функционала ролей и разрешений модели

В примере ниже мы будем добавлять функционал ролей и разрешений модели пользователя. Вы же можете добавить данный функционал любой другой модели или нескольким моделям. 

Добавим трейт `dmitryrogolev\Canis\Traits\HasRolesAndPermissions` и реализуем интерфейс `dmitryrogolev\Canis\Contracts\RoleableAndPermissionable` в модели.

    <?php

    namespace App\Models;

    use dmitryrogolev\Canis\Contracts\RoleableAndPermissionable;
    use dmitryrogolev\Canis\Traits\HasRolesAndPermissions;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Foundation\Auth\User as Model;

    class User extends Model implements RoleableAndPermissionable
    {
        use HasFactory;
        use HasRolesAndPermissions;

Затем создадим модель `App\Models\Role` и унаследуем ее от модели `dmitryrogolev\Canis\Models\Role`.

    <?php

    namespace App\Models;

    use dmitryrogolev\Canis\Models\Role as Model;

    class Role extends Model
    {

    }

Далее добавим метод, возвращающий [полиморфное отношение](https://clck.ru/36JLPn) `многие-ко-многим` роли с нашей моделью.

    <?php

    namespace App\Models;

    use dmitryrogolev\Canis\Models\Role as Model;
    use Illuminate\Database\Eloquent\Relations\MorphToMany;
    use App\Models\User;

    class Role extends Model
    {
        public function users(): MorphToMany
        {
            return $this->roleables(User::class);
        }
    }

Теперь создадим модель `App\Models\Permission` и унаследуем ее от модели `dmitryrogolev\Can\Models\Permission`.

    <?php

    namespace App\Models;

    use dmitryrogolev\Can\Models\Permission as Model;

    class Permission extends Model
    {

    }

Наконец, добавим метод, возвращающий [полиморфное отношение](https://clck.ru/36JLPn) `многие-ко-многим` разрешений с нашей моделью.

    <?php

    namespace App\Models;

    use dmitryrogolev\Can\Models\Permission as Model;
    use Illuminate\Database\Eloquent\Relations\MorphToMany;
    use App\Models\User;

    class Permission extends Model
    {
        public function users(): MorphToMany
        {
            return $this->permissionables(User::class);
        }
    }

### Иерархия ролей

Иерархия ролей призвана упростить логику работы с ролями. Вместо того, чтобы присоединять все необходимые модели роли, достаточно присоединить только одну роль с самым большим уровнем. 

Разберем пример. При регистрации пользователю присоединяются три роли: пользователь, клиент, продавец. Без иерархии ролей потребовалось бы присоединить все три роли.

    $user->attachRole(['user', 'customer', 'seller']);

Но в иерархии ролей мы можем присвоить каждой роли соответствующий уровень, который будет обозначать уровень доступа в приложении.

| Роль     | Уровень |
|----------|:-------:|
| user     |    1    |
| customer |    2    |
| seller   |    3    |

Теперь будет достаточно присоединить пользователю роль с самым большим уровнем.

    $user->attachRole('seller');

После этого пользователь будет иметь все три роли, несмотря на то, что фактически они не были присоединены. 

    if ($user->hasAllRoles(['user', 'customer', 'seller'])) {
        // Пользователь имеет все перечисленные роли.
    }

По умолчанию иерархия ролей включена, но вы можете ее отключить в конфигурации.

    // config/canis.php
    config(['canis.uses.levels' => false]);

    // .env
    CANIS_USES_LEVELS=false

## Использование

### Роли

#### Прикрепление роли

Для присоединения одной роли или множества ролей, можно воспользоваться методом `attachRole`, который принимает идентификатор, slug или модель роли, а также их множество. Если роль фактически была присоединена, метод вернет `true`.

    $user->attachRole($id); // bool
    $user->attachRole('admin'); // bool
    $user->attachRole($role); // bool
    $user->attachRole([$id, 'admin', $role]); // bool

#### Отсоединение роли

Для отсоединения одной роли или множества ролей, можно воспользоваться методом `detachRole`, который принимает идентификатор, slug или модель роли, а также их множество. Если роль фактически была отсоединена, метод вернет `true`.

    $user->detachRole($id); // bool
    $user->detachRole('admin'); // bool
    $user->detachRole($role); // bool
    $user->detachRole([$id, 'admin', $role]); // bool

#### Отсоединение всех ролей

Для отсоединения всех ролей, можно воспользоваться методом `detachAllRoles`. Также можно воспользоваться методом `detachRole` с пустым аргументом. Если роли были фактически отсоединены, метод вернет `true`.

    $user->detachAllRoles(); // bool 
    $user->detachRole(); // bool

#### Синхронизация ролей

Для синхронизации ролей можно воспользоваться методом `syncRoles`, который принимает идентификатор, slug или модель роли, а также их множество.

    $user->syncRoles($id); // void
    $user->syncRoles('admin'); // void
    $user->syncRoles($role); // void
    $user->syncRoles([$id, 'admin', $role]); // void

#### Проверка наличия роли

Для проверки наличия роли у модели, можно воспользоваться методами `hasRole`, `hasOneRole` или `is`, которые принимают идентификатор, slug или модель роли, а также их множество. Если модель имеет переданную роль, метод вернет `true`. Если иерархия ролей включена, метод вернет `true` для всех ролей (даже для тех, которые фактически не присоединены), которые имеют равный или меньший уровень относительно роли модели с самым большим уровнем.

    if ($user->is('admin')) {
        // Передаем slug роли.
    }

    if ($user->hasRole($id)) {
        // Передаем идентификатор роли.
    }

    if ($user->hasOneRole($role)) {
        // Передаем модель роли.
    }

Если передать множество ролей, метод вернет `true` для первой имеющейся у модели роли. 

    if ($user->is([$id, 'admin', $role])) {
        // У пользователя есть, по крайней мере, одна из переданных ролей.
    }

    if ($user->hasRole([$id, 'admin', $role])) {
        // У пользователя есть, по крайней мере, одна из переданных ролей.
    }

    if ($user->hasOneRole([$id, 'admin', $role])) {
        // У пользователя есть, по крайней мере, одна из переданных ролей.
    }

Для проверки наличия у модели одной роли по slug'у доступен магический метод.

    if ($user->isAdmin()) {
        // У пользователя есть роль со slug'ом "admin"
    }

    if ($user->isModerator()) {
        // У пользователя есть роль со slug'ом "moderator"
    }

#### Проверка наличия всех ролей

Для проверки наличия всех переданных ролей у модели, можно воспользоваться методом `hasAllRoles` или методами `hasRole` или `is`, передав им вторым параметром `true`. Они принимают идентификатор, slug или модель роли, а также их множество. Метод возвращает `true` только тогда, когда модель имеет все переданные роли. Если иерархия ролей включена, метод вернет `true` для всех ролей (даже для тех, которые фактически не присоединены), которые имеют равный или меньший уровень относительно роли модели с самым большим уровнем.

    if ($user->is([$id, 'admin', $role], true)) {
        // У пользователя есть все переданные роли.
    }

    if ($user->hasRole([$id, 'admin', $role], true)) {
        // У пользователя есть все переданные роли.
    }

    if ($user->hasAllRoles([$id, 'admin', $role])) {
        // У пользователя есть все переданные роли.
    }

#### Уровни ролей

Уровни ролей создают [иерархию ролей](#иерархия-ролей). 

Для получения роли с самым большим уровнем, можно воспользоваться методом `role`. 

    $user->role(); // Illuminate\Database\Eloquent\Model

Для получения самого большого уровня из всех присоединенных ролей, можно воспользоваться методом `level`.

    $user->level(); // int

#### Расширения Blade для ролей

По умолчанию в Blade зарегистрированы помощники проверки наличия роли и уровня доступа у пользователя. 

Проверка наличия роли.

    @is('admin') // @if(Auth::check() && Auth::user()->hasRole('admin'))
        // у пользователя есть роль admin
    @endis

    @role('admin') // @if(Auth::check() && Auth::user()->hasRole('admin'))
        // у пользователя есть роль admin
    @endrole

Проверка наличия доступа.

    @level(2) // @if(Auth::check() && Auth::user()->level() >= 2)
        // у пользователя уровень 2 или выше
    @endlevel

Вы можете отключить регистрацию этих директив в конфиге. 

    // config/canis.php 
    config(['canis.uses.blade' => false]);

    // .env
    CANIS_USES_BLADE=false

#### Посредники ролей

По умолчанию зарегистрированы посредники `is` и `role`, проверяющие наличие роли и посредник `level`, проверяющий наличие доступа у пользователя к маршруту. 

Проверка наличия роли у пользователя. 

    Route::get('/', function () {
        //
    })->middleware('is:admin');

    Route::get('/', function () {
        //
    })->middleware('role:admin');

Проверка наличия у пользователя доступа к маршруту.

    Route::get('/', function () {
        // Пользователь имеет уровень доступа 2 или больший.
    })->middleware('level:2'); // level >= 2

Вы можете отключить регистрацию этих посредников в конфиге. 

    // config/canis.php 
    config(['canis.uses.middlewares' => false]);

    // .env
    CANIS_USES_MIDDLEWARES=false

### Разрешения

#### Прикрепление разрешения

Для присоединения одного разрешения или множества разрешений, можно воспользоваться методом `attachPermission`, который принимает идентификатор, slug или модель разрешения, а также их множество. Если разрешение было присоединено, метод вернет `true`.

    $user->attachPermission($id); // bool
    $user->attachPermission('create.users'); // bool
    $user->attachPermission($permission); // bool
    $user->attachPermission([$id, 'create.users', $permission]); // bool

#### Отсоединение разрешения

Для отсоединения одного разрешения или множества разрешений, можно воспользоваться методом `detachPermission`, который принимает идентификатор, slug или модель разрешения, а также их множество. Если разрешение было отсоединено, метод вернет `true`.

    $user->detachPermission($id); // bool
    $user->detachPermission('create.users'); // bool
    $user->detachPermission($permission); // bool
    $user->detachPermission([$id, 'create.users', $permission]); // bool

#### Отсоединение всех разрешений

Для отсоединения всех разрешений, можно воспользоваться методом `detachAllPermissions`. Также можно воспользоваться методом `detachPermission` с пустым аргументом. Если разрешения были отсоединены, метод вернет `true`.

    $user->detachAllPermissions(); // bool 
    $user->detachPermission(); // bool

#### Синхронизация разрешений

Для синхронизации разрешений можно воспользоваться методом `syncPermissions`, который принимает идентификатор, slug или модель разрешения, а также их множество.

    $user->syncPermissions($id); // void
    $user->syncPermissions('create.users'); // void
    $user->syncPermissions($permission); // void
    $user->syncPermissions([$id, 'create.users', $permission]); // void

#### Проверка наличия разрешения

Для проверки наличия разрешения у модели, можно воспользоваться методами `hasPermission`, `hasOnePermission` или `can`, которые принимают идентификатор, slug или модель разрешения, а также их множество. Если модель имеет переданное разрешение, метод вернет `true`. 

    if ($user->can('create.users')) {
        // Передаем slug разрешения.
    }

    if ($user->hasPermission($id)) {
        // Передаем идентификатор разрешения.
    }

    if ($user->hasOnePermission($permission)) {
        // Передаем модель разрешения.
    }

Если передать множество разрешений, метод вернет `true` для первого имеющегося у модели разрешения. 

    if ($user->can([$id, 'create.users', $permission])) {
        // У пользователя есть, по крайней мере, одно из переданных разрешений.
    }

    if ($user->hasPermission([$id, 'create.users', $permission])) {
        // У пользователя есть, по крайней мере, одно из переданных разрешений.
    }

    if ($user->hasOnePermission([$id, 'create.users', $permission])) {
        // У пользователя есть, по крайней мере, одно из переданных разрешений.
    }

Для проверки наличия у модели одного разрешения по slug'у доступен магический метод.

    if ($user->canCreateUsers()) {
        // У пользователя есть разрешение со slug'ом "create.users"
    }

    if ($user->canEditUsers()) {
        // У пользователя есть разрешение со slug'ом "edit.users"
    }

#### Проверка наличия всех разрешений

Для проверки наличия всех переданных разрешений у модели, можно воспользоваться методом `hasAllPermissions` или методами `hasPermission` или `can`, передав им вторым параметром `true`. Они принимают идентификатор, slug или модель разрешения, а также их множество. Метод возвращает `true` только тогда, когда модель имеет все переданные разрешения. 

    if ($user->can([$id, 'create.users', $permission], true)) {
        // У пользователя есть все переданные разрешения.
    }

    if ($user->hasPermission([$id, 'create.users', $permission], true)) {
        // У пользователя есть все переданные разрешения.
    }

    if ($user->hasAllPermissions([$id, 'create.users', $permission])) {
        // У пользователя есть все переданные разрешения.
    }

#### Расширения Blade для разрешений

По умолчанию в Blade зарегистрированы помощники проверки наличия разрешений.

Проверка наличия разрешения.

    @can('create.users') // @if(Auth::check() && Auth::user()->hasPermission('create.users'))
        // у пользователя есть разрешение "create.users"
    @endcan

    @permission('create.users') // @if(Auth::check() && Auth::user()->hasPermission('create.users'))
        // у пользователя есть разрешение "create.users"
    @endpermission

Вы можете отключить регистрацию этих директив в конфиге. 

    // config/canis.php 
    config(['canis.uses.blade' => false]);

    // .env
    CANIS_USES_BLADE=false

#### Посредники разрешений

По умолчанию зарегистрирован посредник `permission`, проверяющие наличие у модели разрешения.

    Route::get('/', function () {
        //
    })->middleware('permission:create.users');

Вы можете отключить регистрацию этого посредника в конфиге. 

    // config/canis.php 
    config(['canis.uses.middlewares' => false]);

    // .env
    CANIS_USES_MIDDLEWARES=false

## Титры

Данный пакет вдохновлен и разработан на основе [jeremykenedy/laravel-roles](https://github.com/jeremykenedy/laravel-roles).

## Лицензия 

Этот пакет является бесплатным программным обеспечением, распространяемым на условиях [лицензии MIT](./LICENSE).

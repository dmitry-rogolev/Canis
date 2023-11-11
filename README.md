# Canis

Функционал ролей и разрешений для фреймворка Laravel.

## Содержание

1. [Установка](#установка)
    
    - [Composer](#composer) 
    - [Публикация ресурсов](#публикация-ресурсов)
    - [Добавление функционала в модель](#добавление-функционала-в-модель)
    - [Миграции и сидеры](#миграции-и-сидеры)
    - [Миграции](#миграции)

2. [Применение](#применение)

    - [Роли](#роли)

        + [Создание ролей](#создание-ролей)
        + [Прикрепление, отсоединение и синхронизация ролей](#прикрепление-отсоединение-и-синхронизация-ролей)
        + [Проверка ролей](#проверка-ролей)
        + [Уровни ролей](#уровни-ролей)

    - [Разрешения](#разрешения) 

        + [Создание разрешений](#создание-разрешений)
        + [Прикрепление, отсоединение и синхронизация разрешений](#прикрепление-отсоединение-и-синхронизация-разрешений)
        + [Проверка разрешений](#проверка-разрешений)

    - [Расширения Blade](#расширения-blade)
    - [Посредники](#посредники)

4. [Титры](#титры)
5. [Лицензия](#лицензия)

## Установка 

### Composer

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

#### Публикация всех ресурсов

    php artisan canis:install 

#### Публикация ресурсов по отдельности

Конфигурация

    php artisan canis:install --config

Миграции

    php artisan canis:install --migrations

Сидеры 

    php artisan canis:install --seeders

### Добавление функционала в модель

Включите трейт `dmitryrogolev\Canis\Traits\HasRolesAndPermissions` и реализуйте интерфейс `dmitryrogolev\Canis\Contracts\RoleableAndPermissionable` в модели.

    <?php

    namespace App\Models;

    // use Illuminate\Contracts\Auth\MustVerifyEmail;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Illuminate\Notifications\Notifiable;
    use Laravel\Sanctum\HasApiTokens;
    use dmitryrogolev\Canis\Contracts\RoleableAndPermissionable;
    use dmitryrogolev\Canis\Traits\HasRolesAndPermissions;

    class User extends Authenticatable implements RoleableAndPermissionable 
    {
        use HasApiTokens, 
            HasFactory, 
            Notifiable, 
            HasRolesAndPermissions;

Трейт `dmitryrogolev\Canis\Traits\HasRolesAndPermissions` добавляет модели возможность работы с ролями и разрешениями.

### Миграции 

Создайте таблицы в базе данных

    php artisan migrate

## Применение

### Роли

#### Создание ролей 

    $adminRole = config('canis.models.role')::create([
        'name' => 'Admin',
        'slug' => 'admin',
        'description' => '',
        'level' => 5,
    ]);

    $moderatorRole = config('canis.models.role')::create([
        'name' => 'Forum Moderator',
        'slug' => 'forum.moderator',
    ]);

#### Прикрепление, отсоединение и синхронизация ролей 

    $user = config('canis.models.user')::find($id);

    $user->attachRole('moderator'); // Присоединяем роль
    $user->detachRole($adminRole); // Отсоединяем роль
    $user->detachAllRoles(); // Отсоединяем все роли
    $user->syncRoles(['admin', 'moderator', 'user']); // Синхронизируем роли

#### Проверка ролей

Проверка наличия у пользователя хотябы одной роли

    if ($user->is('admin')) {
        // 
    }

    if ($user->hasRole([$role, 24, 56])) {
        // 
    }

    if ($user->hasOneRole('user,moderator,23,456')) {
        // 
    }

    if ($user->isAdmin()) {
        // Магический метод
    }

Проверка наличия нескольких ролей

    if ($user->is(['admin', 'moderator'], true)) {
        // 
    }

    if ($user->hasRole('admin|moderator|787', true)) {
        // 
    }

    if ($user->hasAllRoles('admin', 567, $role)) {
        // 
    }

#### Уровни ролей

Уровни ролей создают иерархию ролей.

    if ($user->level() > 4) {
        //
    }

### Разрешения

#### Создание разрешений 

    $canCreateUsers = config('canis.models.permission')::create([
        'name' => 'Can Create Users',
        'slug' => 'create.users',
        'description' => '',
        'model' => 'App\Models\User',
    ]);

    $canDeleteUsers = config('canis.models.permission')::create([
        'name' => 'Can Delete Users',
        'slug' => 'delete.users',
    ]);

#### Прикрепление, отсоединение и синхронизация разрешений 

    $user = config('canis.models.user')::find($id);

    $user->attachPermission('view.users'); // Присоединяем разрешение
    $user->detachPermission($canDeleteUsers); // Отсоединяем разрешение
    $user->detachAllPermissions(); // Отсоединяем все разрешения
    $user->syncPermissions(['view.users', 'create.users', 'delete.users']); // Синхронизируем разрешения

#### Проверка разрешений

Проверка наличия у пользователя хотябы одного разрешения

    if ($user->can('create.users')) {
        // 
    }

    if ($user->hasPermission([$permission, 24, 56])) {
        // 
    }

    if ($user->hasOnePermission('edit.users,delete.users,23,456')) {
        // 
    }

    if ($user->canDeleteUsers()) {
        // Магический метод
    }

Проверка наличия нескольких разрешений

    if ($user->can(['edit.users', 'delete.users'], true)) {
        // 
    }

    if ($user->hasPermission('edit.users|delete.users|787', true)) {
        // 
    }

    if ($user->hasAllPermissions('edit.users', 567, $permission)) {
        // 
    }

### Расширения Blade 

    @is('admin') // @if(Auth::check() && Auth::user()->hasRole('admin'))
        // у пользователя есть роль admin
    @endis

    @role('admin') // @if(Auth::check() && Auth::user()->hasRole('admin'))
        // у пользователя есть роль admin
    @endrole

    @level(2) // @if(Auth::check() && Auth::user()->level() >= 2)
        // у пользователя уровень 2 или выше
    @endlevel

    @can('delete.users') // @if(Auth::check() && Auth::user()->hasPermission('delete.users'))
        // у пользователя есть разерешение delete.users
    @endcan

    @permission('delete.users') // @if(Auth::check() && Auth::user()->hasPermission('delete.users'))
        // у пользователя есть разерешение delete.users
    @endpermission

### Посредники 

Вы можете защитить роуты

    Route::get('/', function () {
        //
    })->middleware('role:admin');

    Route::get('/', function () {
        //
    })->middleware('level:2'); // level >= 2

    Route::get('/', function () {
        //
    })->middleware('role:admin', 'level:2'); // level >= 2 and Admin

    Route::group(['middleware' => ['role:admin']], function () {
        //
    });

    Route::get('/', function () {
        //
    })->middleware('permission:create.users');

    Route::get('/', function () {
        // can - это синоним permission
    })->middleware('can:create.users');

    Route::get('/', function () {
        //
    })->middleware('permission:create.users', 'can:delete.users'); 

    Route::group(['middleware' => ['can:create.users']], function () {
        //
    });

## Титры

Данный пакет вдохновлен и разработан на основе [jeremykenedy/laravel-roles](https://github.com/jeremykenedy/laravel-roles).

## Лицензия 

Этот пакет является бесплатным программным обеспечением, распространяемым на условиях [лицензии MIT](./LICENSE).
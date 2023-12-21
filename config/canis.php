<?php

/**
 * Конфигурация Canis.
 *
 * @version 0.0.2
 *
 * @author Dmitry Rogolev <work.drogolev@internet.ru>
 * @license MIT
 */

return [

    /**
     * * Имена таблиц, которые создает пакет.
     *
     * Пакет использует полиморфные отношения многие-ко-многим.
     *
     * @link https://clck.ru/36JLPn Полиморфные отношения многие-ко-многим
     */
    'tables' => [
        // Таблица ролей.
        'roles' => env('CANIS_TABLES_ROLES', 'roles'),

        // Промежуточная таблица, которая соединяет модели, использующие трейт HasRoles, с ролями.
        'roleables' => env('CANIS_TABLES_ROLEABLES', 'roleables'),

        // Таблица разрешений.
        'permissions' => env('CANIS_TABLES_PERMISSIONS', 'permissions'),

        // промежуточная таблица, которая соединяет модели, использующие трейт HasPermissions с разрешениями.
        'permissionables' => env('CANIS_TABLES_PERMISSIONABLES', 'permissionables'),
    ],

    /**
     * * Имена полиморфной связи моделей.
     *
     * Используется в промежуточной таблице для полей {relation_name}_id и {relation_name}_type.
     * Например, permissionable_id и permissionable_type.
     *
     * В поле {relation_name}_id указывается идентификатор модели, которая связывается с разрешением.
     * В поле {relation_name}_type указывается полное название модели,
     * например "\App\Models\User", которая связывается с разрешением.
     *
     * @link https://clck.ru/36JLPn Полиморфные отношения многие-ко-многим
     */
    'relations' => [
        'roleable' => env('CANIS_RELATIONS_ROLEABLE', 'roleable'),
        'permissionable' => env('CANIS_RELATIONS_PERMISSIONABLE', 'permissionable'),
    ],

    /**
     * * Имя первичного ключа моделей.
     *
     * Первичный ключ - это поле в таблице, которое хранит уникальное значение,
     * по которому можно явно идентифицировать ту или иную запись в таблице.
     *
     * @link https://clck.ru/36Ln4n Первичный ключ модели Eloquent
     */
    'primary_key' => env('CANIS_PRIMARY_KEY', 'id'),

    /**
     * * Имена моделей, которые используются в пакете.
     */
    'models' => [

        // Роль.
        'role' => env('CANIS_MODELS_ROLE', \dmitryrogolev\Canis\Models\Role::class),

        // Промежуточная модель роли.
        'roleable' => env('CANIS_MODELS_ROLEABLE', \dmitryrogolev\Is\Models\Roleable::class),

        // Разрешение.
        'permission' => env('CANIS_MODELS_PERMISSION', \dmitryrogolev\Can\Models\Permission::class),

        // Промежуточная модель разрешения.
        'permissionable' => env('CANIS_MODELS_PERMISSIONABLE', \dmitryrogolev\Can\Models\Permissionable::class),

        // Модель пользователя.
        'user' => env('CANIS_MODELS_USER', config('auth.providers.users.model')),

    ],

    /**
     * * Имена фабрик.
     */
    'factories' => [

        // Фабрика роли.
        'role' => env('CANIS_FACTORIES_ROLE', \dmitryrogolev\Is\Database\Factories\RoleFactory::class),

        // Фабрика разрешения.
        'permission' => env('CANIS_FACTORIES_PERMISSION', \dmitryrogolev\Can\Database\Factories\PermissionFactory::class),

    ],

    /**
     * * Имена сидеров.
     */
    'seeders' => [

        // Сидер роли.
        'role' => env('CANIS_SEEDERS_ROLE', \dmitryrogolev\Is\Database\Seeders\RoleSeeder::class),

        // Сидер разрешения.
        'permission' => env('CANIS_SEEDERS_PERMISSION', \dmitryrogolev\Can\Database\Seeders\PermissionSeeder::class),

        // Сидер отношений ролей с разрешениями.
        'roles_has_permissions' => env('CANIS_SEEDERS_ROLES_HAS_PERMISSIONS', \dmitryrogolev\Canis\Database\Seeders\RolesHasPermissionsSeeder::class),

    ],

    /**
     * * Строковый разделитель.
     *
     * Используется для разделения строк на подстроки для поля slug.
     */
    'separator' => env('CANIS_SEPARATOR', '.'),

    /**
     * * Флаги.
     */
    'uses' => [

        /**
         * * Использовать ли в моделях uuid вместо обычного id.
         *
         * UUID — это универсальные уникальные буквенно-цифровые идентификаторы длиной 36 символов.
         *
         * @link https://clck.ru/36JNiT UUID
         */
        'uuid' => (bool) env('CANIS_USES_UUID', true),

        /**
         * * Использовать ли программное удаление для моделей.
         *
         * Помимо фактического удаления записей из БД,
         * Eloquent может выполнять «программное удаление» моделей.
         * При таком удалении, они фактически не удаляются из БД.
         * Вместо этого для каждой модели устанавливается атрибут deleted_at,
         * указывающий дату и время, когда она была «удалена».
         *
         * @link https://clck.ru/36JNnr Программное удаление моделей
         */
        'soft_deletes' => (bool) env('CANIS_USES_SOFT_DELETES', false),

        /**
         * * Использовать ли временные метки для моделей.
         *
         * По умолчанию модели Eloquent определяют поля "created_at" и "updated_at",
         * в которых хранятся дата и время создания и изменения модели соответственно.
         *
         * Если вы не хотите, чтобы модели имели временные метки, установите данный флаг в false.
         *
         * @link https://clck.ru/36JNke Временные метки моделей
         */
        'timestamps' => (bool) env('CANIS_USES_TIMESTAMPS', true),

        /**
         * * Использовать ли миграции по умолчанию.
         *
         * Если вы не публикуете или не создаете свои миграции таблиц для этого пакета,
         * то установите данный флаг в true.
         */
        'migrations' => (bool) env('CANIS_USES_MIGRATIONS', false),

        /**
         * * Использовать ли сидеры по умолчанию.
         *
         * Если вы хотите использовать сидеры по умолчанию, установите данный флаг в true.
         */
        'seeders' => (bool) env('CANIS_USES_SEED', false),

        /**
         * * Регистрировать ли директивы blade (can, endcan, permission, endpermission).
         *
         * Директивы can и permission предоставляют одинаковый функционал.
         *
         * Эти директивы применимы только к модели пользователя,
         * использующего трейт "\dmitryrogolev\Can\Traits\HasPermissions".
         *
         * @link https://clck.ru/36Ls42 Директивы Blade
         */
        'blade' => (bool) env('CANIS_USES_BLADE', true),

        /**
         * * Регистрировать ли посредники (can, permission).
         *
         * Посредники can и permission предоставляют одинаковый функционал.
         *
         * Эти посредники применимы только к модели пользователя,
         * использующего трейт "\dmitryrogolev\Can\Traits\HasPermissions".
         *
         * @link https://clck.ru/36LsKF Посредники
         */
        'middlewares' => (bool) env('CANIS_USES_MIDDLEWARES', true),

        /**
         * * Следует ли подгружать отношение модели после изменения.
         *
         * По умолчанию после подключения или удаления отношения(-ий) моделей с ролями,
         * отношения будут подгружены заново.
         * Это означает, что модель всегда будет хранить актуальные отношения,
         * однако также это означает увеличение количества запросов к базе данных.
         *
         * Если вы делаете много операций с ролями,
         * рекомендуется отключить данную функцию для увеличения производительности.
         */
        'load_on_update' => (bool) env('CANIS_USES_LOAD_ON_UPDATE', true),

        /**
         * * Следует ли расширять метод "is" модели Eloquent.
         *
         * Метод is по умолчанию сравнивает две модели.
         * Трейт HasRoles расширяет данный метод.
         * Это означает, что данным методом по прежнему можно будет пользоваться для сравнения моделей,
         * но, если передать идентификатор, slug или модель роли, то будет вызван метод hasRole,
         * проверяющий наличие роли у модели.
         *
         * Если вы не хотите, чтобы данный метод был расширен, установите данный флаг в false.
         *
         * @link https://clck.ru/36LeCR Метод is модели Eloquent
         */
        'extend_is_method' => (bool) env('CANIS_USES_EXTEND_IS_METHOD', true),

        /**
         * * Следует ли расширять метод "can" интерфейса "Illuminate\Contracts\Auth\Access\Authorizable".
         *
         * Например, модель "Illuminate\Foundation\Auth\User" реализует данный интерфейс.
         *
         * Метод can по умолчанию авторизует действие модели.
         * Трейт HasPermissions расширяет данный метод.
         * Это означает, что данным методом по прежнему можно будет пользоваться для авторизации действий модели,
         * но, если передать идентификатор, slug или модель разрешения, то будет вызван метод hasPermission,
         * проверяющий наличие разрешения у модели.
         *
         * Если вы не хотите, чтобы данный метод был расширен, установите данный флаг в false.
         *
         * @link https://clck.ru/36SAPk Авторизация действий с помощью политик
         */
        'extend_can_method' => (bool) env('CANIS_USES_EXTEND_CAN_METHOD', true),

        /**
         * * Использовать ли иерархию ролей на основе уровней.
         *
         * Иерархия подразумевает, что вышестоящая в иерархии роль имеет допуск
         * к функционалу нижестоящих относительно нее ролей.
         * Например, если модель имеет роль с уровнем 5,
         * то проверка наличия роли с уровнем 3 будет положительна.
         *
         * $user->attachRole($admin); // level 3
         * $user->hasRole($moderator); // level 2 // true
         *
         * Если эта функция включена, то вам не придется добавлять пользователю все роли,
         * которые ему необходимы, а будет достаточно добавить только одну вышестоящую в иерархии роль.
         */
        'levels' => (bool) env('CANIS_USES_LEVELS', true),

    ],
];

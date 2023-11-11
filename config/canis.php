<?php 

/**
 * Конфигурация Canis
 * 
 * @version 0.0.1
 * @author Dmitry Rogolev <work.drogolev@internet.ru>
 * @license MIT
 */

return [

    /**
     * * Подключение к БД, которое должен использовать пакет.
     * 
     * Список возможных подключений определен в файле конфигурации "config/database.php".
     * По умолчанию используется подключинение к приложению по умолчанию.
     * 
     * @link https://clck.ru/36LkBo Конфигурирование БД
     */
    'connection' => env('CANIS_CONNECTION', config('database.default', null)), 

    /**
     * * Имена таблиц, которые создает пакет. 
     * 
     * Пакет использует полиморфные отношения многие-ко-многим.
     * 
     * Определяются следующие таблицы: 
     * 
     * 1). таблица ролей и промежуточная таблица, 
     * которая соединяет модели, использующие трейт HasRoles, с ролями;
     * 
     * 2). таблица разрешений и промежуточная таблица, 
     * которая соединяет модели, использующие трейт HasPermissions с разрешениями;
     * 
     * @link https://clck.ru/36JLPn Полиморфные отношения многие-ко-многим
     */
    'tables' => [
        'roles' => env('CANIS_TABLES_ROLES', 'roles'), 
        'roleables' => env('CANIS_TABLES_ROLEABLES', 'roleables'), 
        'permissions' => env('CANIS_TABLES_PERMISSIONS', 'permissions'), 
        'permissionables' => env('CANIS_TABLES_PERMISSIONABLES', 'permissionables'), 
    ], 

    /**
     * * Имена полиморфной связи моделей.
     * 
     * Используется в промежуточной таблице для полей {relation_name}_id и {relation_name}_type.
     * Например, permissionable_id и permissionable_type.
     * 
     * В поле {relation_name}_id указывается идентфикатор модели, которая связывается с разрешением.
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
     * * Имя первичного ключа моделей
     * 
     * Первичный ключ - это поле в таблице, которое хранит уникальное значение, 
     * по которому можно явно идентфицировать ту или иную запись в таблице.
     * 
     * @link https://clck.ru/36Ln4n Первичный ключ модели Eloquent
     */
    'primary_key' => env('CANIS_PRIMARY_KEY', 'id'), 

    /**
     * * Имена моделей, которые используются в пакете.
     */
    'models' => [

        // Роль
        'role' => env('CANIS_MODELS_ROLE', \dmitryrogolev\Canis\Models\Role::class),
        
        // Промежуточная модель
        'roleable' => env('CANIS_MODELS_ROLEABLE', \dmitryrogolev\Is\Models\Roleable::class), 

        // Разрешение
        'permission' => env('CANIS_MODELS_PERMISSION', \dmitryrogolev\Can\Models\Permission::class),
        
        // Промежуточная модель
        'permissionable' => env('CANIS_MODELS_PERMISSIONABLE', \dmitryrogolev\Can\Models\Permissionable::class), 

        // Пользователь по умолчанию
        'user' => env('CANIS_MODELS_USER', config('auth.providers.users.model')), 

    ], 

    /**
     * * Имена фабрик, которые используются в пакете.
     */
    'factories' => [

        // Фабрика роли
        'role' => env('CANIS_FACTORIES_ROLE', \dmitryrogolev\Is\Database\Factories\RoleFactory::class), 

        // Фабрика разрешения
        'permission' => env('CANIS_FACTORIES_PERMISSION', \dmitryrogolev\Can\Database\Factories\PermissionFactory::class), 

    ], 

    /**
     * * Имена сидеров, которые использутся в пакете.
     */
    'seeders' => [

        // Сидер роли
        'role' => env('CANIS_SEEDERS_ROLE', \dmitryrogolev\Is\Database\Seeders\RoleSeeder::class), 

        // Сидер разрешения
        'permission' => env('CANIS_SEEDERS_PERMISSION', \dmitryrogolev\Can\Database\Seeders\PermissionSeeder::class), 

        // Сидер отношений ролей с разрешениями
        'roles_has_permissions' => env('CANIS_SEEDERS_ROLES_HAS_PERMISSIONS', \dmitryrogolev\Canis\Database\Seeders\RolesHasPermissionsSeeder::class), 
        
    ], 

    /**
     * * Строковый разделитель. 
     * 
     * Используется для раделения строк на подстроки для поля slug.
     */
    'separator' => env('CANIS_SEPARATOR', '.'), 

    /**
     * * Флаги
     */ 
    'uses' => [

        /**
         * * Использовать ли в моделях uuid вместо обычного id.
         * 
         * UUID — это универсальные уникальные буквенно-цифровые идентификаторы длиной 36 символов.
         * 
         * @link https://clck.ru/36JNiT UUID
         */
        'uuid' => env('CANIS_USES_UUID', true), 

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
        'soft_deletes' => env('CANIS_USES_SOFT_DELETES', false), 

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
        'timestamps' => env('CANIS_USES_TIMESTAMPS', true), 

        /**
         * * Использовать ли миграции по умолчанию.
         * 
         * Если вы не публикуете или не создаете свои миграции таблиц для этого пакета, 
         * то установите данный флаг в true.
         */
        'migrations' => env('CANIS_USES_MIGRATIONS', false), 

        /**
         * * Использовать ли сидеры по умолчанию.
         * 
         * Если вы хотитите использовать сидеры по умолчанию, установите данный флаг в true.
         */
        'seeders' => env('CANIS_USES_SEED', false), 

        /**
         * * Регистрировать ли дериктивы blade (can, endcan, permission, endpermission).
         * 
         * Директивы can и permission предоставляют одинаковый функционал. 
         * 
         * Эти дериктывы применимы только к модели пользователя, 
         * использующего трейт "\dmitryrogolev\Can\Traits\HasPermissions".
         * 
         * @link https://clck.ru/36Ls42 Директивы Blade
         */
        'blade' => env('CANIS_USES_BLADE', true), 

        /**
         * * Регистировать ли посредники (can, permission).
         * 
         * Посредники can и permission предоставляют одинаковый функционал.
         * 
         * Эти посредники применимы только к модели пользователя, 
         * использующего трейт "\dmitryrogolev\Can\Traits\HasPermissions".
         * 
         * @link https://clck.ru/36LsKF Посредники
         */
        'middlewares' => env('CANIS_USES_MIDDLEWARES', true), 

        /**
         * * Следует ли подгружать отношение модели после изменения. 
         * 
         * По умолчанию после подключения или удаления отношения(-ий) моделей с ролями, 
         * отношения будут подгружены заного. 
         * Это означает, что модель всегда будет хранить актуальные отношения, 
         * однако также это означает увеличение количества запросов к базе данных. 
         * 
         * Если вы делаете много опираций с ролями, 
         * рекомендуется отключить данную функцию для увеличения производительности.
         */
        'load_on_update' => env('CANIS_USES_LOAD_ON_UPDATE', true), 

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
        'extend_is_method' => env('CANIS_USES_EXTEND_IS_METHOD', true), 

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
        'extend_can_method' => env('CANIS_USES_EXTEND_CAN_METHOD', true), 

        /**
         * * Использовать ли иерархию ролей на основе уровней. 
         * 
         * Иерархия подразумевает, что вышестоящая в иерархии роль иммеет допуск 
         * к функционалу нижестоящих относительно нее ролей.
         * Например, если модель имеет роль с уровенем 5, 
         * то проверка наличия роли с уровнем 3 будет положительна. 
         * 
         * $user->attachRole($admin); // level 3
         * $user->hasRole($moderator); // level 2 // true 
         * 
         * Если эта функция включена, то вам не придется добалять пользователю все роли, 
         * которые ему необходимы, а будет достаточно добавить только одну вышестоящую в иерархии роль.
         */
        'levels' => env('CANIS_USES_LEVELS', true), 

    ], 
];

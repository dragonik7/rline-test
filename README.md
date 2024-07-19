# API для Сервиса Файлообменника

Этот репозиторий содержит реализацию API для сервиса файлообменника, использующего фреймворк Laravel. API предоставляет функционал для регистрации пользователей, управления файлами и директориями, шаринга файлов и контроля доступа.

## Функциональные Требования

API включает следующие функции:

1. **Регистрация и Аутентификация Пользователей**
    - Пользователи могут регистрироваться и проходить аутентификацию для доступа к сервису.

2. **Управление Директориями**
    - Создание и удаление директорий.
    - При удалении директории удаляются все файлы внутри неё.

3. **Управление Файлами**
    - Загрузка одного или нескольких файлов в указанную директорию.
    - Удаление файлов.
    - Переименование файлов и директорий.
    - Просмотр информации о файле (размер, дата загрузки).
    - Скрытие файлов от публичного доступа или шаринг файлов для публичного доступа.
    - Показ текущего занятого пространства на диске.

4. **Скачивание Файлов**
    - Скачивание файлов по уникальной ссылке, доступной как авторизованным, так и неавторизованным пользователям.

## Начало Работы

### Необходимые Условия

- Docker
- Docker Compose

### Установка

1. **Клонирование Репозитория**
   ```bash
   git clone https://github.com/dragonik7/rline-test
   cd rline-test
   ```

2. **Настройка Переменных Окружения**
    - Скопируйте файл `.env.example` в `.env`
    - Обновите переменные окружения, включая настройки базы данных.

3. **Сборка и Запуск Контейнеров**
   ```bash
   docker-compose up -d
   ```

### Запуск Миграций

После настройки контейнеров выполните миграции базы данных:

```bash
docker-compose exec app php artisan migrate
```

### Запуск Тестов

Решение включает тесты для проверки функциональности. Запустите тесты с помощью следующей команды:

```bash
docker-compose exec app php artisan test
```

## Маршруты API

### Файлы

```php
Route::group(['prefix' => '/files'], function () {
    Route::get('download/{uniqueLink}', [FileController::class, 'download'])->name('files.download');
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('', [FileController::class, 'upload'])->name('files.upload');
        Route::get('user/current-space', [FileController::class, 'getCurrentSpace'])->name('files.get-current-space');
        Route::patch('{id}/rename', [FileController::class, 'rename'])->name('files.rename');
        Route::delete('{id}', [FileController::class, 'destroy'])->name('files.destroy');
        Route::get('{id}', [FileController::class, 'show'])->name('files.show');
        Route::patch('{id}/toggle-public', [FileController::class, 'togglePublic'])->name('files.toggle-public');
    });
});
```

### Пользователи

```php
Route::group(['prefix' => '/user'], function (){
    Route::post('register', [UserController::class, 'register'])->name('user.register');
    Route::post('login', [UserController::class, 'login'])->name('user.login');
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [UserController::class, 'logout'])->name('user.logout');
    });
});
```

### Директории

```php
Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'directories'], function () {
    Route::post('', [DirectoryController::class, 'store'])->name('directories.store');
    Route::patch('{id}/rename', [DirectoryController::class, 'rename'])->name('directories.rename');
    Route::delete('{id}', [DirectoryController::class, 'destroy'])->name('directories.destroy');
});
```

### Коллекция Postman

Коллекция Postman находится в корневом каталоге репозитория. Импортируйте файл `File Sharing API.postman_collection.json` в Postman для просмотра и тестирования всех доступных конечных точек.

## Качество Кода

Реализация следует принципам SOLID для обеспечения чистого и поддерживаемого кода. Архитектура спроектирована так, чтобы быть масштабируемой и легко расширяемой.

## База Данных

Сервис использует базу данных, работающую в контейнере Docker. Конфигурация базы данных находится в файле `.env`. Обновите учетные данные по мере необходимости.

## Развертывание

Развертывание и запуск приложения упрощены с помощью Docker. Используйте следующую команду для запуска приложения:

```bash
docker-compose up -d
```

Эта команда соберет и запустит необходимые контейнеры, делая API доступным.

## Внесение Вкладов

Вклады приветствуются! Пожалуйста, форкните репозиторий и создайте пулл-реквест с вашими изменениями. Убедитесь, что ваш код соответствует установленному стилю кода и включает тесты для любой новой функциональности.

## Лицензия

Этот проект лицензирован под лицензией MIT. Смотрите файл `LICENSE` для подробностей.

## Контакты

Для любых вопросов или поддержки, пожалуйста, откройте issue в репозитории или свяжитесь с мейнтейнером.

---

Спасибо за использование API для Сервиса Файлообменника!
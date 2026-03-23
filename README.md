##  Установка и запуск

### Требования
- PHP 8.1+
- PostgreSQL
- Composer

### Установка
```bash
git clone https://github.com/lampyris/raberu.git
cd raberu
composer install
createdb raberu
echo "DATABASE_URL=\"postgresql://postgres@127.0.0.1:5432/raberu\"" > .env.local
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
php -S 127.0.0.1:8000 -t public
```

## API Методы

### GET /api/users/top

Топ-3 пользователей по сумме заказов.

Запрос:
```bash
curl http://127.0.0.1:8000/api/users/top
```

Ответ:
```json
[{"userId":1,"totalAmount":"12500"},{"userId":2,"totalAmount":"9800"},{"userId":3,"totalAmount":"5400"}]
```

### GET /api/user?id={id}

Получение пользователя. Защищено от SQL-инъекций.

Запрос:
```bash
curl "http://127.0.0.1:8000/api/user?id=1"
```

Ответ:
```json
{"id":1,"name":"User 1"}
```

Ошибки: 400 Invalid user ID, 404 User not found

### POST /api/vacancies/{id}/apply

Отклик на вакансию. Проверяет существование, активность, уникальность.

Запрос:
```bash
curl -X POST http://127.0.0.1:8000/api/vacancies/1/apply
```

Ответ:
```json
{"status":"ok"}
```

Ошибки: 404 Vacancy not found, 400 Vacancy is not active, 400 Already applied

## Структура
```
src/
├── Controller/
│   ├── UserController.php
│   └── VacancyController.php
├── Repository/
│   └── UserRepository.php
├── Entity/
│   ├── User.php
│   ├── Order.php
│   ├── Vacancy.php
│   └── Application.php
└── DataFixtures/
    └── AppFixtures.php
```

# Installation

### Install dependencies for Vue.js
```
cd web/ && npm install
```

### Install dependencies for Laravel
```
cd api/ && composer install
```

<br>

# Run servers

### Start Vue.js server
```
cd web/ && clear && npm run serve
```

### Start laravel server
```
cd api/ && php artisan cache:clear && php artisan route:clear && php artisan config:clear && php artisan view:clear && clear && php artisan serve
```

### Run laravel-echo server
```
cd socket.io/ && clear && npx laravel-echo-server start
```

### Run laravel queue
```
cd api/ && clear && php artisan queue:listen --timeout=0
```

<br>

# DB manage

1. Create `chat_bug_fix` database.
2. Migrate tables to the database using ```cd api/ && php artisan migrate```.
3. Head over to your sql and add all the required lines:

### Users
> INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `stripe_id`, `card_brand`, `card_last_four`, `trial_ends_at`) VALUES (1, 'User', '1', 'user_1@gmail.com', '$2y$10$VDUWJXd5D2xoW6pzHq392eHX8RgEPX.Y3nNRgy3OjG5dwcFJG6avG', NULL, NULL, NULL, NULL), (2, 'User', '2', 'user_2@gmail.com', '$2y$10$b6qX.7xE5Bz/sO1.WlwWVep2JoVNqt09qzsSI9OdTO3X3GkDZ5FtK', NULL, NULL, NULL, NULL);

> INSERT INTO `privacy` (`id`, `messaging`) VALUES (1, 'All'), (2, 'All');

### Chat
> INSERT INTO `chat` (`id`, `from_id`, `to_id`, `text`, `status`, `created_at`, `updated_at`) VALUES (1, 1, 2, 'Hello', 0, '2021-06-14 16:08:10', '2021-06-16 14:18:31');

<br>

# Additional information

When your servers are run, user `user_1@gmail.com` and `user_2@gmail.com` as logins. Then type __ANY__ password you want and you will be headed over to the messanger page.

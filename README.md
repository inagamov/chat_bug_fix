### Start vue.js server
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
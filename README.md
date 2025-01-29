# Установка 

Добавить в composer.json
```json
"repositories": [
	{
		"type": "vcs",
		"url": "https://<github/gitlab>.com/<Author>/internal-protocol.git"
	}
],
```

```sh
composer require ledsqsuare/internal-protocol
php artisan vendor:publish --tag=provider
php artisan vendor:publish --tag=config
```


# PHP QuickBooks

## Clone

```bash
git clone https://github.com/prawee/php-quickbooks.git
cd php-quickbooks
composer install
```

## Update environment

- Register and sign in `Quick Books` with <https://developer.intuit.com> 
- Go to `Dashboard` and create an `App`
- Click to `App` looking `Keys & OAuth` part then copy `Client ID` and `Client Secret`
- Copy example and update `.env` 

```bash
cp -R .env.example .env
```

## Running

```bash
php -S localhost:3000 .
```
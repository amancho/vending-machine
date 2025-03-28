# Vending Machine

Vending Machine is an awesome console application that from a few input parameters (product, amount of money) is capable to order a drink.

## Features 
- Insert coins
- Select a product
- Cancel operation
- Show available products
- Show coins stored
- Add products
- Add coins

## Project set up

Install and run the application.

```
sh docker/build
sh docker/composer i --ignore-platform-reqs
sh docker/up
```

Examples of the use of the application.

```
sh docker/console app:show-products
```


Execute phpstan

```
sh docker/phpstan {path}
```

Execute test with code coverage (in HTML format inside /coverage folder)

```
sh docker/test
```

## How it works

You can run these commands from command line at project root folder (or inside docker)
```
sh docker/console app:show-products 
sh docker/console app:show-coins
sh docker/console app:return-coins
```
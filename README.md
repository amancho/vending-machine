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

sh docker/console app:get
sh docker/console app:insert-coin
sh docker/console app:return-coins

sh docker/console app:service-add-product
sh docker/console app:service-add-coin
```

Examples of the use of the application.

First, insert allowed coins
```
sh docker/console app:insert-coin 0.05
sh docker/console app:insert-coin 0.10  
sh docker/console app:insert-coin 0.25
sh docker/console app:insert-coin 1

sh docker/console app:insert-coin 0.50 (fail)
```

Select allowed product 
```
sh docker/console app:get soda
sh docker/console app:get juice
sh docker/console app:get water

sh docker/console app:get coconut (fail)
```
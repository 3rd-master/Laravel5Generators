# 3rd Sense Australia Laravel 5 Generators

Based on the excellent [Generators](https://github.com/laracasts/Laravel-5-Generators-Extended) by Jeffery Way we have added a couple more that we use regularly here at 3rd Sense.

These include:

- make:repository
- make:transformer 

## Usage

### Step 1: Install using `composer`

```
composer require 3rd-sense/generators --dev
```

### Step 2: Add the service provider
Following Jeffery's advice these should only be available in your local development environment, so you don't want to update the production `providers` array in `config/app.php`. Instead, add the provider in `app/Providers/AppServiceProvider.php`, like so:

```php
public function register()
{
    if ($this->app->environment() == 'local') {
        $this->app->register('ThirdSense\Generators\GeneratorsServiceProvider');
    }
}
```

### Step 3: Run `artisan` generators!
You're all set. Run `php artisan` from the console, and you'll see the new commands in the `make:*` namespace section. 

## Examples

- [Generate Repository Classes](#generate-repository-classes)
- [Generate Transformer Classes](#generate-transformer-classes)

### Generate Repository Classes
```
php artisan make:repository UserRepository App/User
```

This will generate 2 files for you in the app/repositories directory, the repository:

```php
<?php

namespace App\Repositories;

use App\User;

/**
 * Class UserRepositoryRepository
 * @package App\Repositories
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * Retrieve all User.
     *
     * @return mixed
     */
    public function all()
    {
        return User::all();
    }

    /**
     * Retrieve a paginated list of User.
     *
     * @param $limit
     */
    public function paginated($limit)
    {
        return User::paginate($limit);
    }

    /**
     * Retrieve a single User by ID.
     *
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return User::find($id);
    }

    /**
     * Create and save a new User.
     *
     * @param $data
     *
     * @return bool
     */
    public function create($data)
    {
        $entity = new User;

        return $this->save($entity, $data);
    }

    /**
     * Update an existing User.
     *
     * @param $id
     * @param $data
     *
     * @return User
     */
    public function update($id, $data)
    {
        $entity = $this->findById($id);

        return $this->save($entity, $data);
    }

    /**
     * Remove/delete exiting User
     *
     * @param $id
     *
     * @return int
     */
    public function destroy($id)
    {
        return User::destroy($id);
    }

    /**
     * Save the User.
     *
     * @param $entity
     * @param $data
     *
     * @return boolean
     */
    protected function save($entity, $data)
    {
        // set model properties

        return $entity->save();
    }
}
```

and the repository interface:

```php
<?php

namespace App\Repositories;

use App\User;

/**
 * Interface UserRepositoryInterface
 * @package App\Repositories
 */
interface UserRepositoryInterface
{
    /**
     * Retrieve all User.
     *
     * @return mixed
     */
    public function all();

    /**
     * Retrieve a paginated list of User.
     *
     * @param $limit
     */
    public function paginated($limit);

    /**
     * Retrieve a single User by ID.
     *
     * @param $id
     *
     * @return mixed
     */
    public function find($id);

    /**
     * Create and save a new User.
     *
     * @param $data
     *
     * @return bool
     */
    public function create($data);

    /**
     * Update an existing User.
     *
     * @param $id
     * @param $data
     *
     * @return User
     *
     */
    public function update($id, $data);

    /**
     * Remove/delete exiting User
     *
     * @param $id
     *
     * @return int
     */
    public function destroy($id);
}
```

Now you just need to register these repositories with your service container. To do this just add the following snippet to your `app/Providers/AppServiceProvider`'s `register` method:

```php
$this->app->bind(UserRepositoryInterface::class, UserRepository::class);
```

and you're good to go.

The other alternative is to create an `App/Providers/RepositoriesServiceProvider` class and place the above code in the `register` method. Remember to add this new service provider to your `config/app.php` `providers` array.

### Generate Transformer Classes
This generator is to be used to create transformers for the [Fractal](http://fractal.thephpleague.com/) package provided by The PHP League

```
php artisan make:transformer UserTransformer App/User
```

This will generate the following tranformer for you in the app/Tranformers directory:

```php
<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * List of resources to possibly include
     *
     * @var  array
     */
    protected $availableIncludes = [];

    /**
     * List of resources to automatically include
     *
     * @var  array
     */
    protected $defaultIncludes = [];

    /**
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id'    =>  $user->id,

            // TODO: transform entity properties

            'links' => [
                [
                    'rel' => 'self',
                    'uri' => route('user.show', ['id' =>  $user->id]),
                ],
                [
                    'rel' => 'list',
                    'uri' => route('user.index'),
                ],
            ],
        ];
    }
}
```

# Seeder
Easy seeding database for Laravel Applications

## Usage

### Step 1: Install Through Composer

```
composer require codingphase/seeder
```

### Step 2: Register Service Provider
Add your new provider to the `providers` array of `config/app.php`:
```php
  'providers' => [
      // ...
      CodingPhase\Seeder\SeederServiceProvider::class,
      // ...
  ],
```

## Usage
Extend your seeders with ModelSeeder:
```php
use CodingPhase\Seeder\ModelSeeder;

class UsersTableSeeder extends ModelSeeder
{
    ...
}
```

Implement run method:
```php
/**
 * Run the database seeds.
 *
 * @return void
 */
public function run
{
    //Example
    $users = $this->seedModel(\App\User::class, function ($user) {
        $user->save();
    });
}
```

##API:
### setAmount($number)
Default amount of seeding resources that are seeded are stored in config. If you want to seed another value of resources, you can. 
```php
$this->setAmount(30)->seedModel(\App\User::class, function ($user) {
    $user->save();
});
```

### setHeader("Text")
Define header before progress bar in output (default is model namespace)
```php
$this->setHeader("Awesome Users")->seedModel(\App\User::class, function ($user) {
    $user->save();
});
```

### setCompact($bool)
Default true. Define style of Progress Bar.  
```php
$this->setAmount(30)->seedModel(\App\User::class, function ($user) {
    $user->save();
});
```

### useData($data)
Set data that will be used to fill resources. It overrides model factory data.
```php
//first user will have name `test` and email `test@test.com`
//25th user will have name `example` and email `example@example.com`
$data = [    
    1 => [
        'name' => 'test',
        'email' => 'test@test.com'
    ],
    
    25 => [
        'name' => 'example
        'email' => 'example@example.com',
    ],
];

$this->useData($data)->seedModel(\App\User::class, function ($user) {
    $user->save();
});
```

##Practical Examples
```php
$admins = [
    1 => [
        'name' => 'test',
        'email' => 'test@test.com',
        'password' => bcrypt('123456')
    ],
    4 => [
        'name' => 'test4',
        'email' => 'test4@test.com',
        'password' => bcrypt('654321')
    ],
];

$this->useData($admins)
    ->setAmount(5)
    ->setHeader("Seeding Admins")
    ->setCompact(false)
    ->seedModel(\App\User::class, function ($user) {
        $user->admin = 1;
        $user->save();
    });

$this->setHeader("Seeding Regular Users")
    ->seedModel(\App\User::class, function ($user) {
        $user->save();
    });
```



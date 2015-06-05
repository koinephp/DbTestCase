Koine DbTestCase
-----------------

Base class for testing database with PDO

**Work in progress**

Code information:

[![Build Status](https://travis-ci.org/koinephp/DbTestCase.png?branch=master)](https://travis-ci.org/koinephp/DbTestCase)
[![Coverage Status](https://coveralls.io/repos/koinephp/DbTestCase/badge.svg?branch=master)](https://coveralls.io/r/koinephp/DbTestCase?branch=master)
[![Code Climate](https://codeclimate.com/github/koinephp/DbTestCase.png)](https://codeclimate.com/github/koinephp/DbTestCase)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/koinephp/DbTestCase/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/koinephp/DbTestCase/?branch=master)

Package information:

[![Latest Stable Version](https://poser.pugx.org/koine/db-test-case/v/stable.svg)](https://packagist.org/packages/koine/db-test-case)
[![Total Downloads](https://poser.pugx.org/koine/db-test-case/downloads.svg)](https://packagist.org/packages/koine/db-test-case)
[![Latest Unstable Version](https://poser.pugx.org/koine/db-test-case/v/unstable.svg)](https://packagist.org/packages/koine/db-test-case)
[![License](https://poser.pugx.org/koine/db-test-case/license.svg)](https://packagist.org/packages/koine/db-test-case)
[![Dependency Status](https://gemnasium.com/koinephp/DbTestCase.png)](https://gemnasium.com/koinephp/DbTestCase)


### Usage

### Db Test Case

In your bootstrap file set up the connection

```php
// tests/bootstrap.php

// [...]

\Koine\PHPUnit\DbTestCase::setConnection($pdoConnection);
```

```php
namespace MyAppTest;

use Koine\PHPUnit\DbTestCase;
use MyApp\BlogService;

/**
 * @author Marcelo Jacobus <marcelo.jacobus@gmail.com>
 */
class DbTestCaseTest extends DbTestCase
{
    public function setUp()
    {
        parent::setUp(); // enclose everything in a transaction
    }

    public function tearDown()
    {
        parent::tearDown(); // rollback the transaction
    }

    /**
     * @test
     */
    public function canCreatePost()
    {
        $service = new BlogService($this->getConnection());

        $service->create(array(
            'title' => 'some title',
            'body'  => 'some body',
        ));

        $this->assertTableCount(1, 'blog_post');
    }

    /**
     * @test
     */
    public function canFindByCategory()
    {
        $helper = $this->createTableHelper('blog_post');

        $helper->insert(array(
            'title'      => 'foo',
            'body'       => 'bar',
            'categoryId' => 1,
        ));

        $helper->insert(array(
            'title'      => 'foo',
            'body'       => 'bar',
            'categoryId' => 2,
        ));

        $service = new BlogService($this->getConnection());
        $records = $service->findByCategoryId(1);

        $this->assertEquals(1, count($records));
    }
}
```



### Table Helper

Table helper is a very simple ORM for creating records for test, updating and
querying a single table.

Setting up

```php
$tableName = 'blog_post';

$tableHelper = new \Koine\DbTestCase\TableHelper\TableHelper(
  $pdo,
  $tableName,
  'id'
);
```

Finding records by conditions

```php
$posts = $tableHelper->findAllBy(array(
  'categoryId' => $categoryId,
));
```

Finding record by id

```php
$post = $tableHelper->find(10);
```
Crating records

```php
$tableHelper->insert(array(
  'title' => 'First blog',
  'body'  => 'Post body',
));
```

Updating

```php
$tableHelper->update(10, array(
  'title' => 'new title',
));
```

Deleting

```php
$tableHelper->delete(10);
```

Counting

```php
$tableHelper->getNumberOfRows();
```

### Installing

#### Installing Via Composer
Append the lib to your requirements key in your composer.json.

```javascript
{
    // composer.json
    // [..]
    require: {
        // append this line to your requirements
        "koine/db-test-case": "*"
    }
}
```

### Alternative install
- Learn [composer](https://getcomposer.org). You should not be looking for an alternative install. It is worth the time. Trust me ;-)
- Follow [this set of instructions](#installing-via-composer)

### Issues/Features proposals

[Here](https://github.com/koinephp/DbTestCase/issues) is the issue tracker.

## Contributing

Please refer to the [contribuiting guide](https://github.com/koinephp/DbTestCase/blob/master/CONTRIBUTING.md).

### Lincense
[MIT](MIT-LICENSE)

### Authors

- [Marcelo Jacobus](https://github.com/mjacobus)

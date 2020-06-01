# `ghost-api-php`

Easy pulling of content from a Ghost API.

## Installation
```bash
composer require guym4c/ghost-api-php
```

## Usage
Get an instance of the API client:
```php
$ghost = new Ghost(
    $yourGhostApiBaseUrl,
    $yourGhostApiKey,
);
```
You can also pass in a Doctrine Cache provider here (and configure its lifetime), and the client will cache all requests that give a single-resource answer.

Call static methods on the resource classes to get your data.

```php
use Guym4c\GhostApiPhp\Model as Cms;

Cms\Post::bySlug($ghost, 'my-post-slug');
Cms\Tag::byId($ghost, 'my-tag-id');
Cms\Page::get()
```

If you don't know the slug or ID of the resource you're looking for, you can also run queries. (You'll probably want to be reading Ghost's [API docs](https://ghost.org/docs/api/v3/content/) in parallel if you use this method, so that you know what the client is doing.)
```php
Cms\Page::get(
    /* limit: */ 5,
    /* simple sorting: */ new Sort('some_value', SortOrder::DESC),
);
```

`get()` returns a `CollectionRequest` that exposes methods for managing pagination - you can access the returned records using `getResources()`.

### Filtering
The third parameter to the `::get` method above is a Ghost NQL filter, assembled programmatically using the client. It provides fluent methods that allow you to construct complex filter expressions.

```php
(new Filter())
    ->by('some-val', '=', 'true', true)
    ->and('another-val', '-', 'not-this')
    ->else((new Filter())
        ->by(
            'this-filter-will-be-given-brackets',
            '=',
            'which-overrides-operator-precedence',
        )
    );
```

`with()` and `else()` are bracket-ised versions of `and()` and `or()`. You must start with `by()`.

We've passed an additional `true` at the start here to denote that the value, `'true'`, is a literal and should be interpreted by Ghost as a boolean, not a string.

`[]` in-group syntax is not supported by the client.

Refer to the Ghost docs for more information on NQL filtering.
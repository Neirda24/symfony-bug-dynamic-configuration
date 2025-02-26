# Reproduce

1. clone this repository
2. `symfony composer install`
3. `rm -rf ./var/cache/*; symfony console config:dump fake`

You should see an error like this :

```shell
$ rm -rf ./var/cache/*; symfony console config:dump fake
# Default configuration for extension with alias: "fake"
[critical] Error thrown while running command "'config:dump' fake". Message: "Warning: Undefined property: Closure::$allowedTypes"

In NodeDefinition.php line 113:
                                                       
  Warning: Undefined property: Closure::$allowedTypes  
                                                       

config:dump-reference [--format FORMAT] [--] [<name> [<path>]]
```

In [NodeDefinition#L113](https://github.com/symfony/symfony/blob/7.3/src/Symfony/Component/Config/Definition/Builder/NodeDefinition.php#L112-L114) there is :

```php
foreach ($this->normalization->before as $expr) {
    $allowedTypes[] = $expr->allowedTypes;
}
```

I think it should be updated like so :

```diff+php
foreach ($this->normalization->before as $expr) {
+    if (!$expr instance of ExprBuilder) {
+        continue;
+    }
    $allowedTypes[] = $expr->allowedTypes;
}
```

because of [NormalizationBuilder](https://github.com/symfony/symfony/blob/7.3/src/Symfony/Component/Config/Definition/Builder/NormalizationBuilder.php#L50) we see that `before` can either be a `Closure` or an instance of `ExprBuilder`.

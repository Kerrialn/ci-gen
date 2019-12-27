# CI Config Generator

[![Build Status](https://img.shields.io/travis/kerrialn/ci-config-generator/master.svg?style=flat-square)](https://travis-ci.org/kerrialn/ci-config-generator)
[![Downloads](https://img.shields.io/packagist/dt/kerrialn/ci-config-generator.svg?style=flat-square)](https://packagist.org/packages/kerrialn/ci-config-generator)

#### Blurb 
Auto generate the configuration yaml file for continuous integration and deployment services. Never write a CI yaml file manually again!

#### Run process
1. Run command `bin/ci gen`
2. Select CI service you want to use (options based on you git config file)
3. Currently, generates a yaml file based on your composer.json and PHPUnit tests. Example below:

```yaml
name: 'Travis CI'
matrix:
    include:
        -
            php: 7.2
        -
            env: 'COMPOSER_FLAGS="--prefer-lowest"'
        -
            php: 7.3
        -
            php: 7.4
install:
    - 'composer update --prefer-source $COMPOSER_FLAGS'
script:
    - 'vendor/bin/phpunit --testsuite main'
notifications:
    email: false
```

#### CI Services Compatibility
- Travis CI
- Github Actions
- Circle CI
- Gitlab CI
- Bitbucket CI

# CI Gen

[![Build Status](https://img.shields.io/travis/kerrialn/ci-gen/master.svg?style=flat-square)](https://travis-ci.org/kerrialn/ci-gen)
[![Downloads](https://img.shields.io/packagist/dt/kerrialn/ci-gen.svg?style=flat-square)](https://packagist.org/packages/kerrialn/ci-gen)

#### Project status
Please note: This project is in the prototyping phase and may not output as expected.

#### Blurb 
Automatically generate the configuration yaml file for continuous integration (CI) services. Never write a CI yaml file manually again!

#### Use cases
- Setting up a project and want  to use a CI service
- Switching CI services and don't want the hassle of rewriting your yaml file to be compatible with the new service (not yet implemented) 

#### Run process
1. Install: `composer require kerrialn/ci-gen`
1. Run: `bin/ci gen`
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

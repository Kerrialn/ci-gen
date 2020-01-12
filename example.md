**Github Actions** 

name: 

on:
    pull_request: null
    push:
        branches:
            - master

jobs:
    tests:
        runs-on: ubuntu-latest
        strategy:
            matrix:
                php: ['7.2', '7.3', '7.4']

        name: PHP tests
        steps:
          

**Circle CI**

version: 2
jobs:
  build:
    docker:
    -
    steps:
      - checkout
      - run: 
  test:
    docker:
      - image:
    steps:
      - checkout
      - run: 
workflows:
  version: 2
  build_and_test:
    jobs:
      - build
      - test


**Travis CI** 



**Gitlab CI**



Common  


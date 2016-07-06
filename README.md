# SocialHub
Amulen Plugin for Social Networks


## Installation

Require with composer:

```
  composer require flowcode/socialhub
```

Run Amulen install command:
```
  php app/console amulen:plugin:register "Flowcode\SocialHubBundle\FlowcodeSocialHubBundle"
```

Update data base schema:
```
  php app/console doctrine:schema:update --force
```


## Set up

Set your hosts in the parameters.yml.

[Setup](src/Flowcode/SocialHubBundle/Resources/doc/setup.md)



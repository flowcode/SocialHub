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
This is used for redirects.
```
    host.scheme: "http"
    host.host: "localhost:8000"
    host.base: ""
    socialhub_host_url: "%host.scheme%://%host.host%"
```

Set the firewall to be used:
```
    socialhub_firewall: "main"
```



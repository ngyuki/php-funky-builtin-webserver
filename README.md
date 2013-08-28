## Install

```console
$ git clone https://github.com/ngyuki/php-funky-builtin-webserver.git
$ cd php-funky-builtin-webserver
$ curl -sS https://getcomposer.org/installer | php
$ php composer.phar install
$ mkdir -p $HOME/bin
$ ln -sf ${PWD}/bin/phpserver $HOME/bin/
```

## Usage

Type `phpserver` Command.

```console
$ phpserver
PHP 5.4.19 Development Server started at Wed Aug 28 22:03:51 2013
Listening on http://0.0.0.0:3000
Document root is /tmp
Press Ctrl-C to quit.
```

Open [http://localhost:3000/](http://localhost:3000/) in WebBrowser.

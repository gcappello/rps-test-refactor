# RPS Game Service

Simple Rock-Paper-Scissors game service definition written in PHP.

## Server

Server architecture allows for custom server implementations (a.k.a. `Adapter`). Included along with
this project you'll find a simple TCP server implementation, which uses TCP sockets and process
forking in order to allow simultaneous connections; which may be considered "multiplayer" in the sense
it allows for concurrent users playing at the same time against the server.

### Starting TCP Server

As mention before, this project includes a simple TCP server implementation. Please, before
starting this server make sure to install all dependencies required using
[composer](https://getcomposer.org/download/) as follow:

```bash
~$ composer install
```

Then, run the `server.php` script provided as follow in order to start a new TCP server instance:

```bash
~$ php server.php
```

This will start a new server instance at `0.0.0.0:6000` ready to handle new TCP connections. This
server can handle multiple game session at the same time, it also uses a simple PHP-array storage
system to keep track of registered users.

## Client

Provided TCP server requires no specific client application, as game state are handled
by the server itself. However, future server implementations ("adapters") may require specific
clients in order to communicate.

Connecting to a running TCP server instance is just as simple as **using any TCP client**; for
instance, using Unix's ``netcat``:

```bash
~$ nc 127.0.0.1 6000
```

Once connection is made with the sever, it'll start asking for actions in real time. Example output:

```
chris@chris-book:~$ nc 127.0.0.1 6000

Want to login? [yes/NO]
yes
What is your username?
Chris
What is your password?
123456

################################################################
## Hello Chris, welcome back! (last seen 2019-02-09 15:30:31) ##
################################################################

Starting a new game!
####################

Please indicate the number of rounds to play [1-10]: [default: 3]
2
-----------------------------------
- Round 1/2, enter your shape: (rock,paper,scissors,lizard,spock) [default: scissors]
lizard
(you chosen `lizard`, server chose `rock`)
-----------------------------------
- Round 2/2, enter your shape: (rock,paper,scissors,lizard,spock) [default: scissors]
spock
(you chosen `spock`, server chose `scissors`)
--------------------------
# Game results
- Round 1: lizard vs rock (lose)
- Round 2: spock vs scissors (win)

## Overall winner
tie
--------------------------

Play again? [YES/no]
no
Bye!
```

## Game Modes

This server architecture provides interchangeable game modes, a.k.a. `Shape Handlers`.
The built-in TCP server implementation offers two games mode:
the classic [Rock-Paper-Scissor](https://en.wikipedia.org/wiki/Rock%E2%80%93paper%E2%80%93scissors) game, and    
popular variation [Rock-Paper-Scissors-Lizard-Spock](http://www.samkass.com/theories/RPSSL.html).

To start a TCP server for playing `Rock-Paper-Scissors-Lizard-Spock` simply start the server as follow:

```bash
~$ php server.php rpsls
```

## Creating Servers

Implementation of new servers requires you to implement the interfaces provided, use the provided
`Socket` adapter as reference.

## TODO

- Allow "human vs human" matches:
  - Represent server AI as a regular player (`PlayerInterface`)
  - Define "game session" server, for handling specific game sessions.
  - Define "main" server (match-maker), for locating available game session/rooms or spawning new ones.
- TCP server, use `SQLite` for users registration.

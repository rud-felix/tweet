# Vagrant skeleton

#### Packages Included

LEMP stack:

- Ubuntu 14.04.3 LTS
- Nginx 1.4.6
- PHP 5.5
- MySQL 5.5
- MongoDB
- Git 1.9

LAMP stack:

- *soon*

#### Requirements

- Operating System: Windows, Linux, or OSX.
- Virtualbox >= 4.3.10
- Vagrant >= 1.4.1

#### Configuring

1. Copy your `id_rsa` and `id_rsa.pub` to `./vagrant.d/ssh`
2. Create file `known_hosts` in `./vagrant.d/ssh`and copy in it result of this command `ssh-keyscan -H github.com`

#### Usage

Execute `vagrant up` for run vagrant machine

#### Vagrant Credentials

These are credentials setup by default:

- **Host Address**: 192.168.33.10
- **SSH**: vagrant / vagrant
- **MySQL**: root / root

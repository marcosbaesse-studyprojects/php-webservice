## Instalação

$ composer selfupdate
$ composer install

## Configuração de vhost apache - Debian/Ubuntu
### Criar um vhost dev.lumen

$ sudo vim /etc/apache2/sites-available/dev.lumen.conf

<VirtualHost *:80>

        ServerAdmin webmaster@localhost
        ServerName dev.lumen
        DocumentRoot "/home/marcos/Dropbox/lumen/public"

        <Directory "/home/marcos/Dropbox/lumen/public">
                Require all granted
                AllowOverride all
                DirectoryIndex index.php
        </Directory>

        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>

### Habilitar o vhost
$ sudo a2ensite dev.lumen.conf

### Adicionar entrada nos hosts
$ sudo vim /etc/hosts

127.0.0.1   dev.lumen

### Reiniciar o apache2
$ sudo service apache2 restart

### Permissões mínimas para as pastas e arquivos
* Pastas:
    * find /Caminho/Do/Meu/App/public -type d -exec chmod 0755 {} \;
* Arquivos:
    * find /Caminho/Do/Mey/App/public -type f -exec chmod 0644 {} \;

* Storage:
    * $ sudo chown -R www-data storage
    * $ sudo chmod -R u+w storage

* Se usar sqlite, fazer:
    * $ sudo chgrp www-data database
    * $ sudo chgrp www-data database/database.sqlite
    * $ sudo chmod g+w database
    * $ sudo chmod g+w database/database.sqlite

## Configuração da Aplicação
Copie o arquivo .env.example, para .env

Configure um APP_KEY, pode ser qualquer valor, desde que tenha 32 caracteres.

Configure o banco de dados

## Migração do Banco de Dados e Iniciação do banco de dados
$ php artisan migrate

$ php artisan db:seed

## Para se utilizar o SOAP, é necessário instalar o PHP_SOAP

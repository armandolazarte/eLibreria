eLibreria
=========

1.- Instalar Symfony 2.3

2.- Crear carpeta src/RGM

3.- Clonar repositorio en carpeta RGM quedando: src/RGM/eLibreria/...

4.- Añadir composer.json e instalador.php a Symfony/.

5.- Lanzar php instalador.php

6.- Lanzar php composer.phar update

7.- Añadir ficheros routing.yml y security.yml

8.- Configurar parameters.yml

9.- En config.yml:
    a. Descomentar:
        framework:
            esi:             ~
            translator:      { fallback: %locale% }
        
    b. Modificar:
        assetic:
            bundles:        [ RGMELibreriaIndexBundle ]
        
10.- Lanzar php app/console doctrine:database:create

11.- Lanzar php app/console doctrine:schema:create

12.- Lanzar php app/console assets:install --symlink

13.- Lanzar php app/console cache:clear

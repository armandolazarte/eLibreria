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
            
10.- Añadir a app/AppKernel.php:

```php
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new APY\DataGridBundle\APYDataGridBundle(),
            new RGM\eLibreria\IndexBundle\RGMELibreriaIndexBundle(),
            new RGM\eLibreria\UsuarioBundle\RGMELibreriaUsuarioBundle(),
            new RGM\eLibreria\EmpresaBundle\RGMELibreriaEmpresaBundle(),
            new RGM\eLibreria\LibroBundle\RGMELibreriaLibroBundle(),
            new RGM\eLibreria\FinanciacionBundle\RGMELibreriaFinanciacionBundle(),
            new RGM\eLibreria\SuministroBundle\RGMELibreriaSuministroBundle(),
            new RGM\eLibreria\VentasBundle\RGMELibreriaVentasBundle(),
```
        
11.- Lanzar php app/console doctrine:database:create

12.- Lanzar php app/console doctrine:schema:create

13.- Lanzar php app/console doctrine:fixtures:load

14.- Lanzar php app/console assets:install --symlink

15.-
     a)sudo setfacl -R -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs
     b)sudo setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs

16.- Lanzar php app/console cache:clear

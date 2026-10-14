# Procedimiento de publicación y revisión en GitHub

## Organización del trabajo

El desarrollo se organiza mediante un repositorio con una rama principal y ramas de trabajo. Las responsabilidades corresponden a desarrollo, calidad e integración. La guía del laboratorio solicita de dos a tres actualizaciones por desarrollador y el registro de las revisiones en GitHub.

La publicación se realiza con los siguientes pasos:

1. Registrar los cambios del proyecto en la rama de trabajo, con mensajes que describan su contenido.
2. Enviar la rama al repositorio del grupo.
3. Abrir un Pull Request indicando los módulos incorporados y las verificaciones realizadas.
4. Revisar la rama y comprobar el mantenimiento de productos y las modalidades de venta.
5. Registrar observaciones o aceptar el cambio en GitHub.
6. Integrar en la rama principal los cambios aceptados.

## Configuración local

Se agregó `.gitignore` para excluir `app/config/config.php`, que contiene la conexión local a MySQL, y `public/diagnostico.php`. Se incluyó `app/config/config.example.php` como referencia para configurar otras instalaciones.

En una nueva clonación se copia `config.example.php` con el nombre `config.php` y se completan los datos de conexión y la dirección local del proyecto. La configuración real permanece en la computadora y no se publica en el repositorio.

## Registro inicial

Para una carpeta que todavía no tiene un repositorio Git, los comandos iniciales se ejecutan desde PowerShell dentro del proyecto:

```powershell
git init
git switch -c main
git add .
git status
git commit -m "Agregar laboratorio PHP MVC Northwind"
```

Antes de confirmar el commit, `git status` permite revisar los archivos incluidos. La conexión con el repositorio remoto se configura con su dirección real. Estos comandos documentan el procedimiento; no representan un historial de commits ni de revisiones realizadas.

## Referencia

Ruiz, J. (2026). *Guía de actividad práctica y/o laboratorio: Laboratorio #01* [Material de curso]. Universidad Técnica Nacional, Sede del Pacífico.

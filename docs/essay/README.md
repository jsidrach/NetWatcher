Código fuente de la memoria en *LaTeX* para el **Trabajo de Fin de Grado** (EPS-UAM).

Instalación completa (dependencias)
----
### GNU/Linux
##### Ubuntu/Debian based
Instalar con *aptitude*
```sh
$ sudo apt-get install texlive-full python-pygments
```
##### Fedora
Instalar con *yum*
```sh
$ sudo yum install texlive-scheme-full python-pygments
```
##### Arch Linux/Manjaro
Instalar con *pacman*
```sh
$ sudo pacman -S texlive-most python-pygments
```

### Windows
[Instalar proTeXt](https://tug.org/protext/)

[Instalar Pygments](ttp://pygments.org/download/)

### Mac/OS X
[Instalar MacTeX](https://tug.org/mactex/)

[Instalar Pygments](ttp://pygments.org/download/)

Comandos
----
Si prefieres no utilizar un editor gráfico de *LaTeX* ([Texmaker](http://www.xm1math.net/texmaker/), [TeXworks](https://www.tug.org/texworks/)), puedes usar los siguientes comandos (*GNU/Linux*, *Mac*):
#### Borrar archivos auxiliares
```sh
$ ./limpiar.sh
```

#### Generar el documento PDF a partir del código fuente
```sh
$ ./compilar.sh
```
# Presentación del Trabajo de Fin de Grado

## Basada en el tema de beamer *mtheme*

Se utiliza *[mtheme](https://github.com/matze/mtheme)*, un tema de Beamer con con un diseño visual minimalista.

## Instalación

Dependencias:

* *XeLaTeX*,
* *[Fira Sans](https://github.com/mozilla/Fira)*
* *TikZ*

Dependiendo de la distribución de *GNU/Linux*, el paquete con la fuente *Fira Sans* puede ser `Fira Sans OT` en vez de `Fira Sans`. Es ese caso, necesitarás editar el archivo `beamerfontthememetropolis.sty`. Si necesitas instalar *Fira Sans*, puedes ejecutar `getFiraFont.sh`.

### Uso

Para generar el .pdf, ejecuta `make` en el directorio de la presentación.

### Configuración

#### Opciones de *mtheme*

La opción `usetitleprogressbar` añade una fina barra de progreso similar a la de entre secciones debajo del título de cada diapositiva.

Para poder usar `\cite`, `\ref` y comandos similares en el título de una diapositiva se debe proteger el título. Esto se puede realizar automáticamente con la opción `protectframetitle`.

La opción `blockbg` define colores extra utilizados al definir bloques. Los bloques entonces tendrán un color de fondo gris similar a otros temas de beamer.

Por defecto, este tema añade `\vspace{2em}` tras el título para hacer que el contenido esté más centrado en la diapositiva. Si se utiliza más contenido por diapositiva se puede deshabilitar este espacio extra de forma global utilizando la opción `nooffset`.

Con la opción `nosectionslide`, no se añade una diapositiva específica cuando se empieza una nueva sección. Por defecto, cuando se utiliza el comando `\section`, una nueva diapositiva es creada con únicamente el título de la sección en ella.

La opción `nosmallcapitals` elimina el uso de mayúsculas pequeñas en el título de la página y de las diapositivas, y de las diapositivas específicas de nueva sección.

La opción `usetotalslideindicator` añade el número de cada diapositiva en la esquina inferior derecha con el formato #actual/#total. Por defecto, sólo se muestra el número de página actual.

#### Comandos

El comando `\plain{title=[]}{body}` establece una diapositiva en colores oscuros planos.

#### Estilos de *pgfplot*

El tema *mtheme* contiene también estilos predefinidos de *pgfplot*. Usa las clave `mlineplot` para dibujar gráficos de líneas y `mbarplot` o `horizontal mbarplot` para dibujar gráficos de barras.

### Licencia

El tema base utiliza la licencia [Creative Commons Attribution-ShareAlike 4.0 International License](http://creativecommons.org/licenses/by-sa/4.0/). La presentación en sí utiliza la misma licencia que el resto de documentación de este proyecto ([Creative Commons Attribution 4.0 International License](http://creativecommons.org/licenses/by/4.0/)).

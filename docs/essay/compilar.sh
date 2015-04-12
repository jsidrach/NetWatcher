#!/bin/bash

# Limpiar archivos auxiliares
./limpiar.sh

# Compilar
pdflatex -shell-escape main.tex
bibtex main
makeglossaries main
pdflatex -shell-escape main.tex
pdflatex -shell-escape main.tex
mv main.pdf "Trabajo de Fin de Grado.pdf"

# Limpiar archivos auxiliares
./limpiar.sh
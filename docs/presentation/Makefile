SRC = presentation.tex
PDF = presentation.pdf
AUX = presentation.aux
TEXC := xelatex
TEXC_OPTS += -shell-escape

.PHONY: clean install

all: $(PDF)

$(AUX):
	$(TEXC) $(TEXC_OPTS) $(SRC)

$(PDF): beamerthemem.sty $(AUX) $(SRC)
	$(TEXC) $(TEXC_OPTS) $(SRC)
	rm -f *.aux *.log *.nav *.out *.snm *.toc *.vrb latexmkrc
	rm -f src/*.aux src/*.log src/*.nav src/*.out src/*.snm src/*.toc src/*.vrb src/latexmkrc
	mv presentation.pdf "Presentación TFG.pdf"

clean:
	@rm -f $(PDF)
	rm -f *.aux *.log *.nav *.out *.snm *.toc *.vrb latexmkrc
	rm -f src/*.aux src/*.log src/*.nav src/*.out src/*.snm src/*.toc src/*.vrb src/latexmkrc

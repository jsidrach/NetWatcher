echo "setMode -bscan" > impact.bat
echo "setCable -p auto" >> impact.bat
echo "identify" >> impact.bat
echo "assignfile -p 1 -file $1" >> impact.bat
echo "program -p 1 -prog" >> impact.bat
echo "quit" >> impact.bat

impact -batch impact.bat

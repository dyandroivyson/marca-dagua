# Marca D'água
Script PHP para anexar uma marca d'água PNG em imagens PNG. A marca d'água é inserida no centro das imagens alvo e redimencionada proporcionamente em relação a altura caso a imagem alvo seja menor que a marca d'água.

## Configuração
Para executar o script é necessário habilitar a extensão phpgd no php.ini.

## Como Executar
Inclua no diretório "transparencia" a imagem que servirá de marca d'água.
Inclua no diretório "imagens_originais" todas as imagens no formato PNG que receberam a marca d'água.
Execute o script "index.php" no terminal ou diretamente no navegador. Todas as imagens dentro do diretório "imagens_originais" serão movidas já com a marca d'água para dentro do diretório "imagens_manipuladas".

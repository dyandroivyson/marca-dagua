<?php
    // Verifica se o script está sendo executado via browser ou terminal
    $quebra_linha = "\n\n";
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $quebra_linha = '<br><br>';
    }

    echo 'Iniciando processamento!' . $quebra_linha;

    // Caminho fixo da marca d'água
    $marca_dagua = imagecreatefrompng('transparencia/marca_dagua.png');

    // Dimensões da marca d'água
    $width_marca_dagua = imagesx($marca_dagua);
    $height_marca_dagua = imagesy($marca_dagua);
    
    // Diretório de busca de imagens
    $dir_imagens_originais = __DIR__ . DIRECTORY_SEPARATOR 
        . 'imagens_originais';

    // Diretório de saída de imagens
    $dir_imagens_manipuladas = __DIR__ . DIRECTORY_SEPARATOR 
        . 'imagens_manipuladas';

    // Lista todos os arquivos do diretório
    $itens = new DirectoryIterator($dir_imagens_originais);
    foreach ($itens as $item) {
        // Ler arquivos no formato png
        if ($item->isFile() && strtolower($item->getExtension()) == 'png') {
            echo 'Adicionando marca d\'água na imagem ' . $item->getFilename() 
                . $quebra_linha;

            // Caminho completo da imagem
            $path = $dir_imagens_originais . DIRECTORY_SEPARATOR 
                . $item->getFilename();

            // Transformando caminho na imagem a ser trabalhada
            $imagem = imagecreatefrompng($path);

            // Dimensões da imagem
            $width_imagem = imagesx($imagem);
            $height_imagem = imagesy($imagem);

            // Adicionando marca d'água na imagem original
            imagecopy(
                $imagem, 
                $marca_dagua, 
                (imagesx($imagem) - imagesx($marca_dagua)) / 2, 
                (imagesy($imagem) - imagesy($marca_dagua)) / 2, 
                0, 
                0, 
                $width_marca_dagua, 
                $height_marca_dagua
            );

            // Saída
            imagepng($imagem, $dir_imagens_manipuladas . DIRECTORY_SEPARATOR 
                . $item->getFilename());
            
            // Limpando buffer
            imagedestroy($imagem);

            // Removendo arquivo original
            unlink($path);
        }
    }

    echo 'Finalizando processamento!' . $quebra_linha;

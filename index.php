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

            if ($height_imagem <= $height_marca_dagua) {
                $path_transparencia = 'transparencia/marca_dagua_temp.png';
                // Calcula o ratio da altura
                $ratio = $height_imagem / $height_marca_dagua;

                // Calcula as novas dimensões
                $novo_width = $width_marca_dagua * $ratio;
                $novo_height = $height_marca_dagua * $ratio;

                // Cria uma nova transparência com as novas dimensões
                $nova_transparencia = imagecreatetruecolor(
                    $novo_width, 
                    $novo_height
                );
                imagesavealpha($nova_transparencia, true);
                $color = imagecolorallocatealpha(
                    $nova_transparencia, 
                    0, 
                    0, 
                    0, 
                    127
                );
                imagefill($nova_transparencia, 0, 0, $color);
                imagecopyresampled(
                    $nova_transparencia, 
                    $marca_dagua, 
                    0, 
                    0, 
                    0, 
                    0, 
                    $novo_width, 
                    $novo_height, 
                    $width_marca_dagua, 
                    $height_marca_dagua
                );
                imagepng($nova_transparencia, $path_transparencia);

                // Adicionando marca d'água na imagem original
                imagecopy(
                    $imagem, 
                    $nova_transparencia, 
                    (imagesx($imagem) - imagesx($nova_transparencia)) / 2, 
                    (imagesy($imagem) - imagesy($nova_transparencia)) / 2, 
                    0, 
                    0, 
                    $novo_width, 
                    $novo_height
                );

                // Saída
                imagepng($imagem, $dir_imagens_manipuladas . DIRECTORY_SEPARATOR 
                    . $item->getFilename());
                
                // Limpando buffer
                imagedestroy($imagem);
                imagedestroy($nova_transparencia);

                // Removendo arquivo original
                unlink($path);
                unlink($path_transparencia);
            } else {
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
    }

    echo 'Finalizando processamento!' . $quebra_linha;

<h1>Módulo de API JSON</h1>

**Compatível com a plataforma Magento CE versão 1.6 a 1.9**

Agora ficou muito mais rápido trocar informações com o Magento.

Adicionamos suporte a API JSON para deixar a sua integração muito mais leve.

E agora todos os retornos obtidos nas chamadas de API podem ser totalmente armazenados em cache.

<img src="https://dl.dropboxusercontent.com/s/2lgntr3opt28o46/gamuza-jsonapi-box.png" alt="" title="Gamuza JSON API - Magento - Box" />

<h2>Instalação</h2>

<img src="https://dl.dropboxusercontent.com/s/pqpp0x62kqov683/sempre-faca-backup.png" alt="" title="Atenção! Sempre faça um backup da sua loja antes de realizar qualquer modificação!" />

**Instalar usando o modgit:**

    $ cd /path/to/magento
    $ modgit init
    $ modgit add gamuza_jsonapi https://github.com/gamuzatech/gamuza_jsonapi-magento.git

**Instalação manual dos arquivos**

Baixe a ultima versão aqui do pacote Gamuza_JsonApi-xxx.tbz2 e descompacte o arquivo baixado para dentro do diretório principal do Magento

<img src="https://dl.dropboxusercontent.com/s/ir2vm6cyo3gl1v8/pos-instalacao.png" alt="Após a instalação, limpe os caches, rode a compilação, faça logout e login." title="Após a instalação, limpe os caches, rode a compilação, faça logout e login." />

<h2>Conhecendo o módulo</h2>

**1 - Configurando os parâmetros da API JSON no Painel Administrativo**

<img src="https://dl.dropboxusercontent.com/s/9tiiwdk4r3za9td/gamuza-jsonapi-config-admin.png" alt="" title="Gamuza JSON API - Magento - Configurando os parâmetros da API JSON no Painel Administrativo" />

<h2>Mapeamento das Rotinas da API</h2>

**http://magento/api/json/map**

<h2>Exemplo de Rotina da API</h2>

**Obtendo listagem de clientes**

    <?php
    
    function json_api ($post)
    {
        $url = 'http://magento/api/json/index';

        $curl = curl_init ();

        curl_setopt ($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt ($curl, CURLOPT_URL, $url);
        curl_setopt ($curl, CURLOPT_HTTPHEADER, array ('Content-Type: application/json'));
        curl_setopt ($curl, CURLOPT_POST, 1);
        curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($curl, CURLOPT_POSTFIELDS, json_encode ($post));
        // SSL off
        curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 0);

        $result = curl_exec ($curl);
        $info = curl_getinfo ($curl);
        $response = json_decode ($result, true);

        curl_close ($curl);

        return $response;
    }

    // Autorizando

    $post = array(
        'method' => 'login',
        'params' => array(
            'username' => 'usuario_api',
            'apiKey' => 'senha_api'
        )
    );

    $result = json_api ($post);
    
    var_dump ($result);

    // Listando clientes

    $sessionId = $result ['result'];

    $post = array(
        'method' => 'call',
        'params' => array(
            'sessionId' => $sessionId,
            'apiPath' => 'customer.list',
            'args' => array(),
        )
    );

    $result = json_api ($post);

    var_dump ($result);



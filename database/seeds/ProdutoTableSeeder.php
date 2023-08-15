<?php

use Illuminate\Database\Seeder;

class ProdutoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('tbl_produto')->insert([
            'nome' => 'PROCESSADOR AMD RYZEN 5 3600 3.6GHZ (4.2GHZ TURBO), 6-CORE 12-THREAD, COOLER WRAITH STEALTH, AM4, YD3600BBAFBOX',
            'descricao' => 'Velocidades mais elevadas, mais memória e largura de banda mais ampla do que as gerações anteriores. Processadores AMD Ryzen™ de 3ª geração com núcleo “Zen 2” de 7 nm² define o padrão para alto desempenho: tecnologia de fabricação exclusiva, histórico de produtividade no chip e desempenho global revolucionário para jogos.',
            'cod_barras' => '7891234560011',
            'valor' => 1099,
            'ativo' => true,
            'imagem' => 'public/produtos/produto_image_000001.jpg',
            'quantidade' => 10,
            'created_at' => 'now()',
        ]);

        DB::table('tbl_produto')->insert([
            'nome' => 'PLACA MÃE ASROCK X470 MASTER SLI/AC WIFI, CHIPSET X470, AMD AM4, ATX, DDR4',
            'descricao' => "Além da iluminação RGB integrada, ela também apresenta conectores RGB e um conector RGB endereçável que permite que a placa-mãe seja conectada a dispositivos de LED compatíveis como fitas, ventoinhas de CPU, coolers, gabinetes e assim por diante. Os usuários também podem sincronizar dispositivos LED RGB através de acessórios Polychrome RGB Sync-certified certificados para criar seus efeitos de iluminação únicos.\n\nUm enorme dissipador M.2 de cobertura total que suporta dispositivos M.2 até Tipo 22110, e é capaz de dissipar o calor de forma eficiente para assegurar que seu SSD M.2 de alta velocidade trabalhe sempre em seu melhor.\n\nEquipada com slot M.2 que suporta interface SATA3 6Gb/s e PCIe Gen3 x4 Ultra que eleva a velocidade de transferência de dados a até 32Gb/s, e é compatível com o Kit U.2 ASRock para instalar alguns dos SSDs U.2 PCIe Gen3 x4 mais rápidos do mundo.",
            'cod_barras' => '7891234560021',
            'valor' => 969,
            'ativo' => true,
            'imagem' => 'public/produtos/produto_image_000002.png',
            'quantidade' => 10,
            'created_at' => 'now()',
        ]);

        DB::table('tbl_produto')->insert([
            'nome' => 'MEMÓRIA DDR4 CRUCIAL BALLISTIX SPORT LT, 8GB 3000MHZ, RED, BLS8G4D30AESEK',
            'descricao' => 'Velocidade inicial de 2400 MT/s, Velocidade e responsividade maiores em comparação com a memória DDR4 padrão, Ideal para amantes de games e entusiastas de alto desempenho, Arquitetura com quatro canais de memória para maximizar as taxas de transferência de dados',
            'cod_barras' => '7891234560032',
            'valor' => 279,
            'ativo' => true,
            'imagem' => 'public/produtos/produto_image_000003.jpg',
            'quantidade' => 10,
            'created_at' => 'now()',
        ]);

        DB::table('tbl_produto')->insert([
            'nome' => 'SSD CRUCIAL MX500 250GB, M.2 2280, LEITURA 560MBS GRAVAÇÃO 510MBS, CT250MX500SSD4',
            'descricao' => 'Sempre que você ligar o computador, ele estará usando o disco de armazenamento. Ele armazena todos os seus arquivos mais importantes e carrega e salva tudo o que o sistema faz. Faça como as pessoas mais atualizadas: use um SSD para guardar vídeos da família, fotos de viagens, músicas e documentos importantes e aproveite o ganho quase instantâneo de desempenho e a confiabilidade duradoura próprios da unidade de estado sólido. Mude para o SSD Crucial® MX500, uma unidade pautada em qualidade, velocidade e segurança e sustentada por atendimento e suporte prestativos.',
            'cod_barras' => '7891234560042',
            'valor' => 329,
            'ativo' => true,
            'imagem' => 'public/produtos/produto_image_000004.jpg',
            'quantidade' => 10,
            'created_at' => 'now()',
        ]);

        DB::table('tbl_produto')->insert([
            'nome' => 'SSD KINGSTON A400 240GB, SATA III, LEITURA 500MBS GRAVAÇÃO 350MBS, SA400S37/240G',
            'descricao' => 'A unidade de estado sólido A400 da Kingston aumenta drasticamente a resposta do seu computador com tempos incríveis de inicialização, carregamento e transferência, comparados a discos rígidos mecânicos. Reforçado com uma controladora de última geração, este SSD é 10x mais rápido do que um disco rígido tradicional para melhor desempenho, resposta ultra-rápida em multitarefas e um computador mais rápido de modo geral. Também mais confiável e durável do que um disco rígido.',
            'cod_barras' => '7891234560053',
            'valor' => 179.9,
            'ativo' => true,
            'imagem' => 'public/produtos/produto_image_000005.jpg',
            'quantidade' => 10,
            'created_at' => 'now()',
        ]);

        DB::table('tbl_produto')->insert([
            'nome' => 'HD SEAGATE BARRACUDA 1TB, SATA III, 7200RPM, 64MB, ST1000DM010',
            'descricao' => 'A velocidade de um disco é a principal responsável por sua agilidade na leitura e cópia de informações. Por isso, contar com valores de grande porte é fundamental para os usuários que querem aumentar sua produtividade e não gostam de ter seu tempo jogado fora. Ao lado do HD Seagate BarraCuda 1TB você tem 7.200 RPM, capaz este de efetuar cópias e leitura de dados com extrema agilidade, disponibilizando seus conteúdos com performance sem precedentes.',
            'cod_barras' => '7891234560063',
            'valor' => 239,
            'ativo' => true,
            'imagem' => 'public/produtos/produto_image_000006.jpg',
            'quantidade' => 10,
            'created_at' => 'now()',
        ]);

        DB::table('tbl_produto')->insert([
            'nome' => 'HD WESTERN DIGITAL CAVIAR BLUE 2TB, SATA III, 5400RPM, 64MB, WD20EZRZ',
            'descricao' => 'Caviar Blue são projetadas para oferecer desempenho ideal sólida para computação de sua família e negócios. A WD continua a desenvolver maneiras novas e inovadoras de manter as unidades refrigeradas durante sua operação.',
            'cod_barras' => '7891234560074',
            'valor' => 339.9,
            'ativo' => true,
            'imagem' => 'public/produtos/produto_image_000007.jpg',
            'quantidade' => 10,
            'created_at' => 'now()',
        ]);

        DB::table('tbl_produto')->insert([
            'nome' => 'FONTE CORSAIR VS650 650W, 80 PLUS, PFC ATIVO CP-9020172-WW',
            'descricao' => 'Os modelos da VS Series da CORSAIR combinam a eficiência de alimentação 80 PLUS e os preços acessíveis do fabricante de fontes de alimentação para PCs mais confiável do mundo.',
            'cod_barras' => '7891234560084',
            'valor' => 299,
            'ativo' => true,
            'imagem' => 'public/produtos/produto_image_000008.jpg',
            'quantidade' => 10,
            'created_at' => 'now()',
        ]);

        DB::table('tbl_produto')->insert([
            'nome' => 'PLACA DE VÍDEO ASUS RADEON RX 580 DUAL OC, 8GB GDDR5, 256BIT, DUAL-RX580-O8G',
            'descricao' => 'A série Asus Dual RX 580 oferece a combinação perfeita de desempenho e design gráfico ideal para jogos VR e esports. Essas novas placas gráficas são capazes de oferecer jogos de resolução HD com ultra-configurações, trazendo novos níveis de desempenho para o mercado a um custo acessível.',
            'cod_barras' => '7891234560095',
            'valor' => 999,
            'ativo' => true,
            'imagem' => 'public/produtos/produto_image_000009.png',
            'quantidade' => 10,
            'created_at' => 'now()',
        ]);

        DB::table('tbl_produto')->insert([
            'nome' => 'PLACA DE VÍDEO GALAX GEFORCE GTX 1660 TI OC (1-CLICK OC) DUAL, 6GB GDDR6, 192BIT, 60IRL7DSY91C',
            'descricao' => 'Até 3x mais rápida que a geração passada de placas de vídeo nos games e aplicações VR. Até 3x com mais performance e economia de energia em relação à geração passada. Meticulosamente trabalhada para oferecer dissipação de calor superior. 80mm Dual fan: Aumenta a estabilidade e desempenho da refrigeração. Projeto de resfriamento eficaz: 6mm×2 heatpipes de cobre, Cooler direto na GPU, Dissipação de calor superior',
            'cod_barras' => '7891234560100',
            'valor' => 1329,
            'ativo' => true,
            'imagem' => 'public/produtos/produto_image_000010.png',
            'quantidade' => 10,
            'created_at' => 'now()',
        ]);

        DB::table('tbl_produto')->insert([
            'nome' => 'GABINETE GAMER AEROCOOL RIFT RGB, MID TOWER, COM 1 FAN, LATERAL EM ACRÍLICO, BLACK, S-FONTE',
            'descricao' => 'O RIFT é um gabinete Mid Tower de alto desempenho, com um design em RGB que exibe 13 modos de iluminação exclusivos, 6 fluxos RGB e 7 modos de cores estáticas.',
            'cod_barras' => '7891234560111',
            'valor' => 195,
            'ativo' => true,
            'imagem' => 'public/produtos/produto_image_000011.jpg',
            'quantidade' => 10,
            'created_at' => 'now()',
        ]);
    }
}

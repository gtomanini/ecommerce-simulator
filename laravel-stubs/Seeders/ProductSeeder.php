<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariation;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // ROUPAS
        $clothing = Category::where('slug', 'roupas')->first();
        $this->createClothingProducts($clothing);

        // ELETRÔNICOS
        $electronics = Category::where('slug', 'eletronicos')->first();
        $this->createElectronicsProducts($electronics);

        // DECORAÇÃO
        $decoration = Category::where('slug', 'decoracao')->first();
        $this->createDecorationProducts($decoration);

        // SAPATOS
        $shoes = Category::where('slug', 'sapatos')->first();
        $this->createShoesProducts($shoes);

        // LIVROS
        $books = Category::where('slug', 'livros')->first();
        $this->createBooksProducts($books);

        // ESPORTES
        $sports = Category::where('slug', 'esportes')->first();
        $this->createSportsProducts($sports);

        // BELEZA
        $beauty = Category::where('slug', 'beleza-cuidados')->first();
        $this->createBeautyProducts($beauty);

        // COZINHA
        $kitchen = Category::where('slug', 'cozinha')->first();
        $this->createKitchenProducts($kitchen);

        // ACESSÓRIOS
        $accessories = Category::where('slug', 'acessorios')->first();
        $this->createAccessoriesProducts($accessories);
    }

    private function createClothingProducts($category): void
    {
        $products = [
            ['name' => 'Camiseta Básica Branca', 'slug' => 'camiseta-basica-branca', 'description' => 'Camiseta 100% algodão, confortável para o dia a dia', 'price' => 49.90, 'stock' => 100],
            ['name' => 'Camiseta Gráfica Rock', 'slug' => 'camiseta-grafica-rock', 'description' => 'Camiseta com estampa de banda de rock clássica', 'price' => 69.90, 'stock' => 80],
            ['name' => 'Calça Jeans Skinny', 'slug' => 'calca-jeans-skinny', 'description' => 'Calça jeans ajustada, modelo skinny muito versátil', 'price' => 129.90, 'stock' => 60],
            ['name' => 'Calça Jeans Reta', 'slug' => 'calca-jeans-reta', 'description' => 'Calça jeans clássica modelo reta', 'price' => 119.90, 'stock' => 75],
            ['name' => 'Jaqueta de Couro', 'slug' => 'jaqueta-couro', 'description' => 'Jaqueta de couro genuíno, ideal para looks modernos', 'price' => 299.90, 'stock' => 30],
            ['name' => 'Vestido Floral', 'slug' => 'vestido-floral', 'description' => 'Vestido com estampa floral, perfeito para primavera', 'price' => 159.90, 'stock' => 50],
            ['name' => 'Blusa de Seda', 'slug' => 'blusa-seda', 'description' => 'Blusa de seda fina, elegante e sofisticada', 'price' => 189.90, 'stock' => 40],
            ['name' => 'Shorts Jeans', 'slug' => 'shorts-jeans', 'description' => 'Shorts de jeans desbotado, perfeito para verão', 'price' => 89.90, 'stock' => 70],
            ['name' => 'Moletom Cinza', 'slug' => 'moletom-cinza', 'description' => 'Moletom confortável com capuz, ideal para dias frios', 'price' => 129.90, 'stock' => 90],
            ['name' => 'Calça Legging', 'slug' => 'calca-legging', 'description' => 'Legging confortável, perfeita para academia ou casual', 'price' => 79.90, 'stock' => 110],
            ['name' => 'Blazer Marrom', 'slug' => 'blazer-marrom', 'description' => 'Blazer social em tom marrom, elegante e versátil', 'price' => 249.90, 'stock' => 25],
        ];

        foreach ($products as $product) {
            $p = Product::create(array_merge(['category_id' => $category->id, 'sku' => uniqid()], $product));

            // Add images
            ProductImage::create([
                'product_id' => $p->id,
                'image_url' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400&h=300&fit=crop',
                'is_primary' => true
            ]);

            // Add variations (tamanho, cor)
            $sizes = ['P', 'M', 'G', 'GG'];
            foreach ($sizes as $size) {
                ProductVariation::create([
                    'product_id' => $p->id,
                    'type' => 'tamanho',
                    'value' => $size,
                    'price_modifier' => 0
                ]);
            }

            $colors = ['Preto', 'Branco', 'Vermelho', 'Azul'];
            foreach ($colors as $color) {
                ProductVariation::create([
                    'product_id' => $p->id,
                    'type' => 'cor',
                    'value' => $color,
                    'price_modifier' => 0
                ]);
            }
        }
    }

    private function createElectronicsProducts($category): void
    {
        $products = [
            ['name' => 'Fone de Ouvido Bluetooth', 'slug' => 'fone-bluetooth', 'description' => 'Fone sem fio com tecnologia noise cancelling', 'price' => 299.90, 'stock' => 50],
            ['name' => 'Carregador Rápido USB-C', 'slug' => 'carregador-usb-c', 'description' => 'Carregador de 65W, compatível com múltiplos dispositivos', 'price' => 149.90, 'stock' => 200],
            ['name' => 'Powerbank 20000mAh', 'slug' => 'powerbank-20000', 'description' => 'Bateria portátil com grande capacidade', 'price' => 129.90, 'stock' => 120],
            ['name' => 'Cabo HDMI 2.1', 'slug' => 'cabo-hdmi', 'description' => 'Cabo HDMI de alta velocidade para 4K', 'price' => 59.90, 'stock' => 150],
            ['name' => 'Hub USB Multifuncional', 'slug' => 'hub-usb', 'description' => 'Hub com 7 portas USB, ótimo para expansão', 'price' => 179.90, 'stock' => 80],
            ['name' => 'Webcam 1080p', 'slug' => 'webcam-1080p', 'description' => 'Câmera web Full HD com microfone integrado', 'price' => 249.90, 'stock' => 60],
            ['name' => 'Mouse Wireless', 'slug' => 'mouse-wireless', 'description' => 'Mouse sem fio com grande autonomia de bateria', 'price' => 89.90, 'stock' => 100],
            ['name' => 'Teclado Mecânico RGB', 'slug' => 'teclado-rgb', 'description' => 'Teclado mecânico com iluminação RGB personalizável', 'price' => 399.90, 'stock' => 40],
            ['name' => 'Monitor 24" FHD', 'slug' => 'monitor-24', 'description' => 'Monitor Full HD com painel IPS de qualidade', 'price' => 699.90, 'stock' => 25],
            ['name' => 'Stand para Smartphone', 'slug' => 'stand-smartphone', 'description' => 'Suporte ajustável para celular em diferentes ângulos', 'price' => 49.90, 'stock' => 200],
            ['name' => 'SSD Externo 1TB', 'slug' => 'ssd-1tb', 'description' => 'SSD portátil com conexão USB-C rápida', 'price' => 299.90, 'stock' => 70],
        ];

        foreach ($products as $product) {
            $p = Product::create(array_merge(['category_id' => $category->id, 'sku' => uniqid()], $product));

            ProductImage::create([
                'product_id' => $p->id,
                'image_url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=300&fit=crop',
                'is_primary' => true
            ]);

            // Variações de capacidade/cores
            $variations = ['Preto', 'Branco', 'Cinza Espacial'];
            foreach ($variations as $var) {
                ProductVariation::create([
                    'product_id' => $p->id,
                    'type' => 'cor',
                    'value' => $var,
                    'price_modifier' => 0
                ]);
            }
        }
    }

    private function createDecorationProducts($category): void
    {
        $products = [
            ['name' => 'Quadro Abstrato', 'slug' => 'quadro-abstrato', 'description' => 'Quadro com arte abstrata moderna para sala', 'price' => 189.90, 'stock' => 40],
            ['name' => 'Luminária Neon', 'slug' => 'luminaria-neon', 'description' => 'Luminária de neon RGB com diferentes cores', 'price' => 129.90, 'stock' => 60],
            ['name' => 'Vaso Decorativo', 'slug' => 'vaso-decorativo', 'description' => 'Vaso de cerâmica com design moderno', 'price' => 99.90, 'stock' => 80],
            ['name' => 'Espelho Grande', 'slug' => 'espelho-grande', 'description' => 'Espelho decorativo de parede com moldura dourada', 'price' => 249.90, 'stock' => 30],
            ['name' => 'Tapete Persa', 'slug' => 'tapete-persa', 'description' => 'Tapete com padrão tradicional persa', 'price' => 349.90, 'stock' => 20],
            ['name' => 'Almofada Decorativa', 'slug' => 'almofada-decorativa', 'description' => 'Almofada com estampa geométrica para sofá', 'price' => 79.90, 'stock' => 120],
            ['name' => 'Cortina Blackout', 'slug' => 'cortina-blackout', 'description' => 'Cortina que bloqueia a luz, ideal para dormir', 'price' => 199.90, 'stock' => 50],
            ['name' => 'Prateleira Flutuante', 'slug' => 'prateleira-flutuante', 'description' => 'Prateleira de madeira com suporte invisível', 'price' => 159.90, 'stock' => 70],
            ['name' => 'Arandela de Parede', 'slug' => 'arandela-parede', 'description' => 'Luminária de parede moderna em vidro', 'price' => 179.90, 'stock' => 45],
            ['name' => 'Planta Artificial', 'slug' => 'planta-artificial', 'description' => 'Planta decorativa realista, sem manutenção', 'price' => 109.90, 'stock' => 100],
            ['name' => 'Moldura Flutuante', 'slug' => 'moldura-flutuante', 'description' => 'Moldura para fotos com efeito 3D', 'price' => 69.90, 'stock' => 90],
        ];

        foreach ($products as $product) {
            $p = Product::create(array_merge(['category_id' => $category->id, 'sku' => uniqid()], $product));

            ProductImage::create([
                'product_id' => $p->id,
                'image_url' => 'https://images.unsplash.com/photo-1578500494198-246f612d03b3?w=400&h=300&fit=crop',
                'is_primary' => true
            ]);

            $colors = ['Natural', 'Preto', 'Branco', 'Dourado'];
            foreach ($colors as $color) {
                ProductVariation::create([
                    'product_id' => $p->id,
                    'type' => 'cor',
                    'value' => $color,
                    'price_modifier' => 0
                ]);
            }
        }
    }

    private function createShoesProducts($category): void
    {
        $products = [
            ['name' => 'Tênis Esportivo', 'slug' => 'tenis-esportivo', 'description' => 'Tênis confortável para atividades esportivas', 'price' => 249.90, 'stock' => 80],
            ['name' => 'Tênis Casual Branco', 'slug' => 'tenis-casual-branco', 'description' => 'Tênis clássico branco versátil para qualquer look', 'price' => 189.90, 'stock' => 100],
            ['name' => 'Sapatênis', 'slug' => 'sapatennis', 'description' => 'Sapatênis elegante para uso social e casual', 'price' => 179.90, 'stock' => 70],
            ['name' => 'Sapato Social Preto', 'slug' => 'sapato-social', 'description' => 'Sapato elegante para eventos formais', 'price' => 229.90, 'stock' => 50],
            ['name' => 'Sandália de Dedo', 'slug' => 'sandalia-dedo', 'description' => 'Sandália confortável para praia e piscina', 'price' => 79.90, 'stock' => 150],
            ['name' => 'Chinelo de Couro', 'slug' => 'chinelo-couro', 'description' => 'Chinelo confortável feito de couro genuíno', 'price' => 99.90, 'stock' => 120],
            ['name' => 'Bota de Inverno', 'slug' => 'bota-inverno', 'description' => 'Bota alta para manter os pés quentes', 'price' => 299.90, 'stock' => 40],
            ['name' => 'Sapatilha de Ballet', 'slug' => 'sapatilha-ballet', 'description' => 'Sapatilha profissional para ballet', 'price' => 169.90, 'stock' => 30],
            ['name' => 'Sapato Feminino Social', 'slug' => 'sapato-feminino-social', 'description' => 'Sapato com salto para look elegante', 'price' => 199.90, 'stock' => 60],
            ['name' => 'Tenis Running', 'slug' => 'tenis-running', 'description' => 'Tênis especializado para corrida com tecnologia amortecida', 'price' => 349.90, 'stock' => 50],
            ['name' => 'Mocassim Casual', 'slug' => 'mocassim-casual', 'description' => 'Mocassim confortável para uso casual', 'price' => 159.90, 'stock' => 75],
        ];

        foreach ($products as $product) {
            $p = Product::create(array_merge(['category_id' => $category->id, 'sku' => uniqid()], $product));

            ProductImage::create([
                'product_id' => $p->id,
                'image_url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=300&fit=crop',
                'is_primary' => true
            ]);

            $sizes = ['34', '35', '36', '37', '38', '39', '40', '41', '42'];
            foreach ($sizes as $size) {
                ProductVariation::create([
                    'product_id' => $p->id,
                    'type' => 'tamanho',
                    'value' => $size,
                    'price_modifier' => 0
                ]);
            }
        }
    }

    private function createBooksProducts($category): void
    {
        $products = [
            ['name' => 'O Hobbit', 'slug' => 'o-hobbit', 'description' => 'Clássico da fantasia de J.R.R. Tolkien', 'price' => 69.90, 'stock' => 100],
            ['name' => '1984', 'slug' => '1984', 'description' => 'Distopia clássica de George Orwell', 'price' => 59.90, 'stock' => 90],
            ['name' => 'Orgulho e Preconceito', 'slug' => 'orgulho-preconceito', 'description' => 'Romance de Jane Austen, obra-prima da literatura', 'price' => 49.90, 'stock' => 120],
            ['name' => 'O Senhor dos Anéis', 'slug' => 'senhor-aneis', 'description' => 'Trilogia épica de fantasia', 'price' => 149.90, 'stock' => 60],
            ['name' => 'Cem Anos de Solidão', 'slug' => 'cem-anos-solidao', 'description' => 'Obra-prima de García Márquez', 'price' => 64.90, 'stock' => 75],
            ['name' => 'O Apanhador no Campo de Centeio', 'slug' => 'apanhador', 'description' => 'Clássico de J.D. Salinger', 'price' => 54.90, 'stock' => 80],
            ['name' => 'Dom Casmurro', 'slug' => 'dom-casmurro', 'description' => 'Romance de Machado de Assis', 'price' => 39.90, 'stock' => 110],
            ['name' => 'Grande Sertão Veredas', 'slug' => 'grande-sertao', 'description' => 'Obra-prima de Guimarães Rosa', 'price' => 74.90, 'stock' => 50],
            ['name' => 'O Código Da Vinci', 'slug' => 'codigo-vinci', 'description' => 'Thriller de mistério de Dan Brown', 'price' => 59.90, 'stock' => 70],
            ['name' => 'Sapiens', 'slug' => 'sapiens', 'description' => 'História da humanidade por Yuval Harari', 'price' => 84.90, 'stock' => 65],
            ['name' => 'O Hábito do Bem', 'slug' => 'habito-bem', 'description' => 'Desenvolvimento pessoal e hábitos', 'price' => 79.90, 'stock' => 85],
        ];

        foreach ($products as $product) {
            $p = Product::create(array_merge(['category_id' => $category->id, 'sku' => uniqid()], $product));

            ProductImage::create([
                'product_id' => $p->id,
                'image_url' => 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=400&h=300&fit=crop',
                'is_primary' => true
            ]);
        }
    }

    private function createSportsProducts($category): void
    {
        $products = [
            ['name' => 'Tapete de Yoga', 'slug' => 'tapete-yoga', 'description' => 'Tapete confortável para prática de yoga', 'price' => 129.90, 'stock' => 80],
            ['name' => 'Haltere Ajustável', 'slug' => 'haltere-ajustavel', 'description' => 'Kit de halteres com pesos ajustáveis', 'price' => 249.90, 'stock' => 50],
            ['name' => 'Fita Elástica', 'slug' => 'fita-elastica', 'description' => 'Fita elástica para exercícios de resistência', 'price' => 49.90, 'stock' => 150],
            ['name' => 'Corda de Pular', 'slug' => 'corda-pular', 'description' => 'Corda de pular profissional com contas de velocidade', 'price' => 69.90, 'stock' => 100],
            ['name' => 'Bola de Exercício', 'slug' => 'bola-exercicio', 'description' => 'Bola stability para fortalecimento', 'price' => 99.90, 'stock' => 70],
            ['name' => 'Esteira Manual', 'slug' => 'esteira-manual', 'description' => 'Esteira compacta para caminhada', 'price' => 499.90, 'stock' => 20],
            ['name' => 'Mochila de Trilha', 'slug' => 'mochila-trilha', 'description' => 'Mochila confortável para caminhadas', 'price' => 179.90, 'stock' => 60],
            ['name' => 'Garrafa Térmica', 'slug' => 'garrafa-termica', 'description' => 'Garrafa que mantém bebidas quentes ou frias', 'price' => 119.90, 'stock' => 100],
            ['name' => 'Relógio Esportivo', 'slug' => 'relogio-esportivo', 'description' => 'Relógio inteligente com rastreamento de atividades', 'price' => 399.90, 'stock' => 40],
            ['name' => 'Cintura de Neoprene', 'slug' => 'cintura-neoprene', 'description' => 'Cintura aquecedora para treinos', 'price' => 59.90, 'stock' => 120],
            ['name' => 'Luvas de Boxe', 'slug' => 'luvas-boxe', 'description' => 'Luvas profissionais para treino de boxe', 'price' => 149.90, 'stock' => 50],
        ];

        foreach ($products as $product) {
            $p = Product::create(array_merge(['category_id' => $category->id, 'sku' => uniqid()], $product));

            ProductImage::create([
                'product_id' => $p->id,
                'image_url' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=400&h=300&fit=crop',
                'is_primary' => true
            ]);

            $sizes = ['P', 'M', 'G', 'GG'];
            foreach ($sizes as $size) {
                ProductVariation::create([
                    'product_id' => $p->id,
                    'type' => 'tamanho',
                    'value' => $size,
                    'price_modifier' => 0
                ]);
            }
        }
    }

    private function createBeautyProducts($category): void
    {
        $products = [
            ['name' => 'Shampoo Premium', 'slug' => 'shampoo-premium', 'description' => 'Shampoo com ingredientes naturais', 'price' => 49.90, 'stock' => 150],
            ['name' => 'Condicionador Profissional', 'slug' => 'condicionador', 'description' => 'Condicionador para cabelos danificados', 'price' => 54.90, 'stock' => 130],
            ['name' => 'Sérum Facial', 'slug' => 'serum-facial', 'description' => 'Sérum anti-envelhecimento com vitamina C', 'price' => 129.90, 'stock' => 80],
            ['name' => 'Creme Hidratante', 'slug' => 'creme-hidratante', 'description' => 'Creme hidratante para pele seca', 'price' => 79.90, 'stock' => 100],
            ['name' => 'Máscara Facial', 'slug' => 'mascara-facial', 'description' => 'Máscara de limpeza profunda', 'price' => 59.90, 'stock' => 110],
            ['name' => 'Protetor Solar', 'slug' => 'protetor-solar', 'description' => 'Protetor solar FPS 50+ para proteção máxima', 'price' => 69.90, 'stock' => 140],
            ['name' => 'Demaquilante', 'slug' => 'demaquilante', 'description' => 'Demaquilante líquido suave', 'price' => 39.90, 'stock' => 160],
            ['name' => 'Esfoliante Corporal', 'slug' => 'esfoliante-corporal', 'description' => 'Esfoliante com grãos naturais', 'price' => 44.90, 'stock' => 120],
            ['name' => 'Eau de Parfum', 'slug' => 'eau-de-parfum', 'description' => 'Perfume luxuoso com aroma duradouro', 'price' => 199.90, 'stock' => 50],
            ['name' => 'Batom Líquido', 'slug' => 'batom-liquido', 'description' => 'Batom líquido com acabamento mate', 'price' => 49.90, 'stock' => 180],
            ['name' => 'Rímel à Prova de Água', 'slug' => 'rimel', 'description' => 'Rímel waterproof de longa duração', 'price' => 59.90, 'stock' => 100],
        ];

        foreach ($products as $product) {
            $p = Product::create(array_merge(['category_id' => $category->id, 'sku' => uniqid()], $product));

            ProductImage::create([
                'product_id' => $p->id,
                'image_url' => 'https://images.unsplash.com/photo-1556228578-8c89e6adf883?w=400&h=300&fit=crop',
                'is_primary' => true
            ]);
        }
    }

    private function createKitchenProducts($category): void
    {
        $products = [
            ['name' => 'Jogo de Facas', 'slug' => 'jogo-facas', 'description' => 'Jogo com 5 facas de qualidade profissional', 'price' => 199.90, 'stock' => 60],
            ['name' => 'Panela de Pressão', 'slug' => 'panela-pressao', 'description' => 'Panela de pressão com tampas variáveis', 'price' => 249.90, 'stock' => 40],
            ['name' => 'Jogo de Taças', 'slug' => 'jogo-tacas', 'description' => 'Jogo com 6 taças de vidro cristal', 'price' => 119.90, 'stock' => 80],
            ['name' => 'Liquidificador Potente', 'slug' => 'liquidificador', 'description' => 'Liquidificador 1200W com 10 velocidades', 'price' => 299.90, 'stock' => 50],
            ['name' => 'Microondas', 'slug' => 'microondas', 'description' => 'Microondas com função grill integrada', 'price' => 399.90, 'stock' => 30],
            ['name' => 'Jogo de Panelas', 'slug' => 'jogo-panelas', 'description' => 'Jogo com 5 panelas antiaderentes', 'price' => 279.90, 'stock' => 45],
            ['name' => 'Tabuleiro de Corte', 'slug' => 'tabuleiro-corte', 'description' => 'Tabuleiro com superfície antimicrobiana', 'price' => 89.90, 'stock' => 120],
            ['name' => 'Escorredor de Macarrão', 'slug' => 'escorredor', 'description' => 'Escorredor resistente com alças confortáveis', 'price' => 49.90, 'stock' => 150],
            ['name' => 'Peneira de Trigo', 'slug' => 'peneira-trigo', 'description' => 'Peneira manual para peneirar farinha', 'price' => 39.90, 'stock' => 100],
            ['name' => 'Batedeira Elétrica', 'slug' => 'batedeira-eletrica', 'description' => 'Batedeira com múltiplas velocidades', 'price' => 199.90, 'stock' => 40],
            ['name' => 'Jarra de Suco', 'slug' => 'jarra-suco', 'description' => 'Jarra de vidro para servir sucos', 'price' => 69.90, 'stock' => 110],
        ];

        foreach ($products as $product) {
            $p = Product::create(array_merge(['category_id' => $category->id, 'sku' => uniqid()], $product));

            ProductImage::create([
                'product_id' => $p->id,
                'image_url' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=300&fit=crop',
                'is_primary' => true
            ]);

            $colors = ['Inox', 'Branco', 'Preto', 'Vermelho'];
            foreach ($colors as $color) {
                ProductVariation::create([
                    'product_id' => $p->id,
                    'type' => 'cor',
                    'value' => $color,
                    'price_modifier' => 0
                ]);
            }
        }
    }

    private function createAccessoriesProducts($category): void
    {
        $products = [
            ['name' => 'Bolsa de Couro', 'slug' => 'bolsa-couro', 'description' => 'Bolsa clássica de couro genuíno', 'price' => 299.90, 'stock' => 50],
            ['name' => 'Cinto Marrom', 'slug' => 'cinto-marrom', 'description' => 'Cinto de couro com fivela metálica', 'price' => 89.90, 'stock' => 100],
            ['name' => 'Relógio Analógico', 'slug' => 'relogio-analogico', 'description' => 'Relógio clássico com movimento suíço', 'price' => 399.90, 'stock' => 40],
            ['name' => 'Óculos de Sol', 'slug' => 'oculos-sol', 'description' => 'Óculos com proteção UV 100%', 'price' => 179.90, 'stock' => 80],
            ['name' => 'Lenço de Seda', 'slug' => 'lenco-seda', 'description' => 'Lenço de seda pura com estampa exclusiva', 'price' => 109.90, 'stock' => 70],
            ['name' => 'Carteira de Couro', 'slug' => 'carteira-couro', 'description' => 'Carteira com compartimentos para cartões', 'price' => 149.90, 'stock' => 90],
            ['name' => 'Mochila Jeans', 'slug' => 'mochila-jeans', 'description' => 'Mochila casual de jeans azul', 'price' => 129.90, 'stock' => 75],
            ['name' => 'Chapéu de Palha', 'slug' => 'chapeu-palha', 'description' => 'Chapéu para praia e passeios', 'price' => 79.90, 'stock' => 100],
            ['name' => 'Pulseira Dourada', 'slug' => 'pulseira-dourada', 'description' => 'Pulseira em aço inox com banho de ouro', 'price' => 199.90, 'stock' => 60],
            ['name' => 'Colar de Pérola', 'slug' => 'colar-perola', 'description' => 'Colar elegante com pérola genuína', 'price' => 249.90, 'stock' => 40],
            ['name' => 'Brinco de Diamante', 'slug' => 'brinco-diamante', 'description' => 'Brincos com diamantes sintéticos', 'price' => 189.90, 'stock' => 50],
        ];

        foreach ($products as $product) {
            $p = Product::create(array_merge(['category_id' => $category->id, 'sku' => uniqid()], $product));

            ProductImage::create([
                'product_id' => $p->id,
                'image_url' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=400&h=300&fit=crop',
                'is_primary' => true
            ]);

            $colors = ['Ouro', 'Prata', 'Rosê'];
            foreach ($colors as $color) {
                ProductVariation::create([
                    'product_id' => $p->id,
                    'type' => 'cor',
                    'value' => $color,
                    'price_modifier' => 0
                ]);
            }
        }
    }
}

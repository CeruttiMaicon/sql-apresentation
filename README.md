# SQL Performance Analysis - Apresentação de Otimização de Banco de Dados

## 📋 Sobre o Projeto

Este projeto foi desenvolvido para demonstrar e analisar o impacto de diferentes estratégias de otimização de banco de dados MySQL na performance de consultas SQL complexas. Através de comparações entre três estruturas de banco de dados distintas, o projeto visa apresentar:

- **Impacto de tipos de dados otimizados** (smallInteger vs bigInteger)
- **Benefícios de índices estratégicos** em consultas com JOINs
- **Análise de planos de execução** do MySQL
- **Métricas de performance** em cenários reais

## 🎯 Objetivo

Criar uma apresentação técnica que demonstre como otimizações aparentemente simples podem resultar em ganhos significativos de performance, especialmente em bancos de dados com grandes volumes de dados (milhões de registros).

## 🗄️ Estrutura do Banco de Dados

O projeto utiliza uma estrutura relacional composta por:

- **Estados**: 27 estados brasileiros
- **Cidades**: 210+ cidades brasileiras
- **Endereços**: 1.5 Milhões de registros de endereços
- **Clientes**: 1.5 Milhões de registros de clientes

### Relacionamentos

```
Estados (1) ──< (N) Cidades
Cidades (1) ──< (N) Endereços
Estados (1) ──< (N) Endereços
Endereços (1) ──< (N) Clientes
```

## 🔬 Metodologia de Testes

### Estruturação por Branches

Cada estrutura de banco de dados está implementada em uma branch específica do repositório:

- **`feature/banco-de-dados-base-laravel-1`**: Banco 1 - Estrutura Base (Sem Otimizações)
- **`feature/banco-de-dados-base-laravel-2`**: Banco 2 - Otimização de Tipos
- **`feature/banco-de-dados-base-laravel-3`**: Banco 3 - Otimização Completa

Para testar cada estrutura, faça checkout da branch correspondente:

```bash
# Banco 1
git checkout feature/banco-de-dados-base-laravel-1

# Banco 2
git checkout feature/banco-de-dados-base-laravel-2

# Banco 3
git checkout feature/banco-de-dados-base-laravel-3
```

### Três Cenários de Banco de Dados

#### Banco 1: Estrutura Base (Sem Otimizações)
**Branch**: `feature/banco-de-dados-base-laravel-1`

- Todos os campos de ID utilizam `bigInteger`
- Apenas índices automáticos de foreign keys
- **Objetivo**: Estabelecer baseline de performance

#### Banco 2: Otimização de Tipos
**Branch**: `feature/banco-de-dados-base-laravel-2`

- Estados e Cidades utilizam `smallInteger` (economia de 75% de espaço)
- Endereços e Clientes mantêm `bigInteger` (suportam milhões de registros)
- Apenas índices automáticos de foreign keys
- **Objetivo**: Demonstrar impacto de tipos de dados otimizados

#### Banco 3: Otimização Completa
**Branch**: `feature/banco-de-dados-base-laravel-3`

- Mesmas otimizações do Banco 2
- Índices estratégicos adicionais:
  - `idx_clientes_sexo` (índice na coluna sexo)
  - `idx_enderecos_cidade_id`
  - `idx_enderecos_estado_id`
  - `idx_enderecos_cep`
  - `idx_clientes_endereco_id`
  - `idx_clientes_email`
- **Objetivo**: Demonstrar impacto combinado de tipos + índices

## 📊 Consultas SQL de Teste

As consultas abaixo foram desenvolvidas para testar diferentes cenários de uso e demonstrar o impacto das otimizações. Todas utilizam `INNER JOIN` para relacionar as tabelas e filtram por cidade, bairro e sexo.

### Consulta 1: Filtro por Cidade → Bairro → Sexo

Esta consulta testa a eficiência do JOIN com a tabela de cidades primeiro, seguido pelos filtros de bairro e sexo.

```sql
-- =====================================================
-- CONSULTA 1: Clientes da cidade X no bairro Y do sexo masculino
-- =====================================================
-- Ordem das condições: cidade -> bairro -> sexo
-- Testa: Eficiência do JOIN com cidades e impacto do tipo de dado

SELECT 
    c.id AS cliente_id,
    c.nome AS cliente_nome,
    c.email,
    c.sexo,
    c.celular,
    e.rua,
    e.cep,
    e.numero,
    e.bairro,
    ci.nome AS cidade_nome,
    es.nome AS estado_nome,
    es.sigla AS estado_sigla
FROM clientes c
INNER JOIN enderecos e ON c.endereco_id = e.id
INNER JOIN cidades ci ON e.cidade_id = ci.id
INNER JOIN estados es ON e.estado_id = es.id
WHERE ci.nome = 'São Paulo'  -- Cidade X
  AND e.bairro = 'Centro'     -- Bairro Y
  AND c.sexo = 'M';           -- Sexo masculino
```

### Consulta 2: Filtro por Bairro → Cidade → Sexo

Esta consulta testa a eficiência do filtro por bairro primeiro, seguido pelos filtros de cidade e sexo.

```sql
-- =====================================================
-- CONSULTA 2: Clientes do bairro Y da cidade X do sexo masculino
-- =====================================================
-- Ordem das condições: bairro -> cidade -> sexo
-- Testa: Eficiência do filtro por bairro e impacto da ordem dos filtros

SELECT 
    c.id AS cliente_id,
    c.nome AS cliente_nome,
    c.email,
    c.sexo,
    c.celular,
    e.rua,
    e.cep,
    e.numero,
    e.bairro,
    ci.nome AS cidade_nome,
    es.nome AS estado_nome,
    es.sigla AS estado_sigla
FROM clientes c
INNER JOIN enderecos e ON c.endereco_id = e.id
INNER JOIN cidades ci ON e.cidade_id = ci.id
INNER JOIN estados es ON e.estado_id = es.id
WHERE e.bairro = 'Centro'    -- Bairro Y
  AND ci.nome = 'São Paulo'  -- Cidade X
  AND c.sexo = 'M';          -- Sexo masculino
```

### Consulta 3: Filtro por Sexo → Bairro → Cidade

Esta consulta testa a eficiência do índice em `sexo` (presente apenas no Banco 3), seguido pelos filtros de bairro e cidade.

```sql
-- =====================================================
-- CONSULTA 3: Clientes do sexo masculino do bairro Y da cidade X
-- =====================================================
-- Ordem das condições: sexo -> bairro -> cidade
-- Testa: Eficiência do índice em sexo (Banco 3) vs sem índice (Bancos 1 e 2)

SELECT 
    c.id AS cliente_id,
    c.nome AS cliente_nome,
    c.email,
    c.sexo,
    c.celular,
    e.rua,
    e.cep,
    e.numero,
    e.bairro,
    ci.nome AS cidade_nome,
    es.nome AS estado_nome,
    es.sigla AS estado_sigla
FROM clientes c
INNER JOIN enderecos e ON c.endereco_id = e.id
INNER JOIN cidades ci ON e.cidade_id = ci.id
INNER JOIN estados es ON e.estado_id = es.id
WHERE c.sexo = 'M'           -- Sexo masculino
  AND e.bairro = 'Centro'    -- Bairro Y
  AND ci.nome = 'São Paulo'; -- Cidade X
```

### Análise de Plano de Execução

Para entender como o MySQL executa cada consulta, utilize o comando `EXPLAIN`:

```sql
-- =====================================================
-- EXPLAIN: Análise de plano de execução
-- =====================================================
-- Execute antes de cada consulta para ver o plano de execução
-- Compare os resultados entre os 3 bancos de dados

EXPLAIN SELECT 
    c.id, c.nome, c.email, c.sexo,
    e.bairro, ci.nome AS cidade_nome
FROM clientes c
INNER JOIN enderecos e ON c.endereco_id = e.id
INNER JOIN cidades ci ON e.cidade_id = ci.id
WHERE ci.nome = 'São Paulo'
  AND e.bairro = 'Centro'
  AND c.sexo = 'M';
```

## 📈 Métricas de Análise

Ao executar as consultas em cada banco, colete as seguintes métricas:

1. **Tempo de Execução** (em milissegundos)
2. **Rows Examined** (do resultado do EXPLAIN)
3. **Type de JOIN** (ALL, index, ref, eq_ref, etc.)
4. **Índices Utilizados** (coluna `key` do EXPLAIN)
5. **Extra Information** (Using index, Using where, etc.)

## 🚀 Como Executar os Testes

### Pré-requisitos

- Docker e Docker Compose
- Laravel Sail (incluído no projeto)
- Acesso ao phpMyAdmin (porta 8100) ou cliente MySQL

### Passos

1. **Configurar os 3 bancos de dados** com estruturas diferentes
2. **Popular cada banco** com a mesma quantidade de dados
3. **Executar as consultas SQL** em cada banco
4. **Coletar métricas** de tempo e plano de execução
5. **Comparar resultados** e analisar diferenças

### Executando no phpMyAdmin

1. Acesse `http://localhost:8100`
2. Selecione o banco de dados a ser testado
3. Cole a consulta SQL na aba "SQL"
4. Execute e anote o tempo de execução
5. Execute `EXPLAIN` antes da consulta para ver o plano

## 📝 Resultados Esperados

### Banco 1 (Sem Otimizações)
- **Performance**: Mais lenta
- **Motivo**: JOINs com `bigInteger` ocupam mais memória, índices maiores

### Banco 2 (SmallInteger)
- **Performance**: Melhor que Banco 1
- **Motivo**: JOINs mais eficientes, índices 75% menores, mais dados em cache

### Banco 3 (SmallInteger + Índices)
- **Performance**: Melhor de todos
- **Motivo**: Todos os benefícios do Banco 2 + índices estratégicos (especialmente `idx_clientes_sexo`)

## 🔍 Conceitos Demonstrados

- **Otimização de Tipos de Dados**: Uso de `smallInteger` para tabelas pequenas
- **Índices Estratégicos**: Impacto de índices em colunas frequentemente consultadas
- **Plano de Execução**: Como o MySQL otimiza e executa consultas
- **JOINs Eficientes**: Impacto de tipos de dados em operações de JOIN
- **Cache e I/O**: Relação entre tamanho de índices e eficiência de cache

## 📚 Tecnologias Utilizadas

- **Laravel 12**: Framework PHP
- **MySQL 8.4**: Banco de dados relacional
- **Docker & Docker Compose**: Containerização
- **Laravel Sail**: Ambiente de desenvolvimento

## 📄 Licença

Este projeto é open-source e está disponível sob a [licença MIT](https://opensource.org/licenses/MIT).

---

**Desenvolvido para fins educacionais e de apresentação técnica sobre otimização de banco de dados.**

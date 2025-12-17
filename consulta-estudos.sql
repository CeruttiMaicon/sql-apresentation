-- =====================================================
-- TESTES DE PERFORMANCE - COMPARAÇÃO DE BANCOS DE DADOS
-- =====================================================

-- CONSULTA 1: Clientes da cidade X no bairro Y do sexo masculino
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
WHERE ci.nome = 'São Paulo'
  AND e.bairro = 'Centro'
  AND c.sexo = 'M';

-- CONSULTA 2: Clientes do bairro Y da cidade X do sexo masculino
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
WHERE e.bairro = 'Centro'
  AND ci.nome = 'São Paulo'
  AND c.sexo = 'M';

-- CONSULTA 3: Clientes do sexo masculino do bairro Y da cidade X
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
WHERE c.sexo = 'M'
  AND e.bairro = 'Centro'
  AND ci.nome = 'São Paulo';

-- EXPLAIN para análise de plano de execução
EXPLAIN SELECT 
    c.id, c.nome, c.email, c.sexo,
    e.bairro, ci.nome AS cidade_nome
FROM clientes c
INNER JOIN enderecos e ON c.endereco_id = e.id
INNER JOIN cidades ci ON e.cidade_id = ci.id
WHERE ci.nome = 'São Paulo'
  AND e.bairro = 'Centro'
  AND c.sexo = 'M';
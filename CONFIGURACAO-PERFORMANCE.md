# Configuração de Recursos Limitados para Testes de Performance

## Objetivo
Limitar recursos do MySQL no Docker para tornar as diferenças de performance entre os 3 bancos de dados mais evidentes e demonstráveis.

## Limitações Aplicadas

### 1. Recursos do Container (Docker)

#### CPU
- **Limite**: 0.5 CPU (50% de 1 core)
- **Reserva**: 0.25 CPU (25% de 1 core)
- **Efeito**: MySQL terá menos poder de processamento, tornando diferenças mais visíveis

#### Memória
- **Limite**: 512MB RAM
- **Reserva**: 256MB RAM
- **Efeito**: Cache menor = mais I/O = diferenças de otimização mais evidentes

### 2. Configurações do MySQL

#### Buffer Pool (InnoDB)
- **Valor**: `innodb-buffer-pool-size=128M`
- **Padrão**: Geralmente 50-70% da RAM disponível
- **Efeito**: Cache muito menor = mais leituras do disco = diferenças mais visíveis

#### Query Cache
- **Valor**: `query-cache-size=0` e `query-cache-type=0` (desabilitado)
- **Efeito**: Sem cache de queries = cada execução é "do zero" = comparações mais justas

#### Buffers de Operações
- `key-buffer-size=8M` (muito baixo)
- `sort-buffer-size=256K` (muito baixo)
- `read-buffer-size=128K` (muito baixo)
- `join-buffer-size=128K` (muito baixo)
- `tmp-table-size=8M` (muito baixo)
- `max-heap-table-size=8M` (muito baixo)

**Efeito**: Operações que precisam de mais memória serão mais lentas, amplificando diferenças

#### Conexões
- `max-connections=50` (baixo)
- **Efeito**: Limita concorrência, mas suficiente para testes sequenciais

## Por que isso torna as diferenças mais evidentes?

### 1. **Menos Cache = Mais I/O**
Com apenas 128MB de buffer pool:
- Índices menores (smallInteger) cabem mais em cache
- Índices maiores (bigInteger) precisam de mais I/O
- **Diferença**: Muito mais visível!

### 2. **Menos CPU = Processamento Mais Lento**
Com 0.5 CPU:
- Comparações de 2 bytes vs 8 bytes fazem diferença
- JOINs com índices menores são mais rápidos
- **Diferença**: Amplificada pela limitação de CPU

### 3. **Sem Query Cache = Testes Justos**
Com query cache desabilitado:
- Cada execução é "do zero"
- Não há benefício de cache entre execuções
- **Diferença**: Comparações mais precisas

### 4. **Buffers Pequenos = Operações Mais Lentas**
Com buffers mínimos:
- JOINs grandes precisam de mais operações
- Sorts e agregações são mais lentas
- **Diferença**: Otimizações fazem mais diferença

## Como Aplicar

1. **Parar containers atuais:**
```bash
sail down
```

2. **Recriar com novas configurações:**
```bash
sail up -d
```

3. **Verificar limites aplicados:**
```bash
docker stats sail-mysql-1
```

## Resultados Esperados

Com essas limitações, você deve ver:

### Banco 1 (Sem Otimizações)
- **Tempo**: Mais alto (ex: 500-1000ms)
- **I/O**: Alto (muitas leituras do disco)
- **CPU**: Alto uso (processamento ineficiente)

### Banco 2 (SmallInteger)
- **Tempo**: Médio (ex: 200-400ms)
- **I/O**: Médio (menos leituras, mais cache hits)
- **CPU**: Médio uso (processamento mais eficiente)

### Banco 3 (SmallInteger + Índices)
- **Tempo**: Baixo (ex: 50-150ms)
- **I/O**: Baixo (máximo aproveitamento de cache)
- **CPU**: Baixo uso (processamento otimizado)

## Ajustes Opcionais

Se quiser tornar ainda mais restritivo:

### Ainda mais limitado (para demonstrações extremas):
```yaml
cpus: '0.25'      # 25% de 1 CPU
memory: 256M      # Apenas 256MB
innodb-buffer-pool-size=64M
```

### Mais realista (para testes mais próximos da produção):
```yaml
cpus: '1.0'       # 1 CPU completo
memory: 1G        # 1GB RAM
innodb-buffer-pool-size=512M
```

## Monitoramento

### Ver uso de recursos em tempo real:
```bash
docker stats sail-mysql-1
```

### Ver configurações do MySQL:
```sql
SHOW VARIABLES LIKE '%buffer%';
SHOW VARIABLES LIKE '%cache%';
SHOW VARIABLES LIKE '%size%';
```

### Ver status do InnoDB:
```sql
SHOW ENGINE INNODB STATUS\G
```

## Importante

⚠️ **Essas configurações são apenas para testes de performance!**

Para produção, use:
- Buffer pool: 50-70% da RAM disponível
- CPU: Sem limites ou limites generosos
- Memória: Suficiente para o workload
- Query cache: Pode ser útil dependendo do caso

## Restaurar Configurações Normais

Para voltar ao normal, remova as seções `deploy` e ajuste `MYSQL_EXTRA_OPTIONS`:

```yaml
mysql:
    # ... outras configurações ...
    environment:
        MYSQL_EXTRA_OPTIONS: '${MYSQL_EXTRA_OPTIONS:-}'
    # Remover seção deploy
```

Ou simplesmente remova as linhas de `deploy` e as opções extras do MySQL.

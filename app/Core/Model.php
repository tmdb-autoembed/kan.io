<?php
declare(strict_types=1);

namespace ThemeHub\Core;

use PDO;

abstract class Model
{
    protected string $table = '';
    protected string $primaryKey = 'id';
    protected array $fillable = [];
    protected array $hidden = [];
    protected array $casts = [];
    protected bool $timestamps = true;

    protected PDO $db;

    // Query builder state
    private array $wheres = [];
    private array $whereParams = [];
    private string $orderByClause = '';
    private ?int $limitClause = null;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(string $orderBy = 'id DESC', int $limit = 100, int $offset = 0): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy} LIMIT {$limit} OFFSET {$offset}";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function find(int|string $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findBy(string $column, mixed $value): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$value]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function where(string $column, mixed $value, string $operator = '='): static
    {
        $clone = clone $this;
        $clone->wheres[] = "{$column} {$operator} ?";
        $clone->whereParams[] = $value;
        return $clone;
    }

    public function orderBy(string $column, string $direction = 'ASC'): static
    {
        $clone = clone $this;
        $clone->orderByClause = "{$column} {$direction}";
        return $clone;
    }

    public function limit(int $limit): static
    {
        $clone = clone $this;
        $clone->limitClause = $limit;
        return $clone;
    }

    public function search(string $query): static
    {
        $clone = clone $this;
        $clone->wheres[] = "(name LIKE ? OR description LIKE ?)";
        $clone->whereParams[] = "%{$query}%";
        $clone->whereParams[] = "%{$query}%";
        return $clone;
    }

    public function get(): array
    {
        $sql = "SELECT * FROM {$this->table}";

        if (!empty($this->wheres)) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }

        if ($this->orderByClause) {
            $sql .= ' ORDER BY ' . $this->orderByClause;
        }

        if ($this->limitClause !== null) {
            $sql .= ' LIMIT ' . $this->limitClause;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($this->whereParams);
        return $stmt->fetchAll();
    }

    // Allow using the query builder result as array directly
    public function __invoke(): array
    {
        return $this->get();
    }

    public function paginate(int $page = 1, int $perPage = 20, string $orderBy = 'id DESC'): array
    {
        $whereClause = '';
        if (!empty($this->wheres)) {
            $whereClause = ' WHERE ' . implode(' AND ', $this->wheres);
        }

        $countSql = "SELECT COUNT(*) as count FROM {$this->table}{$whereClause}";
        $stmt = $this->db->prepare($countSql);
        $stmt->execute($this->whereParams);
        $total = (int)$stmt->fetch()['count'];

        $pagination = paginate($total, $page, $perPage);

        $order = $this->orderByClause ?: $orderBy;
        $sql = "SELECT * FROM {$this->table}{$whereClause} ORDER BY {$order} LIMIT {$perPage} OFFSET {$pagination['offset']}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($this->whereParams);
        $items = $stmt->fetchAll();

        return [
            'items' => $items,
            'pagination' => $pagination,
        ];
    }

    public function create(array $data): int|string
    {
        if ($this->timestamps) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        $filteredData = $this->filterFillable($data);
        $columns = implode(', ', array_keys($filteredData));
        $placeholders = ':' . implode(', :', array_keys($filteredData));

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($this->castValues($filteredData));

        return (int)$this->db->lastInsertId();
    }

    public function update(int|string $id, array $data): bool
    {
        if ($this->timestamps) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        $filteredData = $this->filterFillable($data);
        $setClause = implode(', ', array_map(fn($col) => "{$col} = :{$col}", array_keys($filteredData)));

        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = :__id";
        $stmt = $this->db->prepare($sql);
        $filteredData['__id'] = $id;

        return $stmt->execute($this->castValues($filteredData));
    }

    public function delete(int|string $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function count(string $where = '1=1', array $params = []): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE {$where}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetch()['count'];
    }

    protected function filterFillable(array $data): array
    {
        if (empty($this->fillable)) {
            return $data;
        }

        return array_intersect_key($data, array_flip($this->fillable));
    }

    protected function castValues(array $data): array
    {
        foreach ($data as $key => $value) {
            if (isset($this->casts[$key])) {
                $data[$key] = match ($this->casts[$key]) {
                    'int' => (int)$value,
                    'float' => (float)$value,
                    'bool' => (bool)$value,
                    'array', 'json' => is_array($value) ? json_encode($value) : $value,
                    'datetime' => date('Y-m-d H:i:s', strtotime($value)),
                    default => $value,
                };
            }
        }

        return $data;
    }

    public function toArray(?array $record = null): array
    {
        $record = $record ?? [];

        if (!empty($this->hidden)) {
            $record = array_diff_key($record, array_flip($this->hidden));
        }

        return $record;
    }
}

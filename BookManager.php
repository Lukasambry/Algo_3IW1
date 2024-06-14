<?php

require_once 'Book.php';

// Classe BookManager pour gérer les opérations sur les livres
class BookManager {
    private $books = [];
    private $filename = 'books.json';
    private $logfile = 'history.log';

    public function __construct() {
        $this->loadBooks();
    }

    // Validation du livre (nom et description non vides, nom unique)
    private function validateBook($name, $description) {
        if (empty($name) || empty($description)) {
            throw new Exception("Name and description cannot be empty.");
        }
        foreach ($this->books as $book) {
            if (strcasecmp($book->name, $name) == 0) {
                throw new Exception("A book with the same name already exists.");
            }
        }
    }

    // Chargement des livres à partir du fichier
    private function loadBooks() {
        if (file_exists($this->filename)) {
            $data = json_decode(file_get_contents($this->filename), true);
            foreach ($data as $bookData) {
                $this->books[] = new Book($bookData['id'], $bookData['name'], $bookData['description'], $bookData['inStock']);
            }
        }
    }

    // Sauvegarde des livres dans le fichier
    private function saveBooks() {
        $data = array_map(function ($book) {
            return ['id' => $book->id, 'name' => $book->name, 'description' => $book->description, 'inStock' => $book->inStock];
        }, $this->books);
        file_put_contents($this->filename, json_encode($data, JSON_PRETTY_PRINT));
    }

    // Enregistrement des actions dans le fichier log
    private function logAction($action) {
        file_put_contents($this->logfile, $action . PHP_EOL, FILE_APPEND);
    }

    // Ajout d'un livre
    public function addBook($name, $description, $inStock) {
        $this->validateBook($name, $description);
        $id = count($this->books) ? max(array_map(function ($book) { return $book->id; }, $this->books)) + 1 : 1;
        $book = new Book($id, $name, $description, $inStock); 
        $this->books[] = $book;
        $this->saveBooks(); 
        $this->logAction("Added book: {$book->name}"); 
        return $book;
    }

    // Mise à jour d'un livre
    public function updateBook($id, $name, $description, $inStock) {
        if (empty($name) || empty($description)) {
            throw new Exception("Name and description cannot be empty. Nothing changed, please retry.");
        }
        if($inStock !== "yes" && $inStock !== "no"){
            throw new Exception("Stock must be yes or no");
        }
        foreach ($this->books as $book) {
            if ($book->id != $id && strcasecmp($book->name, $name) == 0) {
                throw new Exception("A book with the same name already exists.");
            }
        }
        foreach ($this->books as $book) {
            if ($book->id == $id) {
                $book->name = $name;
                $book->description = $description; 
                $book->inStock = $inStock;
                $this->saveBooks();
                $this->logAction("Updated book: {$book->name}");
                return $book;
            }
        }
        return null;
    }

    // Suppression d'un livre
    public function deleteBook($id) {
        foreach ($this->books as $index => $book) {
            if ($book->id == $id) {
                array_splice($this->books, $index, 1);
                $this->saveBooks();
                $this->logAction("Deleted book: {$book->name}");
                return true;
            }
        }
        return false;
    }

    // Récupération de tous les livres
    public function getBooks() {
        return $this->books;
    }

    // Récupération d'un livre par son ID
    public function getBook($id) {
        foreach ($this->books as $book) {
            if ($book->id == $id) {
                return $book;
            }
        }
        return null;
    }

    // Tri des livres
    public function sortBooks($column, $order = 'asc') {
        usort($this->books, function ($a, $b) use ($column, $order) {
            if ($a->$column == $b->$column) {
                return 0;
            }
            if ($order == 'asc') {
                return ($a->$column < $b->$column) ? -1 : 1;
            } else {
                return ($a->$column > $b->$column) ? -1 : 1;
            }
        });
        $this->logAction("Sorted books by $column in $order order");
        return $this->books;
    }

    // Recherche de livres
    public function searchBooks($column, $value) {
        $this->sortBooks($column, 'asc'); // Trie les livres avant la recherche
        $low = 0;
        $high = count($this->books) - 1;

        // Recherche binaire
        while ($low <= $high) {
            $mid = floor(($low + $high) / 2);
            if (stripos($this->books[$mid]->$column, $value) !== false) {
                return $this->books[$mid];
            } elseif (strcasecmp($this->books[$mid]->$column, $value) < 0) {
                $low = $mid + 1;
            } else {
                $high = $mid - 1;
            }
        }
        return null;
    }
}
?>

<?php

require_once 'BookManager.php';

$manager = new BookManager();

// Fonction pour valider si le livre est en stock
function validateInStock() {
    while (true) {
        $inStock = readInput("Is the book in stock (yes/no): ");
        if ($inStock === 'yes' || $inStock === 'no') {
            return $inStock === 'yes';
        } else {
            echo "Invalid input. Please enter 'yes' or 'no'.\n";
        }
    }
}

// Fonction pour valider le nom du livre
function validateName(){
    while(true){
        $name = readInput("Enter the name of the book: ");
        if(empty($name)){
            echo "Name cannot be empty.\n";
        } else {
            return $name;
        }
    }
}

// Fonction pour valider la description du livre
function validateDescription(){
    while(true){
        $description = readInput("Enter the description of the book: ");
        if(empty($description)){
            echo "Description cannot be empty.\n";
        } else {
            return $description;
        }
    }
}

// Fonction pour ajouter un livre
function addBook() {
    global $manager;
    while (true) {
        try {
            $name = validateName();
            $description = validateDescription();
            $inStock = validateInStock();
            $manager->addBook($name, $description, $inStock);
            echo "Book added successfully.\n";
            break;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
            return;
        }
    }
}

// Fonction pour mettre à jour un livre
function updateBook() {
    global $manager;
    while (true) {
        try {
            $id = (int)readInput("Enter the ID of the book to update: ");
            if ($manager->getBook($id) === null) {
                echo "Book not found.\n";
                return;
            }
            $name = readInput("Enter the new name of the book: ");
            $description = readInput("Enter the new description of the book: ");
            $inStock = readInput("Is the book in stock (yes/no): ") === 'yes';
            if ($manager->updateBook($id, $name, $description, $inStock)) {
                echo "Book updated successfully.\n";
                break;
            } else {
                echo "Book not found.\n";
                return;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
            return;
        }
    }
}

// Fonction pour supprimer un livre
function deleteBook() {
    global $manager;
    $id = (int)readInput("Enter the ID of the book to delete: ");
    if ($manager->getBook($id) === null) {
        echo "Book not found.\n";
        return;
    }
    if ($manager->deleteBook($id)) {
        echo "Book deleted with success.\n";
    } else {
        echo "Book not found.\n";
    }
}

// Fonction pour afficher tous les livres
function displayBooks() {
    global $manager;
    $books = $manager->getBooks();
    foreach ($books as $book) {
        echo "ID: {$book->id}, Name: {$book->name}, Description: {$book->description}, In stock: " . ($book->inStock ? "Yes" : "No") . "\n";
    }
}

// Fonction pour afficher un livre spécifique
function displayBook() {
    global $manager;
    $id = (int)readInput("Enter Book ID: ");
    $book = $manager->getBook($id);
    if ($book) {
        echo "ID: {$book->id},\n Name: {$book->name}, \n Description: {$book->description}, \n In stock: " . ($book->inStock ? "Yes" : "No") . "\n";
    } else {
        echo "Book not found.\n";
    }
}

// Fonction pour trier les livres
function sortBooks() {
    global $manager;
    while (true) {
        $column = readInput("Enter the column to sort by (name, description, inStock): ");
        if (!in_array($column, ['name', 'description', 'inStock'])) {
            echo "Invalid column name. Please enter a valid column (name, description, inStock).\n";
            continue;
        }
        $order = readInput("Enter the sort order (asc/desc): ");
        if (!in_array($order, ['asc', 'desc'])) {
            echo "Invalid sort order. Please enter 'asc' or 'desc'.\n";
            continue;
        }
        $sortedBooks = $manager->sortBooks($column, $order);
        foreach ($sortedBooks as $book) {
            echo "ID: {$book->id}, Name: {$book->name}, Description: {$book->description}, In stock: " . ($book->inStock ? "Yes" : "No") . "\n";
        }
        break;
    }
}

// Fonction pour rechercher un livre
function searchBook() {
    global $manager;
    while(true){
        $column = readInput("Enter the column to search by (id, name, description, inStock): ");
        if (!in_array($column, ['id', 'name', 'description', 'inStock'])) {
            echo "Invalid column name. Please enter a valid column (name, description, inStock, id).\n";
            continue;
        }
        $value = readInput("Enter the value to search for: ");
        $searchedBook = $manager->searchBooks($column, $value);
        if ($searchedBook) {
            echo "Book found: ID: {$searchedBook->id}, Name: {$searchedBook->name}, Description: {$searchedBook->description}, In stock: " . ($searchedBook->inStock ? "Yes" : "No") . "\n";
        } else {
            echo "Book not found.\n";
        }
        break;
    }
}

// Fonction pour lire l'input
function readInput($prompt) {
    echo $prompt;
    return trim(fgets(STDIN));
}

?>

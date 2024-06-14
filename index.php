<?php

require_once 'functions.php';

// Fonction pour afficher le menu principal
function displayMenu() {
    echo "----------------------------------\n";
    echo "1. Add book\n";
    echo "2. Modify book\n";
    echo "3. Delete book\n";
    echo "4. Show books\n";
    echo "5. Show book\n";
    echo "6. Sort books\n";
    echo "7. Search for a book\n";
    echo "8. Exit\n";
    echo "----------------------------------\n";
    echo "Choose an option: ";
}

// Boucle principale pour gérer le menu et les choix de l'utilisateur
while (true) {
    displayMenu();             
    $choice = readInput(""); 

    // Exécute la fonction appropriée en fonction du choix
    switch ($choice) {
        case 1:
            addBook();
            break;
        case 2:
            updateBook();
            break;
        case 3:
            deleteBook();
            break;
        case 4:
            displayBooks();
            break;
        case 5:
            displayBook();
            break;
        case 6:
            sortBooks();
            break;
        case 7:
            searchBook();
            break;
        case 8:
            echo "Bye!\n";
            exit;
        default:
            echo "Invalid option. Please try again.\n"; // Gère les entrées invalides
            break;
    }
}
?>

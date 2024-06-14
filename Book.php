<?php

class Book {
    public $id;
    public $name;
    public $description;
    public $inStock;

    // Add a constructor that initializes all properties
    public function __construct($id, $name, $description, $inStock) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->inStock = $inStock;
    }
}
?>

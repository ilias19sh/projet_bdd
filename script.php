<?php
$servername = "mysql-ilias.alwaysdata.net";
$username = "ilias";
$password = "Nextu2025";
$dbname = "ilias_project";

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

// Table User
$sql = "CREATE TABLE IF NOT EXISTS User (
    User_id CHAR(36) PRIMARY KEY,
    FirstName VARCHAR(30) NOT NULL,
    LastName VARCHAR(30) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    Score INT NOT NULL DEFAULT 0,
    Password VARCHAR(255) NOT NULL,
    CreationDate DATE NOT NULL DEFAULT CURRENT_DATE,
    Avatar VARCHAR(255)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table User créée avec succès<br>";
} else {
    echo "Erreur lors de la création de User: " . $conn->error . "<br>";
}

// Table Admin
$sql = "CREATE TABLE IF NOT EXISTS Admin (
    Admin_id CHAR(36) PRIMARY KEY,
    FirstName VARCHAR(30) NOT NULL,
    LastName VARCHAR(30) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    Password VARCHAR(255) NOT NULL
)";
$conn->query($sql);

// Table Quotation
$sql = "CREATE TABLE IF NOT EXISTS Quotation (
    Quotation_id CHAR(36) PRIMARY KEY,
    Autor VARCHAR(100) NOT NULL,
    Content TEXT NOT NULL
)";
$conn->query($sql);

// Table Challenge
$sql = "CREATE TABLE IF NOT EXISTS Challenge (
    Challenge_id CHAR(36) PRIMARY KEY,
    Name VARCHAR(30) NOT NULL,
    Description TEXT NOT NULL,
    CreationDate DATE NOT NULL DEFAULT CURRENT_DATE,
    Type VARCHAR(100) NOT NULL,
    Score INT DEFAULT 0,
    Status VARCHAR(30) NOT NULL,
    Quotation_id CHAR(36),
    Approve_id CHAR(36),
    FOREIGN KEY (Quotation_id) REFERENCES Quotation(Quotation_id)
)";
$conn->query($sql);

// Table Comment
$sql = "CREATE TABLE IF NOT EXISTS Comment (
    Comment_id CHAR(36) PRIMARY KEY,
    Date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Content TEXT NOT NULL,
    Status VARCHAR(30),
    User_id CHAR(36) NOT NULL,
    Challenge_id CHAR(36) NOT NULL,
    FOREIGN KEY (User_id) REFERENCES User(User_id),
    FOREIGN KEY (Challenge_id) REFERENCES Challenge(Challenge_id)
)";
$conn->query($sql);

// Table Experience
$sql = "CREATE TABLE IF NOT EXISTS Experience (
    Experience_id CHAR(36) PRIMARY KEY,
    Date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    User_id CHAR(36) NOT NULL,
    Challenge_id CHAR(36) NOT NULL,
    NeedHelp BOOLEAN NOT NULL DEFAULT FALSE,
    Status VARCHAR(255) NOT NULL,
    FOREIGN KEY (User_id) REFERENCES User(User_id),
    FOREIGN KEY (Challenge_id) REFERENCES Challenge(Challenge_id)
)";
$conn->query($sql);

// Table Donations
$sql = "CREATE TABLE IF NOT EXISTS Donations (
    Donation_id CHAR(36) PRIMARY KEY,
    Amount DECIMAL(10,2) NOT NULL,
    Reason VARCHAR(255),
    IsPublic BOOLEAN NOT NULL DEFAULT TRUE,
    Date DATE NOT NULL DEFAULT CURRENT_DATE,
    User_id CHAR(36) NOT NULL,
    FOREIGN KEY (User_id) REFERENCES User(User_id)
)";
$conn->query($sql);

// Table Approvments
$sql = "CREATE TABLE IF NOT EXISTS Approvments (
    Approve_id CHAR(36) PRIMARY KEY,
    Comment_id CHAR(36) NOT NULL,
    Challenge_id CHAR(36) NOT NULL,
    Approved BOOLEAN NOT NULL DEFAULT FALSE,
    Reason VARCHAR(255),
    Admin_id CHAR(36) NOT NULL,
    FOREIGN KEY (Comment_id) REFERENCES Comment(Comment_id),
    FOREIGN KEY (Challenge_id) REFERENCES Challenge(Challenge_id),
    FOREIGN KEY (Admin_id) REFERENCES Admin(Admin_id)
)";
$conn->query($sql);


// Générer un UUID en PHP
function guidv4() {
    if (function_exists('com_create_guid') === true) {
        return trim(com_create_guid(), '{}');
    }
    $data = random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

// Insérer un User
$userId = guidv4();
$password = password_hash('password123', PASSWORD_BCRYPT);
$sql = "INSERT INTO User (User_id, FirstName, LastName, Email, Score, Password) 
        VALUES ('$userId', 'John', 'Doe', 'john.doe@example.com', 0, '$password')";
$conn->query($sql);

// Insérer un Challenge
$challengeId = guidv4();
$sql = "INSERT INTO Challenge (Challenge_id, Name, Description, Type, Status) 
        VALUES ('$challengeId', 'Be Kind', 'Do one kind action today', 'Soft Skill', 'Active')";
$conn->query($sql);

// Insérer une Experience
$experienceId = guidv4();
$sql = "INSERT INTO Experience (Experience_id, User_id, Challenge_id, NeedHelp, Status) 
        VALUES ('$experienceId', '$userId', '$challengeId', FALSE, 'Completed')";
$conn->query($sql);

echo "Exemples insérés avec succès.<br>";

$conn->close();
?>
